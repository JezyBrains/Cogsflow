<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // User Management
            ['name' => 'users.view', 'description' => 'View users'],
            ['name' => 'users.create', 'description' => 'Create users'],
            ['name' => 'users.edit', 'description' => 'Edit users'],
            ['name' => 'users.delete', 'description' => 'Delete users'],
            
            // Batch Management
            ['name' => 'batches.view', 'description' => 'View batches'],
            ['name' => 'batches.create', 'description' => 'Create batches'],
            ['name' => 'batches.edit', 'description' => 'Edit batches'],
            ['name' => 'batches.delete', 'description' => 'Delete batches'],
            ['name' => 'batches.approve', 'description' => 'Approve batches'],
            
            // Dispatch Management
            ['name' => 'dispatches.view', 'description' => 'View dispatches'],
            ['name' => 'dispatches.create', 'description' => 'Create dispatches'],
            ['name' => 'dispatches.edit', 'description' => 'Edit dispatches'],
            ['name' => 'dispatches.delete', 'description' => 'Delete dispatches'],
            
            // Inventory Management
            ['name' => 'inventory.view', 'description' => 'View inventory'],
            ['name' => 'inventory.adjust', 'description' => 'Adjust inventory'],
            
            // Supplier Management
            ['name' => 'suppliers.view', 'description' => 'View suppliers'],
            ['name' => 'suppliers.create', 'description' => 'Create suppliers'],
            ['name' => 'suppliers.edit', 'description' => 'Edit suppliers'],
            ['name' => 'suppliers.delete', 'description' => 'Delete suppliers'],
            ['name' => 'suppliers.export', 'description' => 'Export suppliers'],
            
            // Purchase Orders
            ['name' => 'purchase_orders.view', 'description' => 'View purchase orders'],
            ['name' => 'purchase_orders.create', 'description' => 'Create purchase orders'],
            ['name' => 'purchase_orders.edit', 'description' => 'Edit purchase orders'],
            ['name' => 'purchase_orders.delete', 'description' => 'Delete purchase orders'],
            ['name' => 'purchase_orders.approve', 'description' => 'Approve purchase orders'],
            
            // Expenses
            ['name' => 'expenses.view', 'description' => 'View expenses'],
            ['name' => 'expenses.create', 'description' => 'Create expenses'],
            ['name' => 'expenses.edit', 'description' => 'Edit expenses'],
            ['name' => 'expenses.delete', 'description' => 'Delete expenses'],
            
            // Reports
            ['name' => 'reports.view', 'description' => 'View reports'],
            ['name' => 'reports.export', 'description' => 'Export reports'],
            
            // Settings
            ['name' => 'settings.view', 'description' => 'View settings'],
            ['name' => 'settings.edit', 'description' => 'Edit settings'],
        ];

        foreach ($permissions as $permission) {
            $permission['created_at'] = date('Y-m-d H:i:s');
            $permission['updated_at'] = date('Y-m-d H:i:s');
            
            // Check if permission exists
            $existing = $this->db->table('permissions')
                ->where('name', $permission['name'])
                ->get()
                ->getRow();

            if (!$existing) {
                $this->db->table('permissions')->insert($permission);
            }
        }
        
        echo "✓ Permissions seeded successfully\n";
    }
}
