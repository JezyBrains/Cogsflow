<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DefaultSettingsSeeder extends Seeder
{
    public function run()
    {
        // Default system settings
        $settings = [
            // Company Settings
            [
                'key' => 'company_name',
                'value' => 'Nipo Agro',
                'category' => 'company',
                'type' => 'string',
                'description' => 'Company name displayed throughout the system',
                'is_hidden' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'company_email',
                'value' => 'info@nipoagro.com',
                'category' => 'company',
                'type' => 'string',
                'description' => 'Primary company email address',
                'is_hidden' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'company_phone',
                'value' => '+255 123 456 789',
                'category' => 'company',
                'type' => 'string',
                'description' => 'Company phone number',
                'is_hidden' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'company_address',
                'value' => 'Dar es Salaam, Tanzania',
                'category' => 'company',
                'type' => 'text',
                'description' => 'Company physical address',
                'is_hidden' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // System Settings
            [
                'key' => 'system_name',
                'value' => 'CogsFlow Grain Management',
                'category' => 'system',
                'type' => 'string',
                'description' => 'System name displayed in browser title',
                'is_hidden' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'base_url',
                'value' => 'https://nipoagro.com',
                'category' => 'system',
                'type' => 'string',
                'description' => 'Base URL of the application',
                'is_hidden' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'default_currency',
                'value' => 'TSH',
                'category' => 'system',
                'type' => 'string',
                'description' => 'Default currency for financial calculations',
                'is_hidden' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'default_timezone',
                'value' => 'Africa/Nairobi',
                'category' => 'system',
                'type' => 'string',
                'description' => 'Default timezone for date/time operations',
                'is_hidden' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'date_format',
                'value' => 'Y-m-d',
                'category' => 'system',
                'type' => 'string',
                'description' => 'Default date format for display',
                'is_hidden' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'default_weight_unit',
                'value' => 'kg',
                'category' => 'system',
                'type' => 'string',
                'description' => 'Default weight unit for measurements',
                'is_hidden' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'show_secondary_unit',
                'value' => '1',
                'category' => 'system',
                'type' => 'boolean',
                'description' => 'Show secondary unit conversions',
                'is_hidden' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // Business Settings
            [
                'key' => 'low_stock_threshold',
                'value' => '100',
                'category' => 'business',
                'type' => 'integer',
                'description' => 'Threshold for low stock alerts (kg)',
                'is_hidden' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'backup_retention_days',
                'value' => '30',
                'category' => 'business',
                'type' => 'integer',
                'description' => 'Number of days to retain backup files',
                'is_hidden' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'enable_notifications',
                'value' => '1',
                'category' => 'business',
                'type' => 'boolean',
                'description' => 'Enable system notifications',
                'is_hidden' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'auto_backup',
                'value' => '1',
                'category' => 'business',
                'type' => 'boolean',
                'description' => 'Enable automatic database backups',
                'is_hidden' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // Security Settings
            [
                'key' => 'session_timeout',
                'value' => '3600',
                'category' => 'security',
                'type' => 'integer',
                'description' => 'Session timeout in seconds',
                'is_hidden' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'password_min_length',
                'value' => '8',
                'category' => 'security',
                'type' => 'integer',
                'description' => 'Minimum password length requirement',
                'is_hidden' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'enable_2fa',
                'value' => '0',
                'category' => 'security',
                'type' => 'boolean',
                'description' => 'Enable two-factor authentication',
                'is_hidden' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Clear existing settings first
        $this->db->table('settings')->truncate();

        // Insert default settings
        foreach ($settings as $setting) {
            $this->db->table('settings')->insert($setting);
        }

        echo "Default settings created successfully.\n";
    }
}
