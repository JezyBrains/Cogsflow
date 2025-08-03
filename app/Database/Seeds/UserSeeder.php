<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Check if users table exists
        if (!$db->tableExists('users')) {
            // Create users table if it doesn't exist
            $forge = \Config\Database::forge();
            
            $forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'username' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                ],
                'email' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                ],
                'password' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
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
            
            $forge->addKey('id', true);
            $forge->addUniqueKey('username');
            $forge->addUniqueKey('email');
            $forge->createTable('users');
        }
        
        // Check if admin user already exists
        $existingUser = $db->table('users')->where('username', 'admin')->get()->getRow();
        
        if (!$existingUser) {
            // Create admin user
            $userData = [
                'username' => 'admin',
                'email' => 'admin@cogsflow.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
            ];
            
            $userId = $db->table('users')->insert($userData);
            if (!$userId) {
                $userId = $db->insertID();
            }
            
            // Get admin role
            $adminRole = $db->table('roles')->where('name', 'admin')->get()->getRow();
            
            if ($adminRole && $userId) {
                // Assign admin role to user
                $db->table('user_roles')->insert([
                    'user_id' => $userId,
                    'role_id' => $adminRole->id,
                    'assigned_by' => null,
                    'assigned_at' => date('Y-m-d H:i:s'),
                    'is_active' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                
                echo "Admin user created successfully!\n";
                echo "Username: admin\n";
                echo "Password: admin123\n";
                echo "Email: admin@cogsflow.com\n";
            }
        } else {
            echo "Admin user already exists.\n";
        }
        
        // Create warehouse staff user
        $warehouseUser = $db->table('users')->where('username', 'warehouse')->get()->getRow();
        
        if (!$warehouseUser) {
            $warehouseData = [
                'username' => 'warehouse',
                'email' => 'warehouse@cogsflow.com',
                'password' => password_hash('warehouse123', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
            ];
            
            $warehouseUserId = $db->table('users')->insert($warehouseData);
            if (!$warehouseUserId) {
                $warehouseUserId = $db->insertID();
            }
            
            // Get warehouse staff role
            $warehouseRole = $db->table('roles')->where('name', 'warehouse_staff')->get()->getRow();
            
            if ($warehouseRole && $warehouseUserId) {
                // Assign warehouse staff role to user
                $db->table('user_roles')->insert([
                    'user_id' => $warehouseUserId,
                    'role_id' => $warehouseRole->id,
                    'assigned_by' => null,
                    'assigned_at' => date('Y-m-d H:i:s'),
                    'is_active' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                
                echo "Warehouse staff user created successfully!\n";
                echo "Username: warehouse\n";
                echo "Password: warehouse123\n";
                echo "Email: warehouse@cogsflow.com\n";
            }
        } else {
            echo "Warehouse staff user already exists.\n";
        }
        
        // Create standard user
        $standardUser = $db->table('users')->where('username', 'user')->get()->getRow();
        
        if (!$standardUser) {
            $standardData = [
                'username' => 'user',
                'email' => 'user@cogsflow.com',
                'password' => password_hash('user123', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
            ];
            
            $standardUserId = $db->table('users')->insert($standardData);
            if (!$standardUserId) {
                $standardUserId = $db->insertID();
            }
            
            // Get standard user role
            $standardRole = $db->table('roles')->where('name', 'standard_user')->get()->getRow();
            
            if ($standardRole && $standardUserId) {
                // Assign standard user role
                $db->table('user_roles')->insert([
                    'user_id' => $standardUserId,
                    'role_id' => $standardRole->id,
                    'assigned_by' => null,
                    'assigned_at' => date('Y-m-d H:i:s'),
                    'is_active' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                
                echo "Standard user created successfully!\n";
                echo "Username: user\n";
                echo "Password: user123\n";
                echo "Email: user@cogsflow.com\n";
            }
        } else {
            echo "Standard user already exists.\n";
        }
    }
}
