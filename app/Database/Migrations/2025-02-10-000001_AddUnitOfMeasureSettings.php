<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUnitOfMeasureSettings extends Migration
{
    public function up()
    {
        // Insert default unit of measure settings
        $data = [
            [
                'key' => 'default_weight_unit',
                'value' => 'kg',
                'type' => 'string',
                'category' => 'system',
                'description' => 'Default unit for weight measurements (kg, mt, ton, lbs)',
                'is_sensitive' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'weight_unit_display',
                'value' => 'Kilograms (kg)',
                'type' => 'string',
                'category' => 'system',
                'description' => 'Display name for the weight unit',
                'is_sensitive' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'enable_unit_conversion',
                'value' => '1',
                'type' => 'boolean',
                'category' => 'system',
                'description' => 'Enable automatic unit conversion in the system',
                'is_sensitive' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'show_secondary_unit',
                'value' => '1',
                'type' => 'boolean',
                'category' => 'system',
                'description' => 'Show weight in secondary unit (e.g., show MT when primary is kg)',
                'is_sensitive' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Check if settings already exist before inserting
        foreach ($data as $setting) {
            $existing = $this->db->table('settings')
                ->where('key', $setting['key'])
                ->get()
                ->getRow();
            
            if (!$existing) {
                $this->db->table('settings')->insert($setting);
            }
        }
    }

    public function down()
    {
        // Remove unit of measure settings
        $keys = [
            'default_weight_unit',
            'weight_unit_display',
            'enable_unit_conversion',
            'show_secondary_unit'
        ];

        $this->db->table('settings')
            ->whereIn('key', $keys)
            ->delete();
    }
}
