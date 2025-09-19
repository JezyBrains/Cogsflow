<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EnhanceProcurementWorkflow extends Migration
{
    public function up()
    {
        // Add approval workflow fields to purchase_orders table
        $this->forge->addColumn('purchase_orders', [
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected', 'completed'],
                'default' => 'pending',
                'null' => false,
                'after' => 'total_amount'
            ],
            'approved_by' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'status'
            ],
            'approved_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'approved_by'
            ],
            'rejection_reason' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'approved_at'
            ],
            'delivered_quantity_mt' => [
                'type' => 'DECIMAL',
                'constraint' => '10,3',
                'default' => 0.000,
                'null' => false,
                'after' => 'rejection_reason'
            ],
            'remaining_quantity_mt' => [
                'type' => 'DECIMAL',
                'constraint' => '10,3',
                'default' => 0.000,
                'null' => false,
                'after' => 'delivered_quantity_mt'
            ]
        ]);

        // Add batch linkage and approval fields to batches table
        $this->forge->addColumn('batches', [
            'purchase_order_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'id'
            ],
            'approved_by' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'status'
            ],
            'approved_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'approved_by'
            ],
            'rejection_reason' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'approved_at'
            ]
        ]);

        // Add foreign key constraint for batch-PO linkage
        $this->forge->addForeignKey('purchase_order_id', 'purchase_orders', 'id', 'SET NULL', 'CASCADE', 'fk_batch_purchase_order');

        // Add receiving inspection fields to dispatches table
        $this->forge->addColumn('dispatches', [
            'received_by' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'status'
            ],
            'inspection_date' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'received_by'
            ],
            'actual_bags' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'after' => 'inspection_date'
            ],
            'actual_weight_kg' => [
                'type' => 'DECIMAL',
                'constraint' => '10,3',
                'null' => true,
                'after' => 'actual_bags'
            ],
            'actual_weight_mt' => [
                'type' => 'DECIMAL',
                'constraint' => '10,3',
                'null' => true,
                'after' => 'actual_weight_kg'
            ],
            'discrepancies' => [
                'type' => 'JSON',
                'null' => true,
                'after' => 'actual_weight_mt'
            ],
            'inspection_notes' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'discrepancies'
            ]
        ]);

        // Modify dispatches status enum to include new statuses
        $this->db->query("ALTER TABLE dispatches MODIFY COLUMN status ENUM('pending', 'in_transit', 'arrived', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending'");

        // Create inventory_adjustments table for batch-wise traceability
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'grain_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'adjustment_type' => [
                'type' => 'ENUM',
                'constraint' => ['Stock In', 'Stock Out', 'Stock Transfer', 'Stock Correction', 'Damage/Loss', 'Batch Delivery'],
                'null' => false
            ],
            'quantity' => [
                'type' => 'DECIMAL',
                'constraint' => '10,3',
                'null' => false
            ],
            'reference' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Reference to batch_id, dispatch_id, etc.'
            ],
            'batch_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true
            ],
            'dispatch_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true
            ],
            'reason' => [
                'type' => 'TEXT',
                'null' => false
            ],
            'adjusted_by' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false
            ],
            'adjustment_date' => [
                'type' => 'DATETIME',
                'null' => false
            ],
            'previous_stock' => [
                'type' => 'DECIMAL',
                'constraint' => '10,3',
                'null' => false,
                'default' => 0.000
            ],
            'new_stock' => [
                'type' => 'DECIMAL',
                'constraint' => '10,3',
                'null' => false,
                'default' => 0.000
            ],
            'discrepancy_data' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Store discrepancy details if any'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey(['grain_type', 'adjustment_date']);
        $this->forge->addKey('batch_id');
        $this->forge->addKey('dispatch_id');
        $this->forge->addKey('adjustment_type');
        $this->forge->createTable('inventory_adjustments');

        // Add foreign key constraints for inventory_adjustments
        $this->forge->addForeignKey('batch_id', 'batches', 'id', 'SET NULL', 'CASCADE', 'fk_inventory_adj_batch');
        $this->forge->addForeignKey('dispatch_id', 'dispatches', 'id', 'SET NULL', 'CASCADE', 'fk_inventory_adj_dispatch');

        // Update inventory table to improve grain management
        $this->forge->addColumn('inventory', [
            'last_updated_by' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'updated_at'
            ],
            'last_batch_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'last_updated_by',
                'comment' => 'Reference to last batch that updated this inventory'
            ]
        ]);

        // Add foreign key for last_batch_id
        $this->forge->addForeignKey('last_batch_id', 'batches', 'id', 'SET NULL', 'CASCADE', 'fk_inventory_last_batch');

        // Add indexes for performance
        $this->forge->addKey('status', false, false, 'idx_po_status');
        $this->forge->addKey('approved_by', false, false, 'idx_po_approved_by');
        $this->forge->addKey(['status', 'approved_by'], false, false, 'idx_po_status_approver');

        $this->forge->addKey('purchase_order_id', false, false, 'idx_batch_po');
        $this->forge->addKey('approved_by', false, false, 'idx_batch_approved_by');
        $this->forge->addKey(['status', 'approved_by'], false, false, 'idx_batch_status_approver');

        $this->forge->addKey('received_by', false, false, 'idx_dispatch_received_by');
        $this->forge->addKey('inspection_date', false, false, 'idx_dispatch_inspection_date');
        $this->forge->addKey(['status', 'received_by'], false, false, 'idx_dispatch_status_receiver');

        // Initialize remaining_quantity_mt for existing POs
        $this->db->query("UPDATE purchase_orders SET remaining_quantity_mt = quantity_mt WHERE remaining_quantity_mt = 0");

        // Set default status for existing records
        $this->db->query("UPDATE purchase_orders SET status = 'pending' WHERE status IS NULL");
        $this->db->query("UPDATE batches SET status = 'pending' WHERE status IS NULL OR status = ''");
    }

    public function down()
    {
        // Drop foreign key constraints first
        if ($this->db->DBDriver === 'MySQLi') {
            $this->forge->dropForeignKey('batches', 'fk_batch_purchase_order');
            $this->forge->dropForeignKey('inventory_adjustments', 'fk_inventory_adj_batch');
            $this->forge->dropForeignKey('inventory_adjustments', 'fk_inventory_adj_dispatch');
            $this->forge->dropForeignKey('inventory', 'fk_inventory_last_batch');
        }

        // Drop added columns from purchase_orders
        $this->forge->dropColumn('purchase_orders', [
            'status',
            'approved_by', 
            'approved_at',
            'rejection_reason',
            'delivered_quantity_mt',
            'remaining_quantity_mt'
        ]);

        // Drop added columns from batches
        $this->forge->dropColumn('batches', [
            'purchase_order_id',
            'approved_by',
            'approved_at', 
            'rejection_reason'
        ]);

        // Drop added columns from dispatches
        $this->forge->dropColumn('dispatches', [
            'received_by',
            'inspection_date',
            'actual_bags',
            'actual_weight_kg',
            'actual_weight_mt',
            'discrepancies',
            'inspection_notes'
        ]);

        // Revert dispatches status enum
        $this->db->query("ALTER TABLE dispatches MODIFY COLUMN status ENUM('pending', 'in_transit', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending'");

        // Drop inventory_adjustments table
        $this->forge->dropTable('inventory_adjustments');

        // Drop added columns from inventory
        $this->forge->dropColumn('inventory', [
            'last_updated_by',
            'last_batch_id'
        ]);
    }
}
