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
        return view('inventory/index');
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
        // This is a stub method for adjusting inventory
        // In Phase 2, we will implement the actual database operations
        
        // Flash a success message
        session()->setFlashdata('success', 'Inventory was successfully adjusted.');
        
        // Redirect to the inventory list
        return redirect()->to('/inventory');
    }
}
