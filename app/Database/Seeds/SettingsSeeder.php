<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Company Information
            [
                'key' => 'company_name',
                'value' => 'Grain Management System',
                'type' => 'string',
                'category' => 'company',
                'description' => 'Company name displayed throughout the system',
                'is_sensitive' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'company_email',
                'value' => 'admin@grainmanagement.com',
                'type' => 'string',
                'category' => 'company',
                'description' => 'Primary company email address',
                'is_sensitive' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'company_phone',
                'value' => '+254-XXX-XXXXXX',
                'type' => 'string',
                'category' => 'company',
                'description' => 'Company phone number',
                'is_sensitive' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'company_address',
                'value' => '',
                'type' => 'text',
                'category' => 'company',
                'description' => 'Company physical address',
                'is_sensitive' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
            // System Settings
            [
                'key' => 'system_name',
                'value' => 'CogsFlow - Grain Management',
                'type' => 'string',
                'category' => 'system',
                'description' => 'System name shown in browser title',
                'is_sensitive' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'base_url',
                'value' => 'http://localhost:8000/',
                'type' => 'string',
                'category' => 'system',
                'description' => 'Base URL of the application',
                'is_sensitive' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'default_currency',
                'value' => 'TSH',
                'type' => 'string',
                'category' => 'system',
                'description' => 'Default currency code',
                'is_sensitive' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'default_timezone',
                'value' => 'Africa/Nairobi',
                'type' => 'string',
                'category' => 'system',
                'description' => 'Default timezone',
                'is_sensitive' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'date_format',
                'value' => 'Y-m-d',
                'type' => 'string',
                'category' => 'system',
                'description' => 'Default date format',
                'is_sensitive' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'datetime_format',
                'value' => 'Y-m-d H:i:s',
                'type' => 'string',
                'category' => 'system',
                'description' => 'Default datetime format',
                'is_sensitive' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
            // Business Settings
            [
                'key' => 'low_stock_threshold',
                'value' => '20',
                'type' => 'integer',
                'category' => 'business',
                'description' => 'Minimum stock level before low stock alert',
                'is_sensitive' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'enable_notifications',
                'value' => '1',
                'type' => 'boolean',
                'category' => 'business',
                'description' => 'Enable system notifications',
                'is_sensitive' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'auto_backup',
                'value' => '1',
                'type' => 'boolean',
                'category' => 'business',
                'description' => 'Enable automatic daily backups',
                'is_sensitive' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'backup_retention_days',
                'value' => '30',
                'type' => 'integer',
                'category' => 'business',
                'description' => 'Number of days to keep backup files',
                'is_sensitive' => false,
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
                'is_sensitive' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'password_min_length',
                'value' => '8',
                'type' => 'integer',
                'category' => 'security',
                'description' => 'Minimum password length',
                'is_sensitive' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'enable_2fa',
                'value' => '0',
                'type' => 'boolean',
                'category' => 'security',
                'description' => 'Enable two-factor authentication',
                'is_sensitive' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Insert settings data
        $this->db->table('settings')->insertBatch($data);
        
        // Log the seeding action
        $this->db->table('system_logs')->insert([
            'level' => 'info',
            'message' => 'Default settings initialized via seeder',
            'context' => json_encode(['settings_count' => count($data)]),
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
