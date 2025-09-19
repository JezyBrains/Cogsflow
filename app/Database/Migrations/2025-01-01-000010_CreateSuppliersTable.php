<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSuppliersTable extends Migration
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
            'supplier_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'business_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'contact_person' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tin_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'supplier_type' => [
                'type'       => 'ENUM',
                'constraint' => ['Grain Vendor', 'Transporter', 'Service Provider', 'Equipment Supplier', 'Other'],
                'default'    => 'Grain Vendor',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive', 'archived'],
                'default'    => 'active',
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
        $this->forge->addUniqueKey('supplier_name');
        $this->forge->addKey('supplier_type');
        $this->forge->addKey('status');
        $this->forge->addKey(['created_at', 'updated_at']);
        $this->forge->createTable('suppliers');

        // Insert default suppliers
        $data = [
            [
                'supplier_name' => 'Mwangi Grain Suppliers',
                'business_name' => 'Mwangi Grain Suppliers Ltd',
                'contact_person' => 'John Mwangi',
                'phone' => '+254712345678',
                'email' => 'info@mwangigrain.co.ke',
                'address' => 'Nakuru, Kenya',
                'tin_number' => 'P051234567A',
                'supplier_type' => 'Grain Vendor',
                'notes' => 'Reliable maize supplier from Nakuru region',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'supplier_name' => 'Rift Valley Transport',
                'business_name' => 'Rift Valley Transport Services',
                'contact_person' => 'Peter Kimani',
                'phone' => '+254723456789',
                'email' => 'dispatch@rvtransport.co.ke',
                'address' => 'Eldoret, Kenya',
                'tin_number' => 'P051234568B',
                'supplier_type' => 'Transporter',
                'notes' => 'Specialized in grain transportation across East Africa',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'supplier_name' => 'Kiambu Farmers Cooperative',
                'business_name' => 'Kiambu Farmers Cooperative Society',
                'contact_person' => 'Mary Wanjiku',
                'phone' => '+254734567890',
                'email' => 'secretary@kiambufarmers.co.ke',
                'address' => 'Kiambu, Kenya',
                'tin_number' => 'P051234569C',
                'supplier_type' => 'Grain Vendor',
                'notes' => 'Cooperative with over 500 smallholder farmers',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('suppliers')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('suppliers');
    }
}
