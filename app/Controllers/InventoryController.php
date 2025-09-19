<?php

namespace App\Controllers;

class InventoryController extends BaseController
{
    /**
     * Display inventory status and list
     * 
     * @return string
     */
    public function index()
    {
        $inventoryModel = new \App\Models\InventoryModel();
        $adjustmentModel = new \App\Models\InventoryAdjustmentModel();
        
        // Get inventory data
        $data = [
            'inventory_summary' => $inventoryModel->getInventorySummary(),
            'active_inventory' => $inventoryModel->getActiveItems(),
            'low_stock_items' => $inventoryModel->getLowStockItems(),
            'recent_adjustments' => $adjustmentModel->getAdjustmentHistory(null, 10)
        ];
        
        return view('inventory/index', $data);
    }
    
    /**
     * Display form to adjust inventory
     * 
     * @return string
     */
    public function showAdjustForm()
    {
        return view('inventory/adjust');
    }
    
    /**
     * Process the inventory adjustment
     * Makes stock movements and balance updates
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function adjust()
    {
        $adjustmentModel = new \App\Models\InventoryAdjustmentModel();
        
        // Validate the form data
        $rules = [
            'grain_type' => 'required',
            'adjustment_type' => 'required|in_list[Stock In,Stock Out,Stock Transfer,Stock Correction,Damage/Loss]',
            'quantity' => 'required|decimal|greater_than[0]',
            'adjustment_date' => 'required|valid_date',
            'adjusted_by' => 'required',
            'reason' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Get form data
        $data = [
            'grain_type' => $this->request->getPost('grain_type'),
            'adjustment_type' => $this->request->getPost('adjustment_type'),
            'quantity' => floatval($this->request->getPost('quantity')),
            'adjustment_date' => $this->request->getPost('adjustment_date'),
            'reference' => $this->request->getPost('reference'),
            'adjusted_by' => $this->request->getPost('adjusted_by'),
            'reason' => $this->request->getPost('reason')
        ];
        
        try {
            // Record the adjustment
            $adjustmentId = $adjustmentModel->recordAdjustment($data);
            
            if ($adjustmentId) {
                session()->setFlashdata('success', 'Inventory was successfully adjusted.');
            } else {
                session()->setFlashdata('error', 'Failed to adjust inventory. Please try again.');
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Inventory adjustment error: ' . $e->getMessage());
            session()->setFlashdata('error', 'An error occurred while adjusting inventory: ' . $e->getMessage());
        }
        
        // Redirect to the inventory list
        return redirect()->to('/inventory');
    }
}
