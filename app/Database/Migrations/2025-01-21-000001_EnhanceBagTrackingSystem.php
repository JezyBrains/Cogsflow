<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EnhanceBagTrackingSystem extends Migration
{
    public function up()
    {
        // Add new columns to batch_bags table for enhanced tracking
        $fields = [
            'bag_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'bag_number'
            ],
            'qr_code' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'bag_id'
            ],
            'quality_grade' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'after' => 'moisture_percentage'
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'quality_grade'
            ],
            'loading_date' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'notes'
            ],
            'loaded_by' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'loading_date'
            ]
        ];

        $this->forge->addColumn('batch_bags', $fields);

        // Create bag_inspections table for receiving inspection tracking
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
            'batch_bag_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'bag_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'expected_weight_kg' => [
                'type' => 'DECIMAL',
                'constraint' => '6,2',
            ],
            'actual_weight_kg' => [
                'type' => 'DECIMAL',
                'constraint' => '6,2',
            ],
            'expected_moisture' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
            ],
            'actual_moisture' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
            'weight_difference' => [
                'type' => 'DECIMAL',
                'constraint' => '6,2',
            ],
            'moisture_difference' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
            'condition_status' => [
                'type' => 'ENUM',
                'constraint' => ['good', 'damaged', 'wet', 'contaminated', 'missing'],
                'default' => 'good',
            ],
            'inspection_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'inspected_by' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'inspection_date' => [
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
        $this->forge->addForeignKey('dispatch_id', 'dispatches', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('batch_bag_id', 'batch_bags', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addKey(['bag_id']);
        $this->forge->addKey(['dispatch_id', 'batch_bag_id']);
        $this->forge->createTable('bag_inspections');

        // Create bag_discrepancies table for tracking issues
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'bag_inspection_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'discrepancy_type' => [
                'type' => 'ENUM',
                'constraint' => ['weight_loss', 'weight_gain', 'moisture_increase', 'moisture_decrease', 'damage', 'contamination', 'missing_bag'],
            ],
            'severity' => [
                'type' => 'ENUM',
                'constraint' => ['minor', 'moderate', 'major', 'critical'],
                'default' => 'minor',
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'action_taken' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'resolved' => [
                'type' => 'BOOLEAN',
                'default' => false,
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
        $this->forge->addForeignKey('bag_inspection_id', 'bag_inspections', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('bag_discrepancies');
    }

    public function down()
    {
        // Drop new tables
        $this->forge->dropTable('bag_discrepancies');
        $this->forge->dropTable('bag_inspections');

        // Remove added columns from batch_bags
        $this->forge->dropColumn('batch_bags', [
            'bag_id',
            'qr_code', 
            'quality_grade',
            'notes',
            'loading_date',
            'loaded_by'
        ]);
    }
}
