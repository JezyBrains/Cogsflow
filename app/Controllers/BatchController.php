<?php

namespace App\Controllers;

class BatchController extends BaseController
{
    /**
     * Display list of all batches
     * 
     * @return string
     */
    public function index()
    {
        return view('batches/index');
    }
    
    /**
     * Display form to create a new batch
     * 
     * @return string
     */
    public function new()
    {
        return view('batches/create');
    }
    
    /**
     * Process the batch creation form
     * Logs supplier batch information including weight and moisture
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function create()
    {
        // This is a stub method for creating a new batch
        // In Phase 2, we will implement the actual database operations
        
        // Flash a success message
        session()->setFlashdata('success', 'Batch was successfully created.');
        
        // Redirect to the batch list
        return redirect()->to('/batches');
    }
}
