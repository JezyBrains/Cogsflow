<?php

namespace App\Controllers;

use App\Models\PurchaseOrderModel;
use App\Models\BatchModel;
use App\Models\DispatchModel;
use App\Models\InventoryModel;
use App\Models\InventoryAdjustmentModel;

class WorkflowDashboardController extends BaseController
{
    protected $purchaseOrderModel;
    protected $batchModel;
    protected $dispatchModel;
    protected $inventoryModel;
    protected $inventoryAdjustmentModel;

    public function __construct()
    {
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->batchModel = new BatchModel();
        $this->dispatchModel = new DispatchModel();
        $this->inventoryModel = new InventoryModel();
        $this->inventoryAdjustmentModel = new InventoryAdjustmentModel();
    }

    /**
     * Main workflow dashboard
     */
    public function index()
    {
        $session = session();
        $userId = $session->get('user_id');
        
        $data = [
            'title' => 'Procurement Workflow Dashboard',
            'workflow_stats' => $this->getWorkflowStats(),
            'pending_approvals' => $this->getPendingApprovals($userId),
            'recent_activities' => $this->getRecentActivities(),
            'po_fulfillment' => $this->getPOFulfillmentOverview(),
            'discrepancy_alerts' => $this->getDiscrepancyAlerts(),
            'inventory_summary' => $this->getInventorySummary()
        ];

        return view('workflow/dashboard', $data);
    }

    /**
     * Get comprehensive workflow statistics
     */
    private function getWorkflowStats()
    {
        $stats = [];

        // Purchase Order Stats
        $poStats = $this->purchaseOrderModel->getPurchaseOrderStats();
        $stats['purchase_orders'] = $poStats;

        // Batch Stats
        $batchStats = $this->batchModel->getBatchStats();
        $stats['batches'] = $batchStats;

        // Dispatch Stats
        $dispatchStats = $this->dispatchModel->getDispatchStats();
        $stats['dispatches'] = $dispatchStats;

        // Workflow Efficiency Metrics
        $stats['efficiency'] = $this->calculateWorkflowEfficiency();

        return $stats;
    }

    /**
     * Get pending approvals for current user
     */
    private function getPendingApprovals($userId)
    {
        $approvals = [];

        // Pending PO approvals (if user has permission)
        $pendingPOs = $this->purchaseOrderModel->where('status', 'pending')->findAll();
        
        // Pending batch approvals (only for POs approved by this user)
        $pendingBatches = $this->batchModel->getBatchesPendingApprovalByUser($userId);

        // Dispatches awaiting inspection
        $awaitingInspection = $this->dispatchModel->getDispatchesAwaitingInspection();

        $approvals = [
            'pending_pos' => $pendingPOs,
            'pending_batches' => $pendingBatches,
            'awaiting_inspection' => $awaitingInspection,
            'total_pending' => count($pendingPOs) + count($pendingBatches) + count($awaitingInspection)
        ];

        return $approvals;
    }

    /**
     * Get recent workflow activities
     */
    private function getRecentActivities()
    {
        $activities = [];

        // Recent PO approvals
        $recentPOs = $this->purchaseOrderModel->select('po_number, status, approved_at, approved_by')
                                             ->where('approved_at IS NOT NULL')
                                             ->orderBy('approved_at', 'DESC')
                                             ->limit(5)
                                             ->findAll();

        // Recent batch approvals
        $recentBatches = $this->batchModel->select('batch_number, status, approved_at, approved_by')
                                         ->where('approved_at IS NOT NULL')
                                         ->orderBy('approved_at', 'DESC')
                                         ->limit(5)
                                         ->findAll();

        // Recent deliveries
        $recentDeliveries = $this->dispatchModel->select('dispatch_number, status, inspection_date, received_by')
                                               ->where('status', 'delivered')
                                               ->orderBy('inspection_date', 'DESC')
                                               ->limit(5)
                                               ->findAll();

        return [
            'po_approvals' => $recentPOs,
            'batch_approvals' => $recentBatches,
            'deliveries' => $recentDeliveries
        ];
    }

    /**
     * Get PO fulfillment overview
     */
    private function getPOFulfillmentOverview()
    {
        $builder = $this->purchaseOrderModel->db->table('purchase_orders po');
        $builder->select('po.*, s.name as supplier_name');
        $builder->join('suppliers s', 's.id = po.supplier_id', 'left');
        $builder->where('po.status', 'approved');
        $builder->where('po.remaining_quantity_mt >', 0);
        $builder->orderBy('po.order_date', 'ASC');
        
        $activePOs = $builder->get()->getResultArray();

        $fulfillmentData = [];
        foreach ($activePOs as $po) {
            $progress = $this->purchaseOrderModel->getPOFulfillmentProgress($po['id']);
            $fulfillmentData[] = array_merge($po, $progress);
        }

        return $fulfillmentData;
    }

    /**
     * Get discrepancy alerts
     */
    private function getDiscrepancyAlerts()
    {
        $builder = $this->dispatchModel->db->table('dispatches d');
        $builder->select('d.*, b.batch_number, s.name as supplier_name');
        $builder->join('batches b', 'b.id = d.batch_id', 'left');
        $builder->join('suppliers s', 's.id = b.supplier_id', 'left');
        $builder->where('d.discrepancies IS NOT NULL');
        $builder->where('d.discrepancies !=', '');
        $builder->orderBy('d.inspection_date', 'DESC');
        $builder->limit(10);

        return $builder->get()->getResultArray();
    }

    /**
     * Get inventory summary with batch traceability
     */
    private function getInventorySummary()
    {
        $inventory = $this->inventoryModel->findAll();
        
        $summary = [];
        foreach ($inventory as $item) {
            // Get recent adjustments for this grain type
            $recentAdjustments = $this->inventoryAdjustmentModel
                                     ->where('grain_type', $item['grain_type'])
                                     ->orderBy('created_at', 'DESC')
                                     ->limit(5)
                                     ->findAll();

            $summary[] = [
                'grain_type' => $item['grain_type'],
                'quantity_mt' => $item['quantity_mt'],
                'last_updated' => $item['last_updated'],
                'recent_adjustments' => $recentAdjustments
            ];
        }

        return $summary;
    }

    /**
     * Calculate workflow efficiency metrics
     */
    private function calculateWorkflowEfficiency()
    {
        $efficiency = [];

        // Average PO approval time
        $builder = $this->purchaseOrderModel->db->table('purchase_orders');
        $builder->select('AVG(TIMESTAMPDIFF(HOUR, created_at, approved_at)) as avg_approval_hours');
        $builder->where('status', 'approved');
        $builder->where('approved_at IS NOT NULL');
        $result = $builder->get()->getRowArray();
        $efficiency['avg_po_approval_hours'] = round($result['avg_approval_hours'] ?? 0, 2);

        // Average batch approval time
        $builder = $this->batchModel->db->table('batches');
        $builder->select('AVG(TIMESTAMPDIFF(HOUR, created_at, approved_at)) as avg_approval_hours');
        $builder->where('status', 'approved');
        $builder->where('approved_at IS NOT NULL');
        $result = $builder->get()->getRowArray();
        $efficiency['avg_batch_approval_hours'] = round($result['avg_approval_hours'] ?? 0, 2);

        // Average delivery time
        $builder = $this->dispatchModel->db->table('dispatches');
        $builder->select('AVG(TIMESTAMPDIFF(HOUR, created_at, inspection_date)) as avg_delivery_hours');
        $builder->where('status', 'delivered');
        $builder->where('inspection_date IS NOT NULL');
        $result = $builder->get()->getRowArray();
        $efficiency['avg_delivery_hours'] = round($result['avg_delivery_hours'] ?? 0, 2);

        // Discrepancy rate
        $totalDeliveries = $this->dispatchModel->where('status', 'delivered')->countAllResults();
        $deliveriesWithDiscrepancies = $this->dispatchModel->where('status', 'delivered')
                                                          ->where('discrepancies IS NOT NULL')
                                                          ->where('discrepancies !=', '')
                                                          ->countAllResults();
        
        $efficiency['discrepancy_rate'] = $totalDeliveries > 0 ? 
            round(($deliveriesWithDiscrepancies / $totalDeliveries) * 100, 2) : 0;

        return $efficiency;
    }

    /**
     * Get workflow analytics (AJAX endpoint)
     */
    public function getAnalytics()
    {
        try {
            $period = $this->request->getGet('period') ?? '30'; // days
            
            $analytics = [
                'po_trends' => $this->getPOTrends($period),
                'batch_trends' => $this->getBatchTrends($period),
                'delivery_trends' => $this->getDeliveryTrends($period),
                'efficiency_trends' => $this->getEfficiencyTrends($period)
            ];

            return $this->response->setJSON($analytics);
        } catch (\Exception $e) {
            log_message('error', 'Workflow Analytics Error: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Failed to get analytics data']);
        }
    }

    /**
     * Get PO trends over time
     */
    private function getPOTrends($days)
    {
        $builder = $this->purchaseOrderModel->db->table('purchase_orders');
        $builder->select('DATE(created_at) as date, COUNT(*) as count, status');
        $builder->where('created_at >=', date('Y-m-d', strtotime("-{$days} days")));
        $builder->groupBy(['DATE(created_at)', 'status']);
        $builder->orderBy('date', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Get batch trends over time
     */
    private function getBatchTrends($days)
    {
        $builder = $this->batchModel->db->table('batches');
        $builder->select('DATE(created_at) as date, COUNT(*) as count, SUM(total_weight_kg) as total_weight, status');
        $builder->where('created_at >=', date('Y-m-d', strtotime("-{$days} days")));
        $builder->groupBy(['DATE(created_at)', 'status']);
        $builder->orderBy('date', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Get delivery trends over time
     */
    private function getDeliveryTrends($days)
    {
        $builder = $this->dispatchModel->db->table('dispatches d');
        $builder->select('DATE(d.inspection_date) as date, COUNT(*) as count, SUM(d.actual_weight_mt) as total_weight');
        $builder->where('d.status', 'delivered');
        $builder->where('d.inspection_date >=', date('Y-m-d', strtotime("-{$days} days")));
        $builder->groupBy('DATE(d.inspection_date)');
        $builder->orderBy('date', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Get efficiency trends over time
     */
    private function getEfficiencyTrends($days)
    {
        // Get daily efficiency metrics
        $builder = $this->dispatchModel->db->table('dispatches d');
        $builder->select('DATE(d.inspection_date) as date, 
                         COUNT(*) as total_deliveries,
                         SUM(CASE WHEN d.discrepancies IS NOT NULL AND d.discrepancies != "" THEN 1 ELSE 0 END) as discrepancy_count');
        $builder->where('d.status', 'delivered');
        $builder->where('d.inspection_date >=', date('Y-m-d', strtotime("-{$days} days")));
        $builder->groupBy('DATE(d.inspection_date)');
        $builder->orderBy('date', 'ASC');

        $results = $builder->get()->getResultArray();
        
        // Calculate daily discrepancy rates
        foreach ($results as &$result) {
            $result['discrepancy_rate'] = $result['total_deliveries'] > 0 ? 
                round(($result['discrepancy_count'] / $result['total_deliveries']) * 100, 2) : 0;
        }

        return $results;
    }

    /**
     * Export workflow report
     */
    public function exportReport()
    {
        try {
            $format = $this->request->getGet('format') ?? 'csv';
            $reportData = $this->generateWorkflowReport();

            switch ($format) {
                case 'csv':
                    return $this->exportCSV($reportData);
                case 'pdf':
                    return $this->exportPDF($reportData);
                default:
                    return $this->response->setJSON(['error' => 'Invalid export format']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Workflow Report Export Error: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Failed to export report']);
        }
    }

    /**
     * Generate comprehensive workflow report data
     */
    private function generateWorkflowReport()
    {
        return [
            'summary' => $this->getWorkflowStats(),
            'po_details' => $this->purchaseOrderModel->getPurchaseOrdersWithSuppliers(),
            'batch_details' => $this->batchModel->getBatchesWithSuppliers(),
            'dispatch_details' => $this->dispatchModel->getDispatchesWithBatchInfo(),
            'discrepancies' => $this->getDiscrepancyAlerts(),
            'inventory' => $this->getInventorySummary(),
            'generated_at' => date('Y-m-d H:i:s')
        ];
    }

    /**
     * Export report as CSV
     */
    private function exportCSV($data)
    {
        $filename = 'workflow_report_' . date('Y-m-d_H-i-s') . '.csv';
        
        $this->response->setHeader('Content-Type', 'text/csv');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Write summary section
        fputcsv($output, ['Workflow Summary Report - Generated: ' . $data['generated_at']]);
        fputcsv($output, []);
        
        // PO Summary
        fputcsv($output, ['Purchase Orders Summary']);
        fputcsv($output, ['Total Orders', 'Pending', 'Approved', 'Completed', 'Total Value']);
        fputcsv($output, [
            $data['summary']['purchase_orders']['total_orders'],
            $data['summary']['purchase_orders']['pending_orders'],
            $data['summary']['purchase_orders']['approved_orders'],
            $data['summary']['purchase_orders']['completed_orders'],
            number_format($data['summary']['purchase_orders']['total_value'], 2)
        ]);
        fputcsv($output, []);
        
        // Batch Summary
        fputcsv($output, ['Batches Summary']);
        fputcsv($output, ['Total Batches', 'Pending', 'Approved', 'Delivered', 'Total Weight (MT)']);
        fputcsv($output, [
            $data['summary']['batches']['total_batches'],
            $data['summary']['batches']['pending_batches'],
            $data['summary']['batches']['approved_batches'],
            $data['summary']['batches']['delivered_batches'],
            number_format($data['summary']['batches']['total_weight_mt'] ?? 0, 3)
        ]);
        fputcsv($output, []);
        
        // Efficiency Metrics
        fputcsv($output, ['Efficiency Metrics']);
        fputcsv($output, ['Avg PO Approval (hours)', 'Avg Batch Approval (hours)', 'Avg Delivery Time (hours)', 'Discrepancy Rate (%)']);
        fputcsv($output, [
            $data['summary']['efficiency']['avg_po_approval_hours'],
            $data['summary']['efficiency']['avg_batch_approval_hours'],
            $data['summary']['efficiency']['avg_delivery_hours'],
            $data['summary']['efficiency']['discrepancy_rate']
        ]);
        
        fclose($output);
        return $this->response;
    }

    /**
     * Export report as PDF (simplified HTML version)
     */
    private function exportPDF($data)
    {
        $filename = 'workflow_report_' . date('Y-m-d_H-i-s') . '.html';
        
        $this->response->setHeader('Content-Type', 'text/html');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        
        $html = view('workflow/report_template', ['data' => $data]);
        
        return $this->response->setBody($html);
    }
}
