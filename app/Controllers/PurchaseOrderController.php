<?php

namespace App\Controllers;

class PurchaseOrderController extends BaseController
{
    /**
     * Display list of all purchase orders
     * 
     * @return string
     */
    public function index()
    {
        return view('purchase_orders/index');
    }
    
    /**
     * Display form to create a new purchase order
     * 
     * @return string
     */
    public function new()
    {
        return view('purchase_orders/create');
    }
    
    /**
     * Process the purchase order creation form
     * Raises new purchase orders
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function create()
    {
        // This is a stub method for creating a new purchase order
        // In Phase 2, we will implement the actual database operations
        
        // Flash a success message
        session()->setFlashdata('success', 'Purchase order was successfully created.');
        
        // Redirect to the purchase order list
        return redirect()->to('/purchase-orders');
    }
}
