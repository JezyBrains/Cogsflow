<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    /**
     * Display the dashboard homepage
     * 
     * @return string
     */
    public function index()
    {
        return view('dashboard/index');
    }
}
