<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductionSeeder extends Seeder
{
    public function run()
    {
        // Seed essential data for production
        $this->call('RolesSeeder');
        $this->call('PermissionsSeeder');
        $this->call('ProductionUserSeeder');
        $this->call('ProductionSettingsSeeder');
        $this->call('NotificationTypesSeeder');
        $this->call('ReportsSeeder');
    }
}
