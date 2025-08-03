<?php

namespace App\Controllers;

class TestController extends BaseController
{
    public function index()
    {
        return 'Test Controller Works!';
    }
    
    public function settings()
    {
        try {
            // Test database connection
            $db = \Config\Database::connect();
            $query = $db->query('SELECT 1 as test');
            $result = $query->getRow();
            
            return 'Database connection works! Test result: ' . $result->test;
        } catch (\Exception $e) {
            return 'Database error: ' . $e->getMessage();
        }
    }
}
