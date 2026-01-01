<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EnhanceExpensesModule extends Migration
{
    public function up()
    {
        // Drop existing expenses table if exists
        $this->forge->dropTable('expenses', true);
        
        // Create expense_categories table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
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
        $this->forge->addKey('name');
        $this->forge->createTable('expense_categories');
        
        // Create enhanced expenses table
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
            'expense_date' => [
                'type' => 'DATE',
            ],
            'category_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'payment_method' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'vendor_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'receipt_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'reference_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'batch, dispatch, purchase_order, general',
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
            'recorded_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'approved_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'approval_status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default' => 'pending',
            ],
            'approval_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'approval_notes' => [
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
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('expense_number');
        $this->forge->addKey('expense_date');
        $this->forge->addKey('category_id');
        $this->forge->addKey('recorded_by');
        $this->forge->addKey('approval_status');
        $this->forge->addForeignKey('category_id', 'expense_categories', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('recorded_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('approved_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('expenses');
        
        // Create expense_audit_log table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'expense_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'action' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'comment' => 'created, updated, deleted, approved, rejected',
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'old_values' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'new_values' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('expense_id');
        $this->forge->addKey('user_id');
        $this->forge->addKey('action');
        $this->forge->addForeignKey('expense_id', 'expenses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('expense_audit_log');
        
        // Insert default expense categories
        $categories = [
            ['name' => 'Transportation', 'description' => 'Vehicle fuel, maintenance, and transport costs', 'is_active' => 1],
            ['name' => 'Storage', 'description' => 'Warehouse rent, storage fees, and facility costs', 'is_active' => 1],
            ['name' => 'Labor', 'description' => 'Wages, salaries, and labor costs', 'is_active' => 1],
            ['name' => 'Equipment', 'description' => 'Machinery, tools, and equipment purchases', 'is_active' => 1],
            ['name' => 'Maintenance', 'description' => 'Repairs and maintenance of equipment and facilities', 'is_active' => 1],
            ['name' => 'Utilities', 'description' => 'Electricity, water, and other utility bills', 'is_active' => 1],
            ['name' => 'Insurance', 'description' => 'Insurance premiums and coverage costs', 'is_active' => 1],
            ['name' => 'Administrative', 'description' => 'Office supplies, stationery, and administrative expenses', 'is_active' => 1],
            ['name' => 'Packaging', 'description' => 'Bags, containers, and packaging materials', 'is_active' => 1],
            ['name' => 'Quality Control', 'description' => 'Testing, inspection, and quality assurance costs', 'is_active' => 1],
            ['name' => 'Marketing', 'description' => 'Advertising, promotions, and marketing expenses', 'is_active' => 1],
            ['name' => 'Other', 'description' => 'Miscellaneous expenses not covered by other categories', 'is_active' => 1],
        ];
        
        foreach ($categories as $category) {
            $category['created_at'] = date('Y-m-d H:i:s');
            $category['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('expense_categories')->insert($category);
        }
    }

    public function down()
    {
        $this->forge->dropTable('expense_audit_log', true);
        $this->forge->dropTable('expenses', true);
        $this->forge->dropTable('expense_categories', true);
    }
}
