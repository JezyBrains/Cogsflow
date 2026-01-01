<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotificationSettingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'notification_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'enabled' => [
                'type'    => 'BOOLEAN',
                'default' => true,
            ],
            'delivery_method' => [
                'type'       => 'ENUM',
                'constraint' => ['in_app', 'email', 'both'],
                'default'    => 'in_app',
            ],
            'sound_enabled' => [
                'type'    => 'BOOLEAN',
                'default' => true,
            ],
            'desktop_enabled' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['user_id', 'notification_type']);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('notification_type', 'notification_types', 'name', 'CASCADE', 'CASCADE');
        $this->forge->createTable('notification_settings');

        // Create default notification settings for existing users (if any)
        try {
            $usersQuery = $this->db->table('users')->select('id')->get();
            if ($usersQuery === false) {
                return; // Table doesn't exist yet, skip seeding
            }
            
            $users = $usersQuery->getResult();
            
            $notificationTypesQuery = $this->db->table('notification_types')->select('name, default_enabled, role_specific')->get();
            if ($notificationTypesQuery === false) {
                return; // Table doesn't exist yet, skip seeding
            }
            
            $notificationTypes = $notificationTypesQuery->getResult();
            
            foreach ($users as $user) {
                // Get user roles
                $userRolesQuery = $this->db->table('user_roles ur')
                    ->join('roles r', 'ur.role_id = r.id')
                    ->where('ur.user_id', $user->id)
                    ->where('ur.is_active', 1)
                    ->select('r.name')
                    ->get();
                
                if ($userRolesQuery === false) {
                    continue;
                }
                
                $userRoles = $userRolesQuery->getResult();
                
                $userRoleNames = array_map(function($role) {
                    return $role->name;
                }, $userRoles);

                foreach ($notificationTypes as $type) {
                    // Check if notification type is relevant for user's roles
                    $roleSpecific = json_decode($type->role_specific, true);
                    $isRelevant = empty($roleSpecific) || !empty(array_intersect($userRoleNames, $roleSpecific));
                    
                    if ($isRelevant) {
                        $this->db->table('notification_settings')->insert([
                            'user_id' => $user->id,
                            'notification_type' => $type->name,
                            'enabled' => $type->default_enabled,
                            'delivery_method' => 'in_app',
                            'sound_enabled' => true,
                            'desktop_enabled' => false,
                            'created_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            // Silently fail if tables don't exist yet
            log_message('debug', 'Notification settings seeding skipped: ' . $e->getMessage());
        }
    }

    public function down()
    {
        $this->forge->dropTable('notification_settings');
    }
}
