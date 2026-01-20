<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\FinanceCategory;
use Illuminate\Support\Facades\Hash;

class FinalSystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Executive Admin
        User::create([
            'name' => 'Executive Admin',
            'email' => 'admin@nipo.com',
            'password' => Hash::make('password'),
        ]);

        // 2. Setup Finance Categories
        $categories = [
            ['name' => 'Sales Revenue', 'type' => 'income', 'description' => 'Main revenue from grain sales'],
            ['name' => 'Consultancy Fees', 'type' => 'income', 'description' => 'Professional service fees'],
            ['name' => 'Office Rent', 'type' => 'expense', 'description' => 'Monthly premises rent'],
            ['name' => 'Operational Costs', 'type' => 'expense', 'description' => 'Utilities, repairs, and recurring costs'],
            ['name' => 'Stationery', 'type' => 'expense', 'description' => 'Office supplies and materials'],
            ['name' => 'Transport & Logistics', 'type' => 'expense', 'description' => 'Fuel, driver allowances, and vehicle maintenance'],
        ];

        foreach ($categories as $cat) {
            FinanceCategory::create($cat);
        }
    }
}
