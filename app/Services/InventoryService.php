<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\Transaction;
use App\Models\FinanceCategory;
use Illuminate\Support\Facades\DB;
use App\Services\AuditService;

class InventoryService
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * Update stock level for a grain type (KG)
     */
    public function updateStock(string $grainType, float $quantityKg, string $type, ?int $userId = null)
    {
        $userId = $userId ?: \Illuminate\Support\Facades\Auth::id();
        return DB::transaction(function () use ($grainType, $quantityKg, $type, $userId) {
            $inventory = Inventory::where('grain_type', $grainType)->first();

            if (!$inventory) {
                $inventory = Inventory::create([
                    'item_code' => strtoupper(substr($grainType, 0, 3)) . date('Ymd') . rand(100, 999),
                    'grain_type' => $grainType,
                    'description' => "Unified stock for $grainType",
                    'current_stock_mt' => 0,
                    'minimum_level_mt' => 10,
                    'location' => 'Main Warehouse',
                    'status' => 'active'
                ]);
            }

            $oldStock = $inventory->current_stock_mt;

            if ($type === 'Stock In') {
                $inventory->increment('current_stock_mt', $quantityKg);
            } else {
                $inventory->decrement('current_stock_mt', $quantityKg);
            }

            // Create a financial transaction record if applicable (simplified for v2)
            // In a real system, Stock In from supplier would be linked to a PO payment.

            $this->auditService->log('inventory_updated', $inventory);

            return $inventory;
        });
    }

    /**
     * Specialized stock adjustment with reason tracking (Legacy Parity)
     */
    public function adjustStock(array $data, ?int $userId = null)
    {
        $userId = $userId ?: \Illuminate\Support\Facades\Auth::id();

        return DB::transaction(function () use ($data, $userId) {
            $inventory = Inventory::where('grain_type', $data['grain_type'])->firstOrFail();

            $quantityKg = $data['quantity']; // Client sends KG for precision
            $type = $data['adjustment_type'];

            if (in_array($type, ['Stock Out', 'Damage/Loss'])) {
                $inventory->decrement('current_stock_mt', $quantityKg);
            } else {
                $inventory->increment('current_stock_mt', $quantityKg);
            }

            $this->auditService->log('inventory_adjusted', [
                'inventory_id' => $inventory->id,
                'type' => $type,
                'quantity_kg' => $data['quantity'],
                'reason' => $data['reason'],
                'reference' => $data['reference'] ?? null,
                'adjusted_by' => $userId
            ]);

            return $inventory;
        });
    }

    /**
     * Get summary for stock tiles
     */
    public function getStockSummary()
    {
        return Inventory::where('status', 'active')->get();
    }
}
