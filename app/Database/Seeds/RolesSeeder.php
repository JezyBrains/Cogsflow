<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'name' => 'admin',
                'description' => 'System Administrator with full access',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'warehouse_staff',
                'description' => 'Warehouse staff with operational access',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'standard_user',
                'description' => 'Standard user with limited access',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        foreach ($roles as $role) {
            // Check if role exists
            $existing = $this->db->table('roles')
                ->where('name', $role['name'])
                ->get()
                ->getRow();

            if (!$existing) {
                $this->db->table('roles')->insert($role);
                echo "✓ Created role: {$role['name']}\n";
            }
        }
    }
}
