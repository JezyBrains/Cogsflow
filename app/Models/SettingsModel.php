<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingsModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['key', 'value', 'type', 'category', 'description', 'is_sensitive'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'key' => 'required|max_length[255]|is_unique[settings.key,id,{id}]',
        'value' => 'permit_empty',
        'type' => 'required|in_list[string,integer,boolean,json,text]',
        'category' => 'required|max_length[100]',
        'description' => 'permit_empty',
        'is_sensitive' => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'key' => [
            'required' => 'Setting key is required',
            'is_unique' => 'Setting key must be unique'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = ['clearCache'];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = ['clearCache'];

    /**
     * Get setting value by key
     */
    public function getSetting($key, $default = null)
    {
        $setting = $this->where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return $this->castValue($setting['value'], $setting['type']);
    }

    /**
     * Set setting value
     */
    public function setSetting($key, $value, $type = 'string', $category = 'general', $description = null, $isSensitive = false)
    {
        $data = [
            'key' => $key,
            'value' => $this->prepareValue($value, $type),
            'type' => $type,
            'category' => $category,
            'description' => $description,
            'is_sensitive' => $isSensitive
        ];

        $existing = $this->where('key', $key)->first();
        
        if ($existing) {
            return $this->update($existing['id'], $data);
        } else {
            return $this->insert($data);
        }
    }

    /**
     * Get settings by category
     */
    public function getSettingsByCategory($category)
    {
        $settings = $this->where('category', $category)->findAll();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting['key']] = [
                'value' => $this->castValue($setting['value'], $setting['type']),
                'type' => $setting['type'],
                'description' => $setting['description'],
                'is_sensitive' => $setting['is_sensitive']
            ];
        }
        
        return $result;
    }

    /**
     * Get all settings grouped by category
     */
    public function getAllSettingsGrouped($includeSensitive = false)
    {
        $builder = $this->builder();
        
        if (!$includeSensitive) {
            $builder->where('is_sensitive', false);
        }
        
        $settings = $builder->get()->getResultArray();
        $result = [];
        
        foreach ($settings as $setting) {
            $category = $setting['category'];
            if (!isset($result[$category])) {
                $result[$category] = [];
            }
            
            $result[$category][$setting['key']] = [
                'value' => $this->castValue($setting['value'], $setting['type']),
                'type' => $setting['type'],
                'description' => $setting['description'],
                'is_sensitive' => $setting['is_sensitive']
            ];
        }
        
        return $result;
    }

    /**
     * Cast value to appropriate type
     */
    private function castValue($value, $type)
    {
        switch ($type) {
            case 'integer':
                return (int) $value;
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'json':
                return json_decode($value, true);
            case 'text':
            case 'string':
            default:
                return $value;
        }
    }

    /**
     * Prepare value for storage
     */
    private function prepareValue($value, $type)
    {
        switch ($type) {
            case 'json':
                return json_encode($value);
            case 'boolean':
                return $value ? '1' : '0';
            default:
                return (string) $value;
        }
    }

    /**
     * Clear cache after settings change
     */
    protected function clearCache(array $data)
    {
        cache()->clean();
        return $data;
    }

    /**
     * Initialize default settings
     */
    public function initializeDefaults()
    {
        $defaults = [
            // Company Information
            ['key' => 'company_name', 'value' => 'Grain Management System', 'type' => 'string', 'category' => 'company', 'description' => 'Company name displayed throughout the system'],
            ['key' => 'company_email', 'value' => 'admin@grainmanagement.com', 'type' => 'string', 'category' => 'company', 'description' => 'Primary company email address'],
            ['key' => 'company_phone', 'value' => '+254-XXX-XXXXXX', 'type' => 'string', 'category' => 'company', 'description' => 'Company phone number'],
            ['key' => 'company_address', 'value' => '', 'type' => 'text', 'category' => 'company', 'description' => 'Company physical address'],
            
            // System Settings
            ['key' => 'system_name', 'value' => 'CogsFlow - Grain Management', 'type' => 'string', 'category' => 'system', 'description' => 'System name shown in browser title'],
            ['key' => 'base_url', 'value' => env('app.baseURL', 'https://nipoagro.com/'), 'type' => 'string', 'category' => 'system', 'description' => 'Base URL of the application'],
            ['key' => 'default_currency', 'value' => 'KES', 'type' => 'string', 'category' => 'system', 'description' => 'Default currency code'],
            ['key' => 'default_timezone', 'value' => 'Africa/Nairobi', 'type' => 'string', 'category' => 'system', 'description' => 'Default timezone'],
            ['key' => 'date_format', 'value' => 'Y-m-d', 'type' => 'string', 'category' => 'system', 'description' => 'Default date format'],
            ['key' => 'datetime_format', 'value' => 'Y-m-d H:i:s', 'type' => 'string', 'category' => 'system', 'description' => 'Default datetime format'],
            
            // Business Settings
            ['key' => 'low_stock_threshold', 'value' => '20', 'type' => 'integer', 'category' => 'business', 'description' => 'Minimum stock level before low stock alert'],
            ['key' => 'enable_notifications', 'value' => '1', 'type' => 'boolean', 'category' => 'business', 'description' => 'Enable system notifications'],
            ['key' => 'auto_backup', 'value' => '1', 'type' => 'boolean', 'category' => 'business', 'description' => 'Enable automatic daily backups'],
            ['key' => 'backup_retention_days', 'value' => '30', 'type' => 'integer', 'category' => 'business', 'description' => 'Number of days to keep backup files'],
            
            // Security Settings
            ['key' => 'session_timeout', 'value' => '7200', 'type' => 'integer', 'category' => 'security', 'description' => 'Session timeout in seconds'],
            ['key' => 'password_min_length', 'value' => '8', 'type' => 'integer', 'category' => 'security', 'description' => 'Minimum password length'],
            ['key' => 'enable_2fa', 'value' => '0', 'type' => 'boolean', 'category' => 'security', 'description' => 'Enable two-factor authentication'],
        ];

        foreach ($defaults as $default) {
            $existing = $this->where('key', $default['key'])->first();
            if (!$existing) {
                $this->insert($default);
            }
        }
    }

    /**
     * Update or create a setting
     */
    public function updateSetting($key, $value, $category = null)
    {
        try {
            // Find existing setting
            $existing = $this->where('key', $key)->first();
            
            if ($existing) {
                // Update existing setting
                return $this->update($existing['id'], [
                    'value' => $value,
                    'category' => $category ?? $existing['category']
                ]);
            } else {
                // Create new setting if it doesn't exist
                return $this->insert([
                    'key' => $key,
                    'value' => $value,
                    'category' => $category ?? 'system',
                    'type' => 'string',
                    'description' => 'Imported setting',
                    'is_sensitive' => false
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Failed to update setting: ' . $e->getMessage());
            return false;
        }
    }
}
