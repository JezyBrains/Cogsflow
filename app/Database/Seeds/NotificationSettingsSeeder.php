<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class NotificationSettingsSeeder extends Seeder
{
    public function run()
    {
        // Get all notification types
        $notificationTypes = $this->db->table('notification_types')->get()->getResultArray();
        
        if (empty($notificationTypes)) {
            echo "No notification types found. Please run the notification types migration first.\n";
            return;
        }

        // Get all users
        $users = $this->db->table('users')->get()->getResultArray();
        
        if (empty($users)) {
            echo "No users found. Please create users first.\n";
            return;
        }

        $settingsData = [];
        
        foreach ($users as $user) {
            foreach ($notificationTypes as $type) {
                $settingsData[] = [
                    'user_id' => $user['id'],
                    'notification_type' => $type['name'],
                    'enabled' => $type['default_enabled'],
                    'delivery_method' => 'in_app',
                    'sound_enabled' => true,
                    'desktop_enabled' => false,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }
        }

        // Insert notification settings
        if (!empty($settingsData)) {
            $this->db->table('notification_settings')->insertBatch($settingsData);
            echo "Created notification settings for " . count($users) . " users and " . count($notificationTypes) . " notification types.\n";
        }
    }
}
