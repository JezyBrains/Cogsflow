<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ReportsSeeder extends Seeder
{
    public function run()
    {
        $reports = [
            // Inventory Reports
            [
                'name' => 'Stock Summary Report',
                'description' => 'Current stock levels and inventory summary',
                'category' => 'inventory',
                'type' => 'stock_summary',
                'allowed_roles' => json_encode(['admin', 'warehouse_staff']),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Batch Analytics Report',
                'description' => 'Detailed batch tracking and analytics',
                'category' => 'inventory',
                'type' => 'batch_analytics',
                'allowed_roles' => json_encode(['admin', 'warehouse_staff']),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            
            // Financial Reports
            [
                'name' => 'Expense Analysis Report',
                'description' => 'Expense tracking and analysis',
                'category' => 'financial',
                'type' => 'expense_analysis',
                'allowed_roles' => json_encode(['admin']),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            
            // Operations Reports
            [
                'name' => 'Dispatch Performance Report',
                'description' => 'Dispatch tracking and performance metrics',
                'category' => 'operations',
                'type' => 'dispatch_performance',
                'allowed_roles' => json_encode(['admin', 'warehouse_staff']),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            
            // Supplier Reports
            [
                'name' => 'Supplier Performance Report',
                'description' => 'Supplier statistics and performance',
                'category' => 'suppliers',
                'type' => 'supplier_performance',
                'allowed_roles' => json_encode(['admin']),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Purchase Order Summary',
                'description' => 'Purchase order tracking and fulfillment',
                'category' => 'suppliers',
                'type' => 'po_summary',
                'allowed_roles' => json_encode(['admin', 'warehouse_staff']),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];

        foreach ($reports as $report) {
            // Check if report exists
            try {
                $query = $this->db->table('reports')
                    ->where('type', $report['type'])
                    ->get();
                
                if ($query === false) {
                    // Table doesn't exist yet, skip
                    continue;
                }
                
                $existing = $query->getRow();

                if (!$existing) {
                    $this->db->table('reports')->insert($report);
                }
            } catch (\Exception $e) {
                // Skip if there's an error
                log_message('debug', 'Report seeding skipped for ' . $report['type'] . ': ' . $e->getMessage());
                continue;
            }
        }
        
        echo "✓ Reports seeded successfully\n";
    }
}
