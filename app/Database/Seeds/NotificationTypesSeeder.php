<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class NotificationTypesSeeder extends Seeder
{
    public function run()
    {
        $notificationTypes = [
            [
                'name' => 'batch_created',
                'description' => 'New batch has been created',
                'default_enabled' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'batch_approved',
                'description' => 'Batch has been approved',
                'default_enabled' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'dispatch_created',
                'description' => 'New dispatch has been created',
                'default_enabled' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'dispatch_status_change',
                'description' => 'Dispatch status has changed',
                'default_enabled' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'inventory_low',
                'description' => 'Inventory level is low',
                'default_enabled' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'expense_created',
                'description' => 'New expense has been logged',
                'default_enabled' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'po_approval_required',
                'description' => 'Purchase order requires approval',
                'default_enabled' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'system_error',
                'description' => 'System error occurred',
                'default_enabled' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];

        foreach ($notificationTypes as $type) {
            // Check if notification type exists
            $existing = $this->db->table('notification_types')
                ->where('name', $type['name'])
                ->get()
                ->getRow();

            if (!$existing) {
                $this->db->table('notification_types')->insert($type);
            }
        }
        
        echo "✓ Notification types seeded successfully\n";
    }
}
