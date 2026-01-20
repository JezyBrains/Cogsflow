<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Executive Administrator',
                'slug' => 'admin',
                'description' => 'Full systemic control including identity governance, audit purging, and security configuration.'
            ],
            [
                'name' => 'Logistics Operative',
                'slug' => 'logistics',
                'description' => 'Management of batch protocols, dispatch nodes, and physical inspection terminals.'
            ],
            [
                'name' => 'Financial Controller',
                'slug' => 'finance',
                'description' => 'Management of fiscal categories, expenditure tracking, and financial intelligence reports.'
            ],
            [
                'name' => 'Procurement Officer',
                'slug' => 'procurement',
                'description' => 'Supplier relationship management and purchase order lifecycle execution.'
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
