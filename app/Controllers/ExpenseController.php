<?php

namespace App\Controllers;

class ExpenseController extends BaseController
{
    /**
     * Display list of all expenses
     * 
     * @return string
     */
    public function index()
    {
        return view('expenses/index');
    }
    
    /**
     * Display form to create a new expense
     * 
     * @return string
     */
    public function new()
    {
        return view('expenses/create');
    }
    
    /**
     * Process the expense creation form
     * Records cost details
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function log()
    {
        // This is a stub method for logging an expense
        // In Phase 2, we will implement the actual database operations
        
        // Flash a success message
        session()->setFlashdata('success', 'Expense was successfully logged.');
        
        // Redirect to the expense list
        return redirect()->to('/expenses');
    }
}
