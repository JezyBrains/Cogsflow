<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBatchHistoryTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'batch_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false
            ],
            'purchase_order_id' => [
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
            'action' => [
                'type' => 'ENUM',
                'constraint' => ['created', 'approved', 'rejected', 'dispatched', 'arrived', 'inspected', 'delivered', 'cancelled'],
                'null' => false
            ],
            'performed_by' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false
            ],
            'performed_at' => [
                'type' => 'DATETIME',
                'null' => false
            ],
            'previous_status' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true
            ],
            'new_status' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true
            ],
            'details' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Additional details like discrepancies, approval reasons, etc.'
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('batch_id');
        $this->forge->addKey('purchase_order_id');
        $this->forge->addKey('dispatch_id');
        $this->forge->addKey(['batch_id', 'performed_at']);
        $this->forge->addKey(['action', 'performed_at']);
        $this->forge->addKey('performed_by');
        
        $this->forge->createTable('batch_history');

        // Add foreign key constraints
        $this->forge->addForeignKey('batch_id', 'batches', 'id', 'CASCADE', 'CASCADE', 'fk_batch_history_batch');
        $this->forge->addForeignKey('purchase_order_id', 'purchase_orders', 'id', 'SET NULL', 'CASCADE', 'fk_batch_history_po');
        $this->forge->addForeignKey('dispatch_id', 'dispatches', 'id', 'SET NULL', 'CASCADE', 'fk_batch_history_dispatch');
    }

    public function down()
    {
        // Drop foreign key constraints first
        if ($this->db->DBDriver === 'MySQLi') {
            $this->forge->dropForeignKey('batch_history', 'fk_batch_history_batch');
            $this->forge->dropForeignKey('batch_history', 'fk_batch_history_po');
            $this->forge->dropForeignKey('batch_history', 'fk_batch_history_dispatch');
        }
        
        $this->forge->dropTable('batch_history');
    }
}
