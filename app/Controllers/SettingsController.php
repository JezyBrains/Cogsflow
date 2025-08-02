<?php

namespace App\Controllers;

class SettingsController extends BaseController
{
    /**
     * Display system settings page
     * 
     * @return string
     */
    public function index()
    {
        return view('settings/index');
    }
    
    /**
     * Process the settings update
     * Adjusts system settings
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update()
    {
        // This is a stub method for updating settings
        // In Phase 2, we will implement the actual database operations
        
        // Flash a success message
        session()->setFlashdata('success', 'Settings were successfully updated.');
        
        // Redirect back to the settings page
        return redirect()->to('/settings');
    }
}
