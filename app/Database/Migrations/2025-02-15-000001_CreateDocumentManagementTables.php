<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDocumentManagementTables extends Migration
{
    public function up()
    {
        // Create document_types table
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
            'workflow_stage' => [
                'type' => 'ENUM',
                'constraint' => ['batch_approval', 'dispatch_transit', 'receiving_inspection'],
            ],
            'is_required' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'allowed_extensions' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'max_file_size_mb' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 10,
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
        $this->forge->addKey('workflow_stage');
        $this->forge->createTable('document_types');

        // Create documents table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'document_type_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'reference_type' => [
                'type' => 'ENUM',
                'constraint' => ['batch', 'dispatch', 'inspection'],
            ],
            'reference_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'original_filename' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'stored_filename' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'file_path' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
            ],
            'file_size' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'mime_type' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'uploaded_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'upload_date' => [
                'type' => 'DATETIME',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
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
        $this->forge->addKey(['reference_type', 'reference_id']);
        $this->forge->addKey('document_type_id');
        $this->forge->addKey('uploaded_by');
        $this->forge->addForeignKey('document_type_id', 'document_types', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('documents');

        // Create workflow_document_requirements table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'workflow_stage' => [
                'type' => 'ENUM',
                'constraint' => ['batch_approval', 'dispatch_transit', 'receiving_inspection'],
            ],
            'document_type_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'is_mandatory' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'minimum_count' => [
                'type' => 'INT',
                'constraint' => 11,
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
        $this->forge->addKey('workflow_stage');
        $this->forge->addKey('document_type_id');
        $this->forge->addForeignKey('document_type_id', 'document_types', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('workflow_document_requirements');

        // Insert default document types
        $this->insertDefaultDocumentTypes();
    }

    public function down()
    {
        $this->forge->dropTable('workflow_document_requirements');
        $this->forge->dropTable('documents');
        $this->forge->dropTable('document_types');
    }

    private function insertDefaultDocumentTypes()
    {
        $db = \Config\Database::connect();
        
        // Batch approval documents
        $batchDocuments = [
            [
                'name' => 'Quality Certificate',
                'description' => 'Official quality inspection certificate from supplier',
                'workflow_stage' => 'batch_approval',
                'is_required' => true,
                'allowed_extensions' => json_encode(['pdf', 'jpg', 'jpeg', 'png']),
                'max_file_size_mb' => 5,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Supplier Delivery Note',
                'description' => 'Official delivery note from supplier',
                'workflow_stage' => 'batch_approval',
                'is_required' => true,
                'allowed_extensions' => json_encode(['pdf', 'jpg', 'jpeg', 'png']),
                'max_file_size_mb' => 5,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Weight Certificate',
                'description' => 'Official weighbridge certificate',
                'workflow_stage' => 'batch_approval',
                'is_required' => true,
                'allowed_extensions' => json_encode(['pdf', 'jpg', 'jpeg', 'png']),
                'max_file_size_mb' => 5,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Dispatch transit documents
        $dispatchDocuments = [
            [
                'name' => 'Transport Permit',
                'description' => 'Official transport permit for grain movement',
                'workflow_stage' => 'dispatch_transit',
                'is_required' => true,
                'allowed_extensions' => json_encode(['pdf', 'jpg', 'jpeg', 'png']),
                'max_file_size_mb' => 5,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Vehicle Inspection Certificate',
                'description' => 'Vehicle roadworthiness certificate',
                'workflow_stage' => 'dispatch_transit',
                'is_required' => true,
                'allowed_extensions' => json_encode(['pdf', 'jpg', 'jpeg', 'png']),
                'max_file_size_mb' => 5,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Driver License',
                'description' => 'Valid driver license copy',
                'workflow_stage' => 'dispatch_transit',
                'is_required' => true,
                'allowed_extensions' => json_encode(['pdf', 'jpg', 'jpeg', 'png']),
                'max_file_size_mb' => 3,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Receiving inspection documents
        $inspectionDocuments = [
            [
                'name' => 'Delivery Receipt',
                'description' => 'Signed delivery receipt',
                'workflow_stage' => 'receiving_inspection',
                'is_required' => true,
                'allowed_extensions' => json_encode(['pdf', 'jpg', 'jpeg', 'png']),
                'max_file_size_mb' => 5,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Inspection Photos',
                'description' => 'Photos of received goods and any discrepancies',
                'workflow_stage' => 'receiving_inspection',
                'is_required' => false,
                'allowed_extensions' => json_encode(['jpg', 'jpeg', 'png']),
                'max_file_size_mb' => 10,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Discrepancy Report',
                'description' => 'Detailed report of any discrepancies found',
                'workflow_stage' => 'receiving_inspection',
                'is_required' => false,
                'allowed_extensions' => json_encode(['pdf', 'doc', 'docx']),
                'max_file_size_mb' => 5,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert all document types
        $allDocuments = array_merge($batchDocuments, $dispatchDocuments, $inspectionDocuments);
        $db->table('document_types')->insertBatch($allDocuments);

        // Insert workflow requirements
        $requirements = [];
        $documentTypes = $db->table('document_types')->get()->getResultArray();
        
        foreach ($documentTypes as $docType) {
            $requirements[] = [
                'workflow_stage' => $docType['workflow_stage'],
                'document_type_id' => $docType['id'],
                'is_mandatory' => $docType['is_required'],
                'minimum_count' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }

        $db->table('workflow_document_requirements')->insertBatch($requirements);
    }
}
