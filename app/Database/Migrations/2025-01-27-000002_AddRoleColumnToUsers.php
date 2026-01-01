<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRoleColumnToUsers extends Migration
{
    public function up()
    {
        // Add role column to users table for simple role checking
        $fields = [
            'role' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'default' => 'standard_user',
                'after' => 'email'
            ]
        ];
        
        $this->forge->addColumn('users', $fields);
        
        // Sync role column with user_roles table for existing users
        $this->syncUserRoles();
    }
    
    private function syncUserRoles()
    {
        $db = \Config\Database::connect();
        
        // Get all users with their roles from user_roles table
        $query = $db->query("
            SELECT u.id, r.name as role_name
            FROM users u
            LEFT JOIN user_roles ur ON ur.user_id = u.id AND ur.is_active = 1
            LEFT JOIN roles r ON r.id = ur.role_id
            WHERE u.id IS NOT NULL
        ");
        
        $users = $query->getResult();
        
        foreach ($users as $user) {
            if ($user->role_name) {
                $db->table('users')
                    ->where('id', $user->id)
                    ->update(['role' => $user->role_name]);
            }
        }
        
        log_message('info', 'Synced role column for ' . count($users) . ' users');
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'role');
    }
}
