<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductionSettingsSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            // System Settings
            [
                'key' => 'system_name',
                'value' => 'CogsFlow - Nipo Agro Management',
                'type' => 'string',
                'category' => 'system',
                'description' => 'System name shown in browser title',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'base_url',
                'value' => 'http://localhost:8000/',
                'type' => 'string',
                'category' => 'system',
                'description' => 'Base URL of the application',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'default_currency',
                'value' => 'TSH',
                'type' => 'string',
                'category' => 'system',
                'description' => 'Default currency code',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'default_timezone',
                'value' => 'Africa/Nairobi',
                'type' => 'string',
                'category' => 'system',
                'description' => 'Default timezone',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'date_format',
                'value' => 'Y-m-d',
                'type' => 'string',
                'category' => 'system',
                'description' => 'Default date format',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'time_format',
                'value' => 'H:i:s',
                'type' => 'string',
                'category' => 'system',
                'description' => 'Default time format',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // Company Information
            [
                'key' => 'company_name',
                'value' => 'Nipo Agro Limited',
                'type' => 'string',
                'category' => 'company',
                'description' => 'Company name',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'company_email',
                'value' => 'info@localhost:8000',
                'type' => 'string',
                'category' => 'company',
                'description' => 'Company email address',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'company_phone',
                'value' => '+254-XXX-XXXXXX',
                'type' => 'string',
                'category' => 'company',
                'description' => 'Company phone number',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'company_address',
                'value' => 'Nairobi, Kenya',
                'type' => 'string',
                'category' => 'company',
                'description' => 'Company address',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // Inventory Settings
            [
                'key' => 'low_stock_threshold',
                'value' => '20',
                'type' => 'integer',
                'category' => 'inventory',
                'description' => 'Low stock threshold for alerts',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'auto_reorder_enabled',
                'value' => 'false',
                'type' => 'boolean',
                'category' => 'inventory',
                'description' => 'Enable automatic reordering',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // Security Settings
            [
                'key' => 'session_timeout',
                'value' => '7200',
                'type' => 'integer',
                'category' => 'security',
                'description' => 'Session timeout in seconds',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'max_login_attempts',
                'value' => '5',
                'type' => 'integer',
                'category' => 'security',
                'description' => 'Maximum login attempts before lockout',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'lockout_duration',
                'value' => '900',
                'type' => 'integer',
                'category' => 'security',
                'description' => 'Lockout duration in seconds',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('settings')->insertBatch($settings);
    }
}
