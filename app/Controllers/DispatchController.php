<?php

namespace App\Controllers;

class DispatchController extends BaseController
{
    /**
     * Display list of all dispatches
     * 
     * @return string
     */
    public function index()
    {
        return view('dispatches/index');
    }
    
    /**
     * Display form to create a new dispatch
     * 
     * @return string
     */
    public function new()
    {
        return view('dispatches/create');
    }
    
    /**
     * Process the dispatch creation form
     * Registers transporter & batch dispatch details
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function create()
    {
        // This is a stub method for creating a new dispatch
        // In Phase 2, we will implement the actual database operations
        
        // Flash a success message
        session()->setFlashdata('success', 'Dispatch was successfully created.');
        
        // Redirect to the dispatch list
        return redirect()->to('/dispatches');
    }
    
    /**
     * Display form to receive an incoming dispatch
     * 
     * @param int $id The dispatch ID
     * @return string
     */
    public function showReceiveForm($id = null)
    {
        return view('dispatches/receive', ['id' => $id]);
    }
    
    /**
     * Process the dispatch receiving
     * Verifies and receives incoming cargo
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function receive()
    {
        // This is a stub method for receiving a dispatch
        // In Phase 2, we will implement the actual database operations
        
        // Flash a success message
        session()->setFlashdata('success', 'Dispatch was successfully received.');
        
        // Redirect to the dispatch list
        return redirect()->to('/dispatches');
    }
}
