<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGrainManagementTables extends Migration
{
    public function up()
    {
        // Suppliers table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'contact_person' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default' => 'active',
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
        $this->forge->createTable('suppliers');

        // Batches table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'batch_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
            ],
            'supplier_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'grain_type' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'total_bags' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'total_weight_kg' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'total_weight_mt' => [
                'type' => 'DECIMAL',
                'constraint' => '10,3',
            ],
            'average_moisture' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
            ],
            'quality_grade' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'dispatched', 'delivered', 'rejected'],
                'default' => 'pending',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'received_date' => [
                'type' => 'DATETIME',
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
        $this->forge->addForeignKey('supplier_id', 'suppliers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('batches');

        // Batch bags table (for individual bag tracking)
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'batch_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'bag_number' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'weight_kg' => [
                'type' => 'DECIMAL',
                'constraint' => '6,2',
            ],
            'moisture_percentage' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
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
        $this->forge->addForeignKey('batch_id', 'batches', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('batch_bags');

        // Dispatches table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'dispatch_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
            ],
            'batch_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'vehicle_number' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'trailer_number' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'driver_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'driver_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'dispatcher_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'destination' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'estimated_arrival' => [
                'type' => 'DATETIME',
            ],
            'actual_departure' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'actual_arrival' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'in_transit', 'arrived', 'delivered', 'cancelled'],
                'default' => 'pending',
            ],
            'notes' => [
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
        $this->forge->addForeignKey('batch_id', 'batches', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('dispatches');

        // Vehicle changes table (for tracking vehicle changes during dispatch)
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'dispatch_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'old_vehicle_number' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'new_vehicle_number' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'old_driver_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'new_driver_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'reason' => [
                'type' => 'TEXT',
            ],
            'changed_by' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'changed_at' => [
                'type' => 'DATETIME',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('dispatch_id', 'dispatches', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('vehicle_changes');

        // Warehouse receipts table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'dispatch_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'receipt_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
            ],
            'received_weight_kg' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'received_bags' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'weight_variance_kg' => [
                'type' => 'DECIMAL',
                'constraint' => '8,2',
            ],
            'bag_variance' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'quality_check_passed' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'quality_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'received_by' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'received_at' => [
                'type' => 'DATETIME',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending_inspection', 'approved', 'rejected', 'partial_approved'],
                'default' => 'pending_inspection',
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
        $this->forge->addForeignKey('dispatch_id', 'dispatches', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('warehouse_receipts');

        // Delivery notes table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'warehouse_receipt_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'delivery_note_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
            ],
            'customer_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'customer_signature' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'delivery_date' => [
                'type' => 'DATETIME',
            ],
            'delivered_by' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'notes' => [
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
        $this->forge->addForeignKey('warehouse_receipt_id', 'warehouse_receipts', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('delivery_notes');

        // Purchase orders table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'po_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
            ],
            'customer_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'customer_contact' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'grain_type' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'quantity_mt' => [
                'type' => 'DECIMAL',
                'constraint' => '10,3',
            ],
            'unit_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'total_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
            ],
            'delivery_date' => [
                'type' => 'DATE',
            ],
            'delivery_address' => [
                'type' => 'TEXT',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'fulfilled', 'cancelled'],
                'default' => 'pending',
            ],
            'notes' => [
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
        $this->forge->createTable('purchase_orders');

        // Expenses table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'expense_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
            ],
            'category' => [
                'type' => 'ENUM',
                'constraint' => ['fuel', 'repairs', 'handling', 'storage', 'transportation', 'other'],
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'dispatch_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'batch_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'expense_date' => [
                'type' => 'DATE',
            ],
            'logged_by' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'receipt_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
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
        $this->forge->addForeignKey('dispatch_id', 'dispatches', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('batch_id', 'batches', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('expenses');

        // Inventory table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'item_code' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
            ],
            'grain_type' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'current_stock_mt' => [
                'type' => 'DECIMAL',
                'constraint' => '10,3',
                'default' => 0,
            ],
            'minimum_level_mt' => [
                'type' => 'DECIMAL',
                'constraint' => '10,3',
                'default' => 0,
            ],
            'unit_cost' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0,
            ],
            'location' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default' => 'active',
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
        $this->forge->createTable('inventory');

        // Inventory movements table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'inventory_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'movement_type' => [
                'type' => 'ENUM',
                'constraint' => ['in', 'out', 'adjustment'],
            ],
            'quantity_mt' => [
                'type' => 'DECIMAL',
                'constraint' => '10,3',
            ],
            'reference_type' => [
                'type' => 'ENUM',
                'constraint' => ['batch', 'purchase_order', 'adjustment', 'other'],
            ],
            'reference_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'moved_by' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'movement_date' => [
                'type' => 'DATETIME',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('inventory_id', 'inventory', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('inventory_movements');
    }

    public function down()
    {
        $this->forge->dropTable('inventory_movements');
        $this->forge->dropTable('inventory');
        $this->forge->dropTable('expenses');
        $this->forge->dropTable('purchase_orders');
        $this->forge->dropTable('delivery_notes');
        $this->forge->dropTable('warehouse_receipts');
        $this->forge->dropTable('vehicle_changes');
        $this->forge->dropTable('dispatches');
        $this->forge->dropTable('batch_bags');
        $this->forge->dropTable('batches');
        $this->forge->dropTable('suppliers');
    }
}
