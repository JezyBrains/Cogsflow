<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductionUserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user for production
        $userData = [
            [
                'id' => 1,
                'username' => 'admin',
                'email' => 'admin@localhost:8000',
                'password' => password_hash('NipoAgro2025!', PASSWORD_DEFAULT),
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'phone' => '+254700000000',
                'is_active' => true,
                'email_verified' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('users')->insertBatch($userData);

        // Assign admin role to the user
        $userRoleData = [
            [
                'user_id' => 1,
                'role_id' => 1, // Admin role
                'assigned_at' => date('Y-m-d H:i:s'),
                'assigned_by' => 1
            ]
        ];

        $this->db->table('user_roles')->insertBatch($userRoleData);

        echo "Production admin user created:\n";
        echo "Username: admin\n";
        echo "Email: admin@localhost:8000\n";
        echo "Password: NipoAgro2025!\n";
        echo "Please change this password after first login!\n";
    }
}
