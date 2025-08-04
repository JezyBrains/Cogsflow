<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationSettingsModel extends Model
{
    protected $table = 'notification_settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id', 'notification_type', 'enabled', 'delivery_method', 
        'sound_enabled', 'desktop_enabled'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'user_id' => 'required|integer',
        'notification_type' => 'required|max_length[50]',
        'enabled' => 'in_list[0,1]',
        'delivery_method' => 'in_list[in_app,email,both]',
        'sound_enabled' => 'in_list[0,1]',
        'desktop_enabled' => 'in_list[0,1]'
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => 'User ID is required',
            'integer' => 'User ID must be an integer'
        ],
        'notification_type' => [
            'required' => 'Notification type is required',
            'max_length' => 'Notification type cannot exceed 50 characters'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get user's notification settings
     */
    public function getUserSettings($userId)
    {
        return $this->select('notification_settings.*, nt.display_name, nt.description, nt.icon, nt.color')
                   ->join('notification_types nt', 'notification_settings.notification_type = nt.name')
                   ->where('notification_settings.user_id', $userId)
                   ->orderBy('nt.display_name')
                   ->findAll();
    }

    /**
     * Update user's notification setting
     */
    public function updateUserSetting($userId, $notificationType, $settings)
    {
        $existing = $this->where('user_id', $userId)
                        ->where('notification_type', $notificationType)
                        ->first();

        if ($existing) {
            return $this->update($existing['id'], $settings);
        } else {
            $settings['user_id'] = $userId;
            $settings['notification_type'] = $notificationType;
            return $this->insert($settings);
        }
    }

    /**
     * Check if user should receive notification
     */
    public function shouldReceiveNotification($userId, $notificationType)
    {
        $setting = $this->where('user_id', $userId)
                       ->where('notification_type', $notificationType)
                       ->first();

        return $setting ? (bool)$setting['enabled'] : true; // Default to enabled
    }

    /**
     * Get users who should receive a notification type
     */
    public function getUsersForNotificationType($notificationType)
    {
        return $this->select('u.id, u.username, u.email, ns.delivery_method, ns.sound_enabled, ns.desktop_enabled')
                   ->join('users u', 'ns.user_id = u.id')
                   ->where('ns.notification_type', $notificationType)
                   ->where('ns.enabled', 1)
                   ->find();
    }

    /**
     * Create default settings for a new user
     */
    public function createDefaultSettings($userId)
    {
        $notificationTypes = $this->db->table('notification_types')
                                    ->select('name, default_enabled, role_specific')
                                    ->get()
                                    ->getResult();

        // Get user roles
        $userRoles = $this->db->table('user_roles ur')
                            ->join('roles r', 'ur.role_id = r.id')
                            ->where('ur.user_id', $userId)
                            ->where('ur.is_active', 1)
                            ->select('r.name')
                            ->get()
                            ->getResult();

        $userRoleNames = array_map(function($role) {
            return $role->name;
        }, $userRoles);

        $settings = [];
        foreach ($notificationTypes as $type) {
            // Check if notification type is relevant for user's roles
            $roleSpecific = json_decode($type->role_specific, true);
            $isRelevant = empty($roleSpecific) || !empty(array_intersect($userRoleNames, $roleSpecific));
            
            if ($isRelevant) {
                $settings[] = [
                    'user_id' => $userId,
                    'notification_type' => $type->name,
                    'enabled' => $type->default_enabled,
                    'delivery_method' => 'in_app',
                    'sound_enabled' => true,
                    'desktop_enabled' => false,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
            }
        }

        if (!empty($settings)) {
            return $this->insertBatch($settings);
        }

        return true;
    }

    /**
     * Update multiple settings at once
     */
    public function updateBulkSettings($userId, $settings)
    {
        $this->db->transStart();

        foreach ($settings as $notificationType => $setting) {
            $this->updateUserSetting($userId, $notificationType, $setting);
        }

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    /**
     * Get notification preferences summary
     */
    public function getPreferencesSummary($userId)
    {
        $total = $this->where('user_id', $userId)->countAllResults();
        $enabled = $this->where('user_id', $userId)->where('enabled', 1)->countAllResults();
        $emailEnabled = $this->where('user_id', $userId)
                           ->whereIn('delivery_method', ['email', 'both'])
                           ->where('enabled', 1)
                           ->countAllResults();
        $soundEnabled = $this->where('user_id', $userId)
                           ->where('sound_enabled', 1)
                           ->where('enabled', 1)
                           ->countAllResults();

        return [
            'total_types' => $total,
            'enabled_types' => $enabled,
            'email_notifications' => $emailEnabled,
            'sound_notifications' => $soundEnabled,
            'enabled_percentage' => $total > 0 ? round(($enabled / $total) * 100, 1) : 0
        ];
    }
}
