<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePermissionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'resource' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'action' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('name');
        $this->forge->addKey(['resource', 'action']);
        $this->forge->createTable('permissions');

        // Insert default permissions
        $permissions = [
            // Dashboard permissions
            ['name' => 'dashboard.view', 'resource' => 'dashboard', 'action' => 'view', 'description' => 'View dashboard'],
            
            // User management permissions
            ['name' => 'users.view', 'resource' => 'users', 'action' => 'view', 'description' => 'View users list'],
            ['name' => 'users.create', 'resource' => 'users', 'action' => 'create', 'description' => 'Create new users'],
            ['name' => 'users.edit', 'resource' => 'users', 'action' => 'edit', 'description' => 'Edit existing users'],
            ['name' => 'users.delete', 'resource' => 'users', 'action' => 'delete', 'description' => 'Delete users'],
            
            // Role management permissions
            ['name' => 'roles.view', 'resource' => 'roles', 'action' => 'view', 'description' => 'View roles list'],
            ['name' => 'roles.create', 'resource' => 'roles', 'action' => 'create', 'description' => 'Create new roles'],
            ['name' => 'roles.edit', 'resource' => 'roles', 'action' => 'edit', 'description' => 'Edit existing roles'],
            ['name' => 'roles.delete', 'resource' => 'roles', 'action' => 'delete', 'description' => 'Delete roles'],
            ['name' => 'roles.assign', 'resource' => 'roles', 'action' => 'assign', 'description' => 'Assign roles to users'],
            
            // Inventory permissions
            ['name' => 'inventory.view', 'resource' => 'inventory', 'action' => 'view', 'description' => 'View inventory items'],
            ['name' => 'inventory.create', 'resource' => 'inventory', 'action' => 'create', 'description' => 'Create inventory items'],
            ['name' => 'inventory.edit', 'resource' => 'inventory', 'action' => 'edit', 'description' => 'Edit inventory items'],
            ['name' => 'inventory.delete', 'resource' => 'inventory', 'action' => 'delete', 'description' => 'Delete inventory items'],
            
            // Batch management permissions
            ['name' => 'batches.view', 'resource' => 'batches', 'action' => 'view', 'description' => 'View batch information'],
            ['name' => 'batches.create', 'resource' => 'batches', 'action' => 'create', 'description' => 'Create new batches'],
            ['name' => 'batches.edit', 'resource' => 'batches', 'action' => 'edit', 'description' => 'Edit batch information'],
            ['name' => 'batches.delete', 'resource' => 'batches', 'action' => 'delete', 'description' => 'Delete batches'],
            
            // Dispatch permissions
            ['name' => 'dispatches.view', 'resource' => 'dispatches', 'action' => 'view', 'description' => 'View dispatches'],
            ['name' => 'dispatches.create', 'resource' => 'dispatches', 'action' => 'create', 'description' => 'Create new dispatches'],
            ['name' => 'dispatches.edit', 'resource' => 'dispatches', 'action' => 'edit', 'description' => 'Edit dispatches'],
            ['name' => 'dispatches.delete', 'resource' => 'dispatches', 'action' => 'delete', 'description' => 'Delete dispatches'],
            
            // Purchase orders permissions
            ['name' => 'purchase_orders.view', 'resource' => 'purchase_orders', 'action' => 'view', 'description' => 'View purchase orders'],
            ['name' => 'purchase_orders.create', 'resource' => 'purchase_orders', 'action' => 'create', 'description' => 'Create purchase orders'],
            ['name' => 'purchase_orders.edit', 'resource' => 'purchase_orders', 'action' => 'edit', 'description' => 'Edit purchase orders'],
            ['name' => 'purchase_orders.delete', 'resource' => 'purchase_orders', 'action' => 'delete', 'description' => 'Delete purchase orders'],
            
            // Expense tracking permissions
            ['name' => 'expenses.view', 'resource' => 'expenses', 'action' => 'view', 'description' => 'View expenses'],
            ['name' => 'expenses.create', 'resource' => 'expenses', 'action' => 'create', 'description' => 'Create expenses'],
            ['name' => 'expenses.edit', 'resource' => 'expenses', 'action' => 'edit', 'description' => 'Edit expenses'],
            ['name' => 'expenses.delete', 'resource' => 'expenses', 'action' => 'delete', 'description' => 'Delete expenses'],
            
            // Reports permissions
            ['name' => 'reports.view', 'resource' => 'reports', 'action' => 'view', 'description' => 'View reports'],
            ['name' => 'reports.export', 'resource' => 'reports', 'action' => 'export', 'description' => 'Export reports'],
            
            // Settings permissions
            ['name' => 'settings.view', 'resource' => 'settings', 'action' => 'view', 'description' => 'View system settings'],
            ['name' => 'settings.edit', 'resource' => 'settings', 'action' => 'edit', 'description' => 'Edit system settings'],
            ['name' => 'settings.admin', 'resource' => 'settings', 'action' => 'admin', 'description' => 'Access admin utilities'],
        ];

        foreach ($permissions as &$permission) {
            $permission['created_at'] = date('Y-m-d H:i:s');
        }

        $this->db->table('permissions')->insertBatch($permissions);
    }

    public function down()
    {
        $this->forge->dropTable('permissions');
    }
}
