<?php

use App\Models\User;
use App\Models\Supplier;
use App\Services\ProcurementService;
use Illuminate\Support\Facades\Hash;

echo "--- STARTING PO VERIFICATION ---\n";

// 1. Ensure User
$user = User::first();
if (!$user) {
    $user = User::create([
        'name' => 'PO Tester',
        'email' => 'po@test.com',
        'password' => Hash::make('password')
    ]);
}
echo "User: " . $user->name . " (ID: " . $user->id . ")\n";

// 2. Ensure Supplier
$supplier = Supplier::first();
if (!$supplier) {
    $supplier = Supplier::create([
        'name' => 'Supplier A',
        'code' => 'SUP-A',
        'is_active' => true
    ]);
}
echo "Supplier: " . $supplier->name . "\n";

// 3. Test Service
$service = app(ProcurementService::class);
$data = [
    'supplier_id' => $supplier->id,
    'commodity_type' => 'Soya',
    'total_quantity_kg' => 1000,
    'unit_price' => 500,
    'delivery_deadline' => now()->addDays(7)->toDateString(),
    'notes' => 'Test PO'
];

echo "Creating PO...\n";
try {
    // PASSING USER ID EXPLICITLY as per new signature
    $po = $service->createPurchaseOrder($data, $user->id);
    echo "PO Created Successfully: " . $po->po_number . "\n";
    echo "Created By User ID: " . $po->created_by . "\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
