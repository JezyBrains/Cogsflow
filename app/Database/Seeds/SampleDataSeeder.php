<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data first
        $this->db->table('batch_bags')->truncate();
        $this->db->table('batches')->truncate();
        $this->db->table('suppliers')->truncate();
        $this->db->table('inventory')->truncate();
        
        // Sample suppliers
        $suppliers = [
            [
                'name' => 'ABC Grains Ltd',
                'contact_person' => 'John Smith',
                'phone' => '+1-555-0101',
                'email' => 'john@abcgrains.com',
                'address' => '123 Grain Street, Farmville, State 12345',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'XYZ Suppliers',
                'contact_person' => 'Jane Doe',
                'phone' => '+1-555-0102',
                'email' => 'jane@xyzsuppliers.com',
                'address' => '456 Supply Avenue, Croptown, State 67890',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Farm Fresh Co',
                'contact_person' => 'Bob Johnson',
                'phone' => '+1-555-0103',
                'email' => 'bob@farmfresh.com',
                'address' => '789 Fresh Road, Harvest City, State 11111',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Golden Harvest Inc',
                'contact_person' => 'Alice Brown',
                'phone' => '+1-555-0104',
                'email' => 'alice@goldenharvest.com',
                'address' => '321 Golden Lane, Wheat Valley, State 22222',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('suppliers')->insertBatch($suppliers);

        // Sample batches
        $batches = [
            [
                'batch_number' => 'B2024001',
                'supplier_id' => 1,
                'grain_type' => 'wheat',
                'total_bags' => 50,
                'total_weight_kg' => 2500.00,
                'total_weight_mt' => 2.500,
                'average_moisture' => 12.5,
                'quality_grade' => 'A+',
                'status' => 'approved',
                'notes' => 'Premium quality wheat batch',
                'received_date' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
            ],
            [
                'batch_number' => 'B2024002',
                'supplier_id' => 2,
                'grain_type' => 'rice',
                'total_bags' => 30,
                'total_weight_kg' => 1800.00,
                'total_weight_mt' => 1.800,
                'average_moisture' => 14.2,
                'quality_grade' => 'A',
                'status' => 'pending',
                'notes' => 'Basmati rice batch for quality inspection',
                'received_date' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
            ],
            [
                'batch_number' => 'B2024003',
                'supplier_id' => 3,
                'grain_type' => 'corn',
                'total_bags' => 40,
                'total_weight_kg' => 3200.00,
                'total_weight_mt' => 3.200,
                'average_moisture' => 16.8,
                'quality_grade' => 'B+',
                'status' => 'dispatched',
                'notes' => 'Yellow corn batch dispatched to warehouse',
                'received_date' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
            ]
        ];

        $this->db->table('batches')->insertBatch($batches);

        // Sample batch bags for first batch
        $batchBags = [];
        for ($i = 1; $i <= 50; $i++) {
            $batchBags[] = [
                'batch_id' => 1,
                'bag_number' => $i,
                'weight_kg' => rand(45, 55) + (rand(0, 99) / 100),
                'moisture_percentage' => 12 + (rand(0, 100) / 100),
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
            ];
        }

        $this->db->table('batch_bags')->insertBatch($batchBags);

        // Sample inventory items
        $inventory = [
            [
                'item_code' => 'WH001',
                'grain_type' => 'wheat',
                'description' => 'Premium Wheat Grade A',
                'current_stock_mt' => 2.500,
                'minimum_level_mt' => 0.500,
                'unit_cost' => 5.00,
                'location' => 'Warehouse A - Section 1',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'item_code' => 'RC002',
                'grain_type' => 'rice',
                'description' => 'Basmati Rice Premium',
                'current_stock_mt' => 0.180,
                'minimum_level_mt' => 0.200,
                'unit_cost' => 10.00,
                'location' => 'Warehouse B - Section 2',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'item_code' => 'CR003',
                'grain_type' => 'corn',
                'description' => 'Yellow Corn Grade B',
                'current_stock_mt' => 1.200,
                'minimum_level_mt' => 0.300,
                'unit_cost' => 4.00,
                'location' => 'Warehouse A - Section 3',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('inventory')->insertBatch($inventory);

        echo "Sample data seeded successfully!\n";
    }
}
