<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DefaultUserSeeder extends Seeder
{
    public function run()
    {
        // Create default admin user
        $userData = [
            'username' => 'admin',
            'email' => 'admin@nipoagro.com',
            'password_hash' => password_hash('NipoAgro2025!', PASSWORD_DEFAULT),
            'first_name' => 'System',
            'last_name' => 'Administrator',
            'role' => 'admin',
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Check if admin user already exists
        $existingUser = $this->db->table('users')
            ->where('username', 'admin')
            ->orWhere('email', 'admin@nipoagro.com')
            ->get()
            ->getRow();

        if (!$existingUser) {
            $this->db->table('users')->insert($userData);
            echo "Default admin user created successfully.\n";
        } else {
            echo "Default admin user already exists.\n";
        }

        // Create sample warehouse staff user
        $warehouseUserData = [
            'username' => 'warehouse',
            'email' => 'warehouse@nipoagro.com',
            'password_hash' => password_hash('warehouse123', PASSWORD_DEFAULT),
            'first_name' => 'Warehouse',
            'last_name' => 'Staff',
            'role' => 'warehouse_staff',
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $existingWarehouseUser = $this->db->table('users')
            ->where('username', 'warehouse')
            ->get()
            ->getRow();

        if (!$existingWarehouseUser) {
            $this->db->table('users')->insert($warehouseUserData);
            echo "Default warehouse user created successfully.\n";
        } else {
            echo "Default warehouse user already exists.\n";
        }
    }
}
