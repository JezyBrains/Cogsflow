<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProcurementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $supplier1 = \App\Models\Supplier::create([
            'name' => 'Kibaigwa Farmers Co-op',
            'code' => 'SUP-KIBA-001',
            'contact_person' => 'Hamisi Juma',
            'phone' => '+255712345678',
            'email' => 'kibaigwa@grainnet.tz',
            'address' => 'Dodoma Rd, Kibaigwa',
            'rating' => 4.8
        ]);

        $supplier2 = \App\Models\Supplier::create([
            'name' => 'Arusha Agro Trade',
            'code' => 'SUP-ARUS-002',
            'contact_person' => 'Sarah Mollel',
            'phone' => '+255622334455',
            'email' => 'sarah@arusha-agro.com',
            'address' => 'Njiro Industrial Area',
            'rating' => 4.5
        ]);

        $admin = \App\Models\User::first();

        \App\Models\PurchaseOrder::create([
            'po_number' => 'PO-' . date('Ymd') . '-A1B2',
            'supplier_id' => $supplier1->id,
            'commodity_type' => 'White Maize',
            'total_quantity_kg' => 20000,
            'unit_price' => 850,
            'total_amount' => 17000000,
            'status' => 'issued',
            'created_by' => $admin->id,
            'notes' => 'Priority delivery for Q1 stock.'
        ]);

        \App\Models\PurchaseOrder::create([
            'po_number' => 'PO-' . date('Ymd') . '-C3D4',
            'supplier_id' => $supplier2->id,
            'commodity_type' => 'Yellow Soya',
            'total_quantity_kg' => 5000,
            'unit_price' => 1200,
            'total_amount' => 6000000,
            'status' => 'draft',
            'created_by' => $admin->id,
            'notes' => 'Pending quality assurance verification.'
        ]);
    }
}
