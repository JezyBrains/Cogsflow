<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserRolesTable extends Migration
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
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'role_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'assigned_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'assigned_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
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
        $this->forge->addKey(['user_id', 'role_id']);
        $this->forge->addKey('user_id');
        $this->forge->addKey('role_id');
        $this->forge->addForeignKey('role_id', 'roles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_roles');

        // Assign admin role to user ID 1 if it exists
        $this->assignDefaultUserRole();
    }

    private function assignDefaultUserRole()
    {
        $db = \Config\Database::connect();
        
        // Check if users table exists and has at least one user
        if ($db->tableExists('users')) {
            $firstUser = $db->table('users')->orderBy('id', 'ASC')->limit(1)->get()->getRow();
            
            if ($firstUser) {
                $adminRole = $db->table('roles')->where('name', 'admin')->get()->getRow();
                
                if ($adminRole) {
                    $db->table('user_roles')->insert([
                        'user_id' => $firstUser->id,
                        'role_id' => $adminRole->id,
                        'assigned_by' => null, // System assigned
                        'assigned_at' => date('Y-m-d H:i:s'),
                        'is_active' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }
    }

    public function down()
    {
        $this->forge->dropTable('user_roles');
    }
}
