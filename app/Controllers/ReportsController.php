<?php

namespace App\Controllers;

use App\Models\BatchModel;
use App\Models\ReportModel;
use App\Models\SettingsModel;
use App\Models\DispatchModel;
use App\Models\SupplierModel;

class ReportsController extends BaseController
{
    protected $batchModel;
    protected $reportModel;
    protected $settingsModel;
    protected $dispatchModel;
    protected $supplierModel;

    public function __construct()
    {
        $this->batchModel = new BatchModel();
        $this->reportModel = new ReportModel();
        $this->settingsModel = new SettingsModel();
        $this->dispatchModel = new DispatchModel();
        $this->supplierModel = new SupplierModel();
    }

    public function index()
    {
        try {
            // Get basic data for reports dashboard
            $data = [
                'title' => 'Reports & Analytics',
                'breadcrumb' => '<li class="breadcrumb-item active">Reports</li>'
            ];

            return view('reports/index', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Reports index failed: ' . $e->getMessage());
            return redirect()->to('/dashboard')->with('error', 'Failed to load reports page.');
        }
    }

    public function quickStats()
    {
        try {
            // Get quick statistics for dashboard
            $totalBatches = $this->batchModel->countAll();
            
            // Count active dispatches (pending + in_transit)
            $activeDispatches = $this->dispatchModel
                ->whereIn('status', ['pending', 'in_transit'])
                ->countAllResults();
            
            // Calculate current stock from available batches
            $currentStock = $this->batchModel
                ->selectSum('total_weight_mt')
                ->where('status', 'available')
                ->first();
            
            $stats = [
                'total_batches' => $totalBatches,
                'active_dispatches' => $activeDispatches,
                'current_stock' => number_format($currentStock['total_weight_mt'] ?? 0, 2),
                'monthly_revenue' => number_format($this->calculateMonthlyRevenue(), 2)
            ];

            return $this->response->setJSON([
                'success' => true,
                'stats' => $stats
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Quick stats failed: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load statistics: ' . $e->getMessage(),
                'stats' => [
                    'total_batches' => 0,
                    'active_dispatches' => 0,
                    'current_stock' => '0.00',
                    'monthly_revenue' => '0.00'
                ]
            ]);
        }
    }

    private function calculateMonthlyRevenue()
    {
        try {
            // Calculate revenue for current month
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-t');
            
            // Get delivered dispatches for current month
            $deliveredDispatches = $this->dispatchModel
                ->where('status', 'delivered')
                ->where('created_at >=', $startDate)
                ->where('created_at <=', $endDate)
                ->countAllResults();
            
            // Placeholder calculation - multiply by average value per dispatch
            // You can adjust this based on your actual revenue calculation logic
            $averageValuePerDispatch = 5000; // Placeholder value
            $monthlyRevenue = $deliveredDispatches * $averageValuePerDispatch;
                
            return $monthlyRevenue;
            
        } catch (\Exception $e) {
            log_message('error', 'Monthly revenue calculation failed: ' . $e->getMessage());
            return 0;
        }
    }

    public function batches()
    {
        try {
            $data = [
                'title' => 'Batch Reports',
                'breadcrumb' => '<li class="breadcrumb-item"><a href="' . site_url('reports') . '">Reports</a></li>
                               <li class="breadcrumb-item active">Batches</li>'
            ];

            return view('reports/batches', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Batch reports failed: ' . $e->getMessage());
            return redirect()->to('/reports')->with('error', 'Failed to load batch reports.');
        }
    }

    public function inventory()
    {
        try {
            $data = [
                'title' => 'Inventory Reports',
                'breadcrumb' => '<li class="breadcrumb-item"><a href="' . site_url('reports') . '">Reports</a></li>
                               <li class="breadcrumb-item active">Inventory</li>'
            ];

            return view('reports/inventory', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Inventory reports failed: ' . $e->getMessage());
            return redirect()->to('/reports')->with('error', 'Failed to load inventory reports.');
        }
    }

    public function financial()
    {
        try {
            $data = [
                'title' => 'Financial Reports',
                'breadcrumb' => '<li class="breadcrumb-item"><a href="' . site_url('reports') . '">Reports</a></li>
                               <li class="breadcrumb-item active">Financial</li>'
            ];

            return view('reports/financial', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Financial reports failed: ' . $e->getMessage());
            return redirect()->to('/reports')->with('error', 'Failed to load financial reports.');
        }
    }

    public function view($reportId = null)
    {
        try {
            if (!$reportId) {
                return redirect()->to('/reports')->with('error', 'Report ID is required.');
            }

            $data = [
                'title' => 'Report View',
                'reportId' => $reportId,
                'breadcrumb' => '<li class="breadcrumb-item"><a href="' . site_url('reports') . '">Reports</a></li>
                               <li class="breadcrumb-item active">View Report</li>'
            ];

            return view('reports/view', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Report view failed: ' . $e->getMessage());
            return redirect()->to('/reports')->with('error', 'Failed to load report.');
        }
    }

    public function export()
    {
        try {
            $type = $this->request->getPost('type') ?? $this->request->getGet('type');
            $format = $this->request->getPost('format') ?? $this->request->getGet('format') ?? 'pdf';
            
            if (!$type) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Report type is required'
                ]);
            }

            // Handle different report types
            switch ($type) {
                case 'batches':
                    return $this->exportBatchReport($format);
                case 'inventory':
                    return $this->exportInventoryReport($format);
                case 'financial':
                    return $this->exportFinancialReport($format);
                default:
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Invalid report type'
                    ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Report export failed: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Export failed: ' . $e->getMessage()
            ]);
        }
    }

    private function exportBatchReport($format)
    {
        // Placeholder for batch report export
        $filename = 'batch_report_' . date('Y-m-d_H-i-s') . '.' . $format;
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Batch report exported successfully',
            'filename' => $filename
        ]);
    }

    private function exportInventoryReport($format)
    {
        // Placeholder for inventory report export
        $filename = 'inventory_report_' . date('Y-m-d_H-i-s') . '.' . $format;
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Inventory report exported successfully',
            'filename' => $filename
        ]);
    }

    private function exportFinancialReport($format)
    {
        // Placeholder for financial report export
        $filename = 'financial_report_' . date('Y-m-d_H-i-s') . '.' . $format;
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Financial report exported successfully',
            'filename' => $filename
        ]);
    }
}
