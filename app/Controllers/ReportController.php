<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ReportModel;
use App\Models\UserModel;

class ReportController extends BaseController
{
    protected $reportModel;
    protected $userModel;

    public function __construct()
    {
        $this->reportModel = new ReportModel();
        $this->userModel = new UserModel();
    }

    /**
     * Display reports dashboard
     */
    public function index()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        // Get user roles
        $user = $this->userModel->find($userId);
        $userRoles = $this->getUserRoles($userId);
        
        // Debug: Log user roles
        log_message('debug', 'User ID: ' . $userId . ', User Roles: ' . json_encode($userRoles));
        
        // If no roles, assign admin role for testing
        if (empty($userRoles)) {
            $userRoles = ['admin'];
            log_message('debug', 'No roles found, defaulting to admin role');
        }

        $reports = $this->reportModel->getReportsByCategory($userRoles);
        log_message('debug', 'Reports found: ' . json_encode(array_keys($reports)));
        
        // Debug: Check what we're passing to the view
        $debugInfo = [
            'reports_type' => gettype($reports),
            'reports_empty' => empty($reports),
            'reports_count' => is_array($reports) ? count($reports) : 'not array',
            'reports_keys' => is_array($reports) ? array_keys($reports) : 'not array'
        ];
        log_message('debug', 'Debug info: ' . json_encode($debugInfo));
        
        $data = [
            'title' => 'Reports & Analytics',
            'reports' => $reports,
            'user_roles' => $userRoles,
            'debug_info' => $debugInfo  // Temporary debug info
        ];

        return view('reports/index', $data);
    }

    /**
     * Display specific report
     */
    public function view($slug)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $report = $this->reportModel->getReportBySlug($slug);
        if (!$report) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Report not found');
        }

        // Check user access
        $userRoles = $this->getUserRoles($userId);
        if (!$this->hasReportAccess($report, $userRoles)) {
            return redirect()->to('/reports')->with('error', 'You do not have access to this report');
        }

        // Get filter options
        $filterOptions = $this->getFilterOptions($report);

        $data = [
            'title' => $report['name'],
            'report' => $report,
            'filter_options' => $filterOptions,
            'user_roles' => $userRoles
        ];

        return view('reports/view', $data);
    }

    /**
     * Generate report data via AJAX
     */
    public function generate($slug)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $report = $this->reportModel->getReportBySlug($slug);
        if (!$report) {
            return $this->response->setJSON(['error' => 'Report not found']);
        }

        // Check user access
        $userRoles = $this->getUserRoles($userId);
        if (!$this->hasReportAccess($report, $userRoles)) {
            return $this->response->setJSON(['error' => 'Access denied']);
        }

        // Get filters from request
        $filters = $this->request->getGet();

        try {
            $reportData = $this->generateReportData($report['slug'], $filters);
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $reportData,
                'chart_config' => json_decode($report['chart_config'], true),
                'report_info' => [
                    'name' => $report['name'],
                    'description' => $report['description'],
                    'generated_at' => date('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Report generation failed: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Failed to generate report']);
        }
    }

    /**
     * Export report to PDF
     */
    public function exportPdf($slug)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $report = $this->reportModel->getReportBySlug($slug);
        if (!$report) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Report not found');
        }

        // Check user access
        $userRoles = $this->getUserRoles($userId);
        if (!$this->hasReportAccess($report, $userRoles)) {
            return redirect()->to('/reports')->with('error', 'Access denied');
        }

        // Get filters and generate data
        $filters = $this->request->getGet();
        $reportData = $this->generateReportData($report['slug'], $filters);

        // Generate PDF
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator('GrainFlow System');
        $pdf->SetAuthor('GrainFlow');
        $pdf->SetTitle($report['name'] . ' Report');
        $pdf->SetSubject('Report Export');

        // Set margins
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 25);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', '', 12);

        // Generate PDF content
        $html = $this->generatePdfContent($report, $reportData, $filters);
        $pdf->writeHTML($html, true, false, true, false, '');

        // Output PDF
        $filename = strtolower(str_replace(' ', '_', $report['name'])) . '_' . date('Y-m-d') . '.pdf';
        $pdf->Output($filename, 'D');
    }

    /**
     * Export report to Excel
     */
    public function exportExcel($slug)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $report = $this->reportModel->getReportBySlug($slug);
        if (!$report) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Report not found');
        }

        // Check user access
        $userRoles = $this->getUserRoles($userId);
        if (!$this->hasReportAccess($report, $userRoles)) {
            return redirect()->to('/reports')->with('error', 'Access denied');
        }

        // Get filters and generate data
        $filters = $this->request->getGet();
        $reportData = $this->generateReportData($report['slug'], $filters);

        // Create Excel file
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set title
        $sheet->setTitle($report['name']);
        $sheet->setCellValue('A1', $report['name']);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);

        // Add generation info
        $sheet->setCellValue('A2', 'Generated on: ' . date('Y-m-d H:i:s'));
        $sheet->setCellValue('A3', 'Description: ' . $report['description']);

        // Add data starting from row 5
        $this->populateExcelData($sheet, $reportData, $report['slug']);

        // Auto-size columns
        foreach (range('A', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Create writer and output
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        $filename = strtolower(str_replace(' ', '_', $report['name'])) . '_' . date('Y-m-d') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Generate report data based on slug
     */
    private function generateReportData($slug, $filters = [])
    {
        switch ($slug) {
            case 'stock_summary':
                return $this->reportModel->getStockSummaryData($filters);
            case 'expense_analysis':
                return $this->reportModel->getExpenseAnalysisData($filters);
            case 'dispatch_performance':
                return $this->reportModel->getDispatchPerformanceData($filters);
            case 'supplier_performance':
                return $this->reportModel->getSupplierPerformanceData($filters);
            case 'batch_analytics':
                return $this->reportModel->getBatchAnalyticsData($filters);
            default:
                throw new \Exception('Unknown report type');
        }
    }

    /**
     * Get filter options for report
     */
    private function getFilterOptions($report)
    {
        $filters = json_decode($report['filters'], true);
        $options = [];

        if (isset($filters['grain_type']) && $filters['grain_type']) {
            $options['grain_types'] = $this->reportModel->getFilterOptions('grain_types');
        }
        if (isset($filters['supplier']) && $filters['supplier']) {
            $options['suppliers'] = $this->reportModel->getFilterOptions('suppliers');
        }
        if (isset($filters['category']) && $filters['category']) {
            $options['categories'] = $this->reportModel->getFilterOptions('expense_categories');
        }
        if (isset($filters['status']) && $filters['status']) {
            $options['statuses'] = $this->reportModel->getFilterOptions('dispatch_statuses');
        }
        if (isset($filters['vehicle']) && $filters['vehicle']) {
            $options['vehicles'] = $this->reportModel->getFilterOptions('vehicles');
        }

        return $options;
    }

    /**
     * Debug method to check reports data
     */
    public function debug()
    {
        $db = \Config\Database::connect();
        
        // Check if reports table exists
        $tableExists = $db->tableExists('reports');
        
        // Get all reports
        $allReports = [];
        if ($tableExists) {
            $allReports = $db->table('reports')->get()->getResultArray();
        }
        
        // Get user roles
        $userId = session()->get('user_id');
        $userRoles = $this->getUserRoles($userId);
        
        // Test role filtering
        $filteredReports = [];
        foreach ($allReports as $report) {
            if (!empty($report['roles'])) {
                $reportRoles = json_decode($report['roles'], true);
                $hasAccess = is_array($reportRoles) && array_intersect($userRoles, $reportRoles);
                $filteredReports[] = [
                    'name' => $report['name'],
                    'roles_raw' => $report['roles'],
                    'roles_decoded' => $reportRoles,
                    'user_roles' => $userRoles,
                    'has_access' => $hasAccess,
                    'intersection' => array_intersect($userRoles, $reportRoles ?: [])
                ];
            }
        }
        
        // Test the actual getReportsByCategory method
        $reportsByCategory = $this->reportModel->getReportsByCategory($userRoles);
        
        $debug = [
            'table_exists' => $tableExists,
            'user_id' => $userId,
            'user_roles' => $userRoles,
            'total_reports' => count($allReports),
            'role_filtering_test' => $filteredReports,
            'reports_by_category' => $reportsByCategory,
            'category_count' => count($reportsByCategory)
        ];
        
        return $this->response->setJSON($debug);
    }

    /**
     * Check if user has access to report
     */
    private function hasReportAccess($report, $userRoles)
    {
        if (empty($report['roles'])) {
            return true; // No role restriction
        }

        $reportRoles = json_decode($report['roles'], true);
        return !empty(array_intersect($userRoles, $reportRoles));
    }

    /**
     * Get user roles
     */
    private function getUserRoles($userId)
    {
        $db = \Config\Database::connect();
        $roles = $db->table('user_roles ur')
                   ->select('r.name')
                   ->join('roles r', 'ur.role_id = r.id')
                   ->where('ur.user_id', $userId)
                   ->get()
                   ->getResultArray();

        return array_column($roles, 'name');
    }

    /**
     * Generate PDF content
     */
    private function generatePdfContent($report, $data, $filters)
    {
        $html = '<h1>' . $report['name'] . '</h1>';
        $html .= '<p><strong>Description:</strong> ' . $report['description'] . '</p>';
        $html .= '<p><strong>Generated on:</strong> ' . date('Y-m-d H:i:s') . '</p>';

        // Add filters info
        if (!empty($filters)) {
            $html .= '<h3>Applied Filters:</h3><ul>';
            foreach ($filters as $key => $value) {
                if (!empty($value)) {
                    $html .= '<li><strong>' . ucwords(str_replace('_', ' ', $key)) . ':</strong> ' . $value . '</li>';
                }
            }
            $html .= '</ul>';
        }

        // Add data table
        if (!empty($data)) {
            $html .= '<h3>Report Data:</h3>';
            $html .= '<table border="1" cellpadding="5" cellspacing="0">';
            
            // Table headers
            $html .= '<thead><tr>';
            foreach (array_keys($data[0]) as $header) {
                $html .= '<th>' . ucwords(str_replace('_', ' ', $header)) . '</th>';
            }
            $html .= '</tr></thead>';

            // Table data
            $html .= '<tbody>';
            foreach ($data as $row) {
                $html .= '<tr>';
                foreach ($row as $cell) {
                    $html .= '<td>' . htmlspecialchars($cell) . '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</tbody></table>';
        }

        return $html;
    }

    /**
     * Populate Excel sheet with data
     */
    private function populateExcelData($sheet, $data, $reportSlug)
    {
        if (empty($data)) {
            $sheet->setCellValue('A5', 'No data available');
            return;
        }

        $row = 5;
        $col = 1;

        // Add headers
        foreach (array_keys($data[0]) as $header) {
            $sheet->setCellValueByColumnAndRow($col, $row, ucwords(str_replace('_', ' ', $header)));
            $sheet->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
            $col++;
        }

        // Add data
        $row++;
        foreach ($data as $dataRow) {
            $col = 1;
            foreach ($dataRow as $cell) {
                $sheet->setCellValueByColumnAndRow($col, $row, $cell);
                $col++;
            }
            $row++;
        }
    }

    /**
     * Get quick statistics for dashboard
     */
    public function quickStats()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        try {
            $db = \Config\Database::connect();
            
            // Get total batches
            $totalBatches = $db->table('batches')->countAllResults();
            
            // Get active dispatches
            $activeDispatches = $db->table('dispatches')
                                  ->where('status !=', 'delivered')
                                  ->countAllResults();
            
            // Get current stock (sum of all batch quantities minus dispatched quantities)
            $totalIncoming = $db->table('batches')
                               ->selectSum('total_weight_kg')
                               ->get()
                               ->getRow()
                               ->total_weight_kg ?? 0;
            $totalIncoming = $totalIncoming / 1000; // Convert to MT
            
            // Get total outgoing (sum of dispatched batch weights)
            $totalOutgoing = $db->table('dispatches d')
                               ->select('SUM(b.total_weight_kg) as total_dispatched')
                               ->join('batches b', 'd.batch_id = b.id')
                               ->where('d.status !=', 'cancelled')
                               ->get()
                               ->getRow()
                               ->total_dispatched ?? 0;
            
            $currentStock = $totalIncoming - $totalOutgoing;
            
            // Get monthly revenue (if expenses table exists)
            $monthlyRevenue = 0;
            if ($db->tableExists('expenses')) {
                $monthlyRevenue = $db->table('expenses')
                                    ->selectSum('amount')
                                    ->where('MONTH(expense_date)', date('m'))
                                    ->where('YEAR(expense_date)', date('Y'))
                                    ->get()
                                    ->getRow()
                                    ->amount ?? 0;
            }
            
            return $this->response->setJSON([
                'success' => true,
                'stats' => [
                    'total_batches' => number_format($totalBatches),
                    'active_dispatches' => number_format($activeDispatches),
                    'current_stock' => number_format($currentStock, 2),
                    'monthly_revenue' => number_format($monthlyRevenue, 2)
                ]
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Failed to get quick stats: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Failed to load statistics']);
        }
    }

    /**
     * Export all reports
     */
    public function exportAll($format)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $userRoles = $this->getUserRoles($userId);
        $reports = $this->reportModel->getReportsByRole($userRoles);
        
        if ($format === 'pdf') {
            return $this->exportAllToPdf($reports, $userRoles);
        } elseif ($format === 'excel') {
            return $this->exportAllToExcel($reports, $userRoles);
        }
        
        return redirect()->to('/reports')->with('error', 'Invalid export format');
    }

    /**
     * Export all reports to PDF
     */
    private function exportAllToPdf($reports, $userRoles)
    {
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        $pdf->SetCreator('GrainFlow System');
        $pdf->SetAuthor('GrainFlow');
        $pdf->SetTitle('Comprehensive Reports Export');
        $pdf->SetSubject('All Reports Export');
        
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);
        $pdf->SetAutoPageBreak(TRUE, 25);
        
        foreach ($reports as $report) {
            if (!$this->hasReportAccess($report, $userRoles)) {
                continue;
            }
            
            $pdf->AddPage();
            $pdf->SetFont('helvetica', 'B', 16);
            $pdf->Cell(0, 10, $report['name'], 0, 1, 'C');
            
            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(0, 5, $report['description'], 0, 1, 'L');
            $pdf->Ln(5);
            
            try {
                $reportData = $this->generateReportData($report['slug'], []);
                $html = $this->generatePdfContent($report, $reportData, []);
                $pdf->writeHTML($html, true, false, true, false, '');
            } catch (\Exception $e) {
                $pdf->Cell(0, 10, 'Error generating report: ' . $e->getMessage(), 0, 1, 'L');
            }
        }
        
        $filename = 'all_reports_' . date('Y-m-d') . '.pdf';
        $pdf->Output($filename, 'D');
    }

    /**
     * Export all reports to Excel
     */
    private function exportAllToExcel($reports, $userRoles)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->removeSheetByIndex(0); // Remove default sheet
        
        foreach ($reports as $index => $report) {
            if (!$this->hasReportAccess($report, $userRoles)) {
                continue;
            }
            
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle(substr($report['name'], 0, 31)); // Excel sheet name limit
            
            try {
                $reportData = $this->generateReportData($report['slug'], []);
                $this->populateExcelData($sheet, $reportData, $report['slug']);
            } catch (\Exception $e) {
                $sheet->setCellValue('A1', 'Error: ' . $e->getMessage());
            }
        }
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'all_reports_' . date('Y-m-d') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
}
