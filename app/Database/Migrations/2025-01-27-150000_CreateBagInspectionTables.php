<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBagInspectionTables extends Migration
{
    public function up()
    {
        // ============================================
        // Table: bag_inspections
        // Purpose: Track individual bag inspection details
        // ============================================
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
            'batch_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'bag_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'comment' => 'Unique bag identifier (e.g., BTH-2024-001-B046)',
            ],
            'bag_number' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Sequential bag number within batch',
            ],
            
            // Expected values (from batch creation)
            'expected_weight_kg' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'expected_moisture' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
                'comment' => 'Expected moisture percentage',
            ],
            
            // Actual values (from inspection)
            'actual_weight_kg' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'actual_moisture' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
                'comment' => 'Actual moisture percentage',
            ],
            
            // Calculated discrepancies
            'weight_variance_kg' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'comment' => 'Difference: actual - expected (kg)',
            ],
            'weight_variance_percent' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
                'comment' => 'Percentage variance',
            ],
            'moisture_variance' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
                'comment' => 'Difference: actual - expected (%)',
            ],
            
            // Status and condition
            'condition_status' => [
                'type' => 'ENUM',
                'constraint' => ['good', 'damaged', 'wet', 'contaminated', 'missing'],
                'default' => 'good',
            ],
            'has_discrepancy' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'comment' => 'True if variance exceeds tolerance',
            ],
            'inspection_status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'inspected', 'skipped'],
                'default' => 'pending',
            ],
            
            // Documentation
            'inspection_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'photo_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Path to photo of damaged bag',
            ],
            'voice_note_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Path to voice note recording',
            ],
            
            // Audit trail
            'inspected_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'inspected_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'inspection_duration_seconds' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Time taken to inspect this bag',
            ],
            
            // QR/Scanning metadata
            'qr_scanned' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'comment' => 'True if bag was scanned via QR code',
            ],
            'scan_timestamp' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'device_info' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Device used for scanning (mobile/desktop)',
            ],
            
            // Timestamps
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
        $this->forge->addKey('dispatch_id');
        $this->forge->addKey('batch_id');
        $this->forge->addKey('bag_id');
        $this->forge->addKey('inspection_status');
        $this->forge->addKey('condition_status');
        
        $this->forge->addForeignKey('dispatch_id', 'dispatches', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('batch_id', 'batches', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('inspected_by', 'users', 'id', 'SET NULL', 'CASCADE');
        
        $this->forge->createTable('bag_inspections');
        
        // ============================================
        // Table: inspection_sessions
        // Purpose: Track inspection sessions for progress and analytics
        // ============================================
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
            'batch_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'inspector_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            
            // Session timing
            'started_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'completed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'paused_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'total_duration_seconds' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            
            // Progress tracking
            'total_bags_expected' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'total_bags_inspected' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'total_bags_skipped' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'total_discrepancies' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            
            // Weight summary
            'expected_total_weight_kg' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'actual_total_weight_kg' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'weight_variance_percent' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
            
            // Session status
            'session_status' => [
                'type' => 'ENUM',
                'constraint' => ['in_progress', 'completed', 'paused', 'cancelled'],
                'default' => 'in_progress',
            ],
            
            // Device and mode
            'device_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'mobile, tablet, desktop',
            ],
            'inspection_mode' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'qr_scan, manual_entry, bulk',
            ],
            
            // Session notes
            'session_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            
            // Timestamps
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
        $this->forge->addKey('dispatch_id');
        $this->forge->addKey('batch_id');
        $this->forge->addKey('inspector_id');
        $this->forge->addKey('session_status');
        
        $this->forge->addForeignKey('dispatch_id', 'dispatches', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('batch_id', 'batches', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('inspector_id', 'users', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('inspection_sessions');
    }

    public function down()
    {
        // Drop tables in reverse order (respect foreign keys)
        $this->forge->dropTable('bag_inspections', true);
        $this->forge->dropTable('inspection_sessions', true);
    }
}
