<?php

namespace App\Controllers;

use App\Models\InventoryModel;
use App\Models\InventoryAdjustmentModel;
use App\Models\BatchModel;

class DashboardController extends BaseController
{
    /**
     * Display the dashboard homepage
     * 
     * @return string
     */
    public function index()
    {
        $inventoryModel = new InventoryModel();
        $adjustmentModel = new InventoryAdjustmentModel();
        $batchModel = new BatchModel();
        $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
        $dispatchModel = new \App\Models\DispatchModel();
        
        // Get dashboard statistics
        $batchStats = $batchModel->getBatchStats();
        $purchaseOrderStats = $this->getPurchaseOrderStats($purchaseOrderModel);
        $dispatchStats = $dispatchModel->getDispatchStats();
        
        // Get system settings for currency
        $settingsModel = new \App\Models\SettingsModel();
        $defaultCurrency = $settingsModel->getSetting('default_currency', 'TSH');
        
        // Get dashboard data
        $data = [
            'inventory_summary' => $inventoryModel->getInventorySummary(),
            'recent_batches' => $batchModel->getRecentBatches(5),
            'low_stock_items' => $inventoryModel->getLowStockItems(),
            'recent_adjustments' => $adjustmentModel->getAdjustmentHistory(null, 5),
            'active_inventory' => $inventoryModel->getActiveItems(),
            'batch_stats' => $batchStats,
            'purchase_order_stats' => $purchaseOrderStats,
            'dispatch_stats' => $dispatchStats,
            'default_currency' => $defaultCurrency
        ];
        
        return view('dashboard/index', $data);
    }
    
    /**
     * Get purchase order statistics with dynamic status calculation
     */
    private function getPurchaseOrderStats($purchaseOrderModel)
    {
        // Get all purchase orders with transferred quantities
        $builder = $purchaseOrderModel->db->table('purchase_orders po');
        $builder->select('po.*, COALESCE(SUM(b.total_weight_mt), 0) as transferred_quantity_mt');
        $builder->join('batches b', 'b.purchase_order_id = po.id', 'left');
        $builder->groupBy('po.id');
        $purchaseOrders = $builder->get()->getResultArray();
        
        $stats = [
            'total_purchase_orders' => count($purchaseOrders),
            'pending_orders' => 0,
            'transferring_orders' => 0,
            'completed_orders' => 0,
            'total_value' => 0
        ];
        
        foreach ($purchaseOrders as $po) {
            $transferredQty = (float)$po['transferred_quantity_mt'];
            $totalQty = (float)$po['quantity_mt'];
            $stats['total_value'] += (float)$po['total_amount'];
            
            // Calculate dynamic status
            if ($transferredQty == 0) {
                $stats['pending_orders']++;
            } elseif ($transferredQty >= $totalQty) {
                $stats['completed_orders']++;
            } else {
                $stats['transferring_orders']++;
            }
        }
        
        return $stats;
    }
}
