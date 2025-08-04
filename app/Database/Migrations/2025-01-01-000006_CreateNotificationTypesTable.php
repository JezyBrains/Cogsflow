<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotificationTypesTable extends Migration
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
                'constraint' => 50,
            ],
            'display_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'icon' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'bx-bell',
            ],
            'color' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'primary',
            ],
            'default_enabled' => [
                'type'    => 'BOOLEAN',
                'default' => true,
            ],
            'role_specific' => [
                'type' => 'JSON',
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
        $this->forge->createTable('notification_types');

        // Insert default notification types
        $this->db->table('notification_types')->insertBatch([
            [
                'name' => 'batch_arrival',
                'display_name' => 'Batch Arrivals',
                'description' => 'Notifications when new batches arrive or are updated',
                'icon' => 'bx-package',
                'color' => 'success',
                'default_enabled' => true,
                'role_specific' => json_encode(['admin', 'warehouse_staff']),
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'dispatch_status',
                'display_name' => 'Dispatch Updates',
                'description' => 'Notifications for dispatch status changes and deliveries',
                'icon' => 'bx-truck',
                'color' => 'info',
                'default_enabled' => true,
                'role_specific' => json_encode(['admin', 'warehouse_staff']),
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'expense_alert',
                'display_name' => 'Expense Alerts',
                'description' => 'Notifications for expense logs and budget alerts',
                'icon' => 'bx-money',
                'color' => 'warning',
                'default_enabled' => true,
                'role_specific' => json_encode(['admin']),
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'inventory_threshold',
                'display_name' => 'Inventory Alerts',
                'description' => 'Low stock and overstock notifications',
                'icon' => 'bx-store',
                'color' => 'danger',
                'default_enabled' => true,
                'role_specific' => json_encode(['admin', 'warehouse_staff']),
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'system_error',
                'display_name' => 'System Errors',
                'description' => 'Critical system errors and warnings',
                'icon' => 'bx-error',
                'color' => 'danger',
                'default_enabled' => true,
                'role_specific' => json_encode(['admin']),
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'user_management',
                'display_name' => 'User Management',
                'description' => 'User account and role changes',
                'icon' => 'bx-user',
                'color' => 'primary',
                'default_enabled' => true,
                'role_specific' => json_encode(['admin']),
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'system_maintenance',
                'display_name' => 'System Maintenance',
                'description' => 'Scheduled maintenance and system updates',
                'icon' => 'bx-wrench',
                'color' => 'secondary',
                'default_enabled' => true,
                'role_specific' => json_encode(['admin']),
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('notification_types');
    }
}
