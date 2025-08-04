<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        $db = $this->db;
        
        // Get first user (admin)
        $user = $db->table('users')->select('id')->get()->getFirstRow();
        
        if (!$user) {
            echo "No users found.\n";
            return;
        }
        
        // Create test notifications
        $notifications = [
            [
                'user_id' => $user->id,
                'type' => 'system_maintenance',
                'title' => 'System Initialized',
                'message' => 'Notification system is now active.',
                'priority' => 'normal',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => $user->id,
                'type' => 'batch_arrival',
                'title' => 'New Batch Arrived',
                'message' => 'Batch #B001 has arrived.',
                'priority' => 'high',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];
        
        $db->table('notifications')->insertBatch($notifications);
        echo "Test notifications created.\n";
    }
}
