<?php

namespace App\Controllers;

class ReportsController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Reports & Analytics',
            'page' => 'reports'
        ];
        
        return view('reports/index', $data);
    }
    
    public function batches()
    {
        $data = [
            'title' => 'Batch Reports',
            'page' => 'reports'
        ];
        
        return view('reports/batches', $data);
    }
    
    public function inventory()
    {
        $data = [
            'title' => 'Inventory Reports',
            'page' => 'reports'
        ];
        
        return view('reports/inventory', $data);
    }
    
    public function financial()
    {
        $data = [
            'title' => 'Financial Reports',
            'page' => 'reports'
        ];
        
        return view('reports/financial', $data);
    }
    
    public function export($type = 'pdf')
    {
        // Export functionality will be implemented in later phases
        return redirect()->back()->with('message', 'Export functionality coming soon!');
    }
}
