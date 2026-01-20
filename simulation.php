<?php

use App\Models\User;
use App\Models\Supplier;
use App\Services\LogisticsService;
use App\Services\InventoryService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

try {
    echo "--- STARTING SIMULATION ---\n";

    // 0. Setup Data
    $user = User::first();
    if (!$user) {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        echo "Created User: {$user->name}\n";
    }

    $supplier = Supplier::first();
    if (!$supplier) {
        $supplier = Supplier::create([
            'name' => 'AgriCorps Ltd',
            'code' => 'SUP-001',
            'contact_person' => 'Jane Smith',
            'email' => 'jane@agricorps.com',
            'is_active' => true
        ]);
        echo "Created Supplier: {$supplier->name}\n";
    }

    $logistics = app(LogisticsService::class);
    $inventoryService = app(InventoryService::class);

    echo "Initial Inventory: " . json_encode($inventoryService->getStockSummary()) . "\n";

    // 1. Create Batch
    $batchData = [
        'batch_number' => 'B-' . time(),
        'supplier_id' => $supplier->id,
        'purchase_order_id' => null,
        'commodity_type' => 'White Maize',
        'quality_grade' => 'Grade A',
        'status' => 'at_gate'
    ];
    $bags = [
        ['weight_kg' => 90, 'moisture' => 12],
        ['weight_kg' => 90, 'moisture' => 12]
    ];

    echo "Creating Batch...\n";
    $batch = $logistics->createBatch($batchData, $bags, $user->id);
    echo "Batch Created: " . $batch->batch_number . " (ID: $batch->id, Weight: {$batch->total_weight_kg}kg)\n";

    // 2. Create Dispatch
    $dispatchData = [
        'batch_id' => $batch->id,
        'vehicle_reg_number' => 'T123ABC',
        'driver_name' => 'John Doe',
        'driver_phone' => '0712345678',
        'destination' => 'Main Warehouse',
        'estimated_arrival' => now()->addHours(2)
    ];

    echo "Creating Dispatch...\n";
    $dispatch = $logistics->createDispatch($dispatchData, $user->id);
    echo "Dispatch Created: " . $dispatch->dispatch_number . " (ID: $dispatch->id)\n";

    // 3. Confirm Delivery
    echo "Confirming Delivery...\n";
    $updatedDispatch = $logistics->confirmDelivery($dispatch->id, $user->id);
    echo "Dispatch Arrived. Status: " . $updatedDispatch->status . "\n";

    // 4. Check Inventory
    echo "Final Inventory: " . json_encode($inventoryService->getStockSummary()) . "\n";
    echo "--- SIMULATION COMPLETE ---\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
