<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRolePermissionsTable extends Migration
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
            'role_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'permission_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['role_id', 'permission_id']);
        $this->forge->addForeignKey('role_id', 'roles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('permission_id', 'permissions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('role_permissions');

        // Assign permissions to default roles
        $this->assignDefaultPermissions();
    }

    private function assignDefaultPermissions()
    {
        $db = \Config\Database::connect();
        
        // Get role IDs
        $adminRole = $db->table('roles')->where('name', 'admin')->get()->getRow();
        $warehouseRole = $db->table('roles')->where('name', 'warehouse_staff')->get()->getRow();
        $standardRole = $db->table('roles')->where('name', 'standard_user')->get()->getRow();
        
        // Get all permissions
        $permissions = $db->table('permissions')->get()->getResult();
        $permissionMap = [];
        foreach ($permissions as $permission) {
            $permissionMap[$permission->name] = $permission->id;
        }

        // Admin gets all permissions
        $adminPermissions = [];
        foreach ($permissions as $permission) {
            $adminPermissions[] = [
                'role_id' => $adminRole->id,
                'permission_id' => $permission->id,
                'created_at' => date('Y-m-d H:i:s')
            ];
        }

        // Warehouse Staff permissions
        $warehousePermissionNames = [
            'dashboard.view',
            'inventory.view', 'inventory.create', 'inventory.edit',
            'batches.view', 'batches.create', 'batches.edit',
            'dispatches.view', 'dispatches.create', 'dispatches.edit',
            'purchase_orders.view', 'purchase_orders.create', 'purchase_orders.edit',
            'expenses.view', 'expenses.create', 'expenses.edit',
            'reports.view', 'reports.export'
        ];
        
        $warehousePermissions = [];
        foreach ($warehousePermissionNames as $permName) {
            if (isset($permissionMap[$permName])) {
                $warehousePermissions[] = [
                    'role_id' => $warehouseRole->id,
                    'permission_id' => $permissionMap[$permName],
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
        }

        // Standard User permissions (read-only mostly)
        $standardPermissionNames = [
            'dashboard.view',
            'inventory.view',
            'batches.view',
            'dispatches.view',
            'purchase_orders.view',
            'expenses.view',
            'reports.view'
        ];
        
        $standardPermissions = [];
        foreach ($standardPermissionNames as $permName) {
            if (isset($permissionMap[$permName])) {
                $standardPermissions[] = [
                    'role_id' => $standardRole->id,
                    'permission_id' => $permissionMap[$permName],
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
        }

        // Insert all role permissions
        if (!empty($adminPermissions)) {
            $db->table('role_permissions')->insertBatch($adminPermissions);
        }
        if (!empty($warehousePermissions)) {
            $db->table('role_permissions')->insertBatch($warehousePermissions);
        }
        if (!empty($standardPermissions)) {
            $db->table('role_permissions')->insertBatch($standardPermissions);
        }
    }

    public function down()
    {
        $this->forge->dropTable('role_permissions');
    }
}
