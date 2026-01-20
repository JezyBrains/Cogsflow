<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class SecuritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Roles
        $adminRole = Role::create([
            'name' => 'System Director',
            'slug' => 'admin',
            'description' => 'Full access to all terminal modules.'
        ]);

        $logisticsRole = Role::create([
            'name' => 'Logistics Manager',
            'slug' => 'logistics',
            'description' => 'Manage inventory, batches, and dispatches.'
        ]);

        $financeRole = Role::create([
            'name' => 'Finance Director',
            'slug' => 'finance',
            'description' => 'Manage payments, expenses, and financial reporting.'
        ]);

        // 2. Create Permissions
        $permissions = [
            ['name' => 'Manage Users', 'slug' => 'manage_users', 'module' => 'security'],
            ['name' => 'View Audit Trails', 'slug' => 'view_audit', 'module' => 'security'],
            ['name' => 'Manage Procurement', 'slug' => 'manage_procurement', 'module' => 'procurement'],
            ['name' => 'Manage Logistics', 'slug' => 'manage_logistics', 'module' => 'logistics'],
            ['name' => 'Manage Finance', 'slug' => 'manage_finance', 'module' => 'finance'],
        ];

        foreach ($permissions as $p) {
            $permission = Permission::create($p);
            $adminRole->permissions()->attach($permission);

            if ($p['module'] == 'logistics')
                $logisticsRole->permissions()->attach($permission);
            if ($p['module'] == 'finance')
                $financeRole->permissions()->attach($permission);
        }

        // 3. Create Admin User
        $admin = User::create([
            'name' => 'Jezakh Admin',
            'email' => 'admin@niposystem.com',
            'password' => \Hash::make('password'),
        ]);

        $admin->roles()->attach($adminRole);
    }
}
