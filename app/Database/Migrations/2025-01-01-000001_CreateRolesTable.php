<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRolesTable extends Migration
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
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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
        $this->forge->createTable('roles');

        // Insert default roles
        $data = [
            [
                'name'         => 'admin',
                'display_name' => 'Administrator',
                'description'  => 'Full system access with all administrative privileges',
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'name'         => 'warehouse_staff',
                'display_name' => 'Warehouse Staff',
                'description'  => 'Inventory management, dispatches, and batch operations',
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'name'         => 'standard_user',
                'display_name' => 'Standard User',
                'description'  => 'Limited read access and basic operations',
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('roles')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('roles');
    }
}
