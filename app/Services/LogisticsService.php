<?php

namespace App\Services;

use App\Models\Batch;
use App\Models\BatchBag;
use App\Models\Dispatch;
use Illuminate\Support\Facades\DB;
use App\Services\AuditService;
use App\Services\InventoryService;

class LogisticsService
{
    protected $auditService;
    protected $inventoryService;

    public function __construct(AuditService $auditService, InventoryService $inventoryService)
    {
        $this->auditService = $auditService;
        $this->inventoryService = $inventoryService;
    }

    /**
     * Create a new grain batch from arrival
     */
    public function createBatch(array $data, array $bags, ?int $userId = null)
    {
        $userId = $userId ?: \Illuminate\Support\Facades\Auth::id();
        if (!$userId) {
            throw new \Exception("Authentication context missing for batch creation.");
        }
        return DB::transaction(function () use ($data, $bags, $userId) {
            $batch = Batch::create([
                'batch_number' => $data['batch_number'] ?? 'B-' . now()->format('YmdHis'),
                'supplier_id' => $data['supplier_id'],
                'purchase_order_id' => $data['purchase_order_id'] ?? null,
                'commodity_type' => $data['commodity_type'],
                'expected_bags' => count($bags),
                'total_weight_kg' => 0, // Summed below
                'average_moisture' => 0, // Calculated below
                'quality_grade' => $data['quality_grade'] ?? 'Pending',
                'status' => 'at_gate',
                'received_by' => $userId,
                'received_at' => now()
            ]);

            $totalWeight = 0;
            $totalMoisture = 0;
            foreach ($bags as $bagData) {
                BatchBag::create([
                    'batch_id' => $batch->id,
                    'bag_serial_number' => $bagData['serial'] ?? null,
                    'weight_kg' => $bagData['weight_kg'],
                    'moisture_content' => $bagData['moisture'] ?? null,
                ]);
                $totalWeight += $bagData['weight_kg'];
                $totalMoisture += ($bagData['moisture'] ?? 0);
            }

            $batch->update([
                'total_weight_kg' => $totalWeight,
                'average_moisture' => count($bags) > 0 ? $totalMoisture / count($bags) : 0
            ]);

            $this->auditService->log('batch_created', $batch);

            return $batch;
        });
    }

    /**
     * Transition batch through lifecycle
     */
    public function updateBatchStatus(int $batchId, string $status, ?int $userId = null)
    {
        $userId = $userId ?: \Illuminate\Support\Facades\Auth::id();
        $batch = Batch::findOrFail($batchId);
        $batch->update(['status' => $status]);

        $this->auditService->log('batch_status_updated', $batch);

        return $batch;
    }

    /**
     * Create a dispatch record
     */
    public function createDispatch(array $data, ?int $userId = null)
    {
        $userId = $userId ?: \Illuminate\Support\Facades\Auth::id();
        if (!$userId) {
            throw new \Exception("Authentication context missing for dispatch creation.");
        }
        return DB::transaction(function () use ($data, $userId) {
            $dispatch = Dispatch::create([
                'dispatch_number' => 'DIS-' . strtoupper(uniqid()),
                'batch_id' => $data['batch_id'] ?? null,
                'vehicle_reg_number' => $data['vehicle_reg_number'],
                'trailer_number' => $data['trailer_number'] ?? null,
                'driver_id' => $data['driver_id'] ?? null,
                'driver_name' => $data['driver_name'] ?? null,
                'driver_phone' => $data['driver_phone'] ?? null,
                'driver_id_type' => $data['driver_id_type'] ?? null,
                'driver_id_number' => $data['driver_id_number'] ?? null,
                'destination' => $data['destination'],
                'status' => 'pending',
                'dispatcher_id' => $userId,
                'dispatched_at' => now(),
                'estimated_arrival' => $data['estimated_arrival'] ?? now()->addDays(1)
            ]);

            if (isset($data['batch_id'])) {
                $batch = Batch::findOrFail($data['batch_id']);
                $batch->update(['status' => 'shipped']);
            }

            $this->auditService->log('dispatch_created', $dispatch);

            return $dispatch;
        });
    }

    /**
     * Confirm delivery and update stock
     */
    public function confirmDelivery(int $dispatchId, ?int $userId = null)
    {
        $userId = $userId ?: \Illuminate\Support\Facades\Auth::id();
        return DB::transaction(function () use ($dispatchId, $userId) {
            $dispatch = Dispatch::with('batch')->findOrFail($dispatchId);
            $dispatch->update(['status' => 'delivered', 'actual_arrival' => now()]);

            if ($dispatch->batch) {
                $batch = $dispatch->batch;
                $batch->update(['status' => 'accepted']); // Final state for receiving

                // Add to inventory (Maintain consistency in KG)
                $this->inventoryService->updateStock(
                    $batch->commodity_type,
                    $batch->total_weight_kg,
                    'Stock In',
                    $userId
                );
            }

            $this->auditService->log('dispatch_delivered', $dispatch);

            return $dispatch;
        });
    }

    /**
     * Emergency Vehicle Swap
     */
    public function swapVehicle(int $dispatchId, array $data)
    {
        return DB::transaction(function () use ($dispatchId, $data) {
            $dispatch = Dispatch::findOrFail($dispatchId);
            $oldValues = $dispatch->toArray();

            $dispatch->update([
                'vehicle_reg_number' => $data['vehicle_reg_number'],
                'trailer_number' => $data['trailer_number'] ?? null,
                'driver_name' => $data['driver_name'] ?? $dispatch->driver_name,
                'driver_phone' => $data['driver_phone'] ?? $dispatch->driver_phone,
                'driver_id_type' => $data['driver_id_type'] ?? $dispatch->driver_id_type,
                'driver_id_number' => $data['driver_id_number'] ?? $dispatch->driver_id_number,
            ]);

            $this->auditService->log('dispatch_vehicle_swapped', $dispatch, $oldValues, $data);

            return $dispatch;
        });
    }

    /**
     * Record individual bag inspection with discrepancy tracking
     */
    public function recordBagInspection(array $data, ?int $userId = null)
    {
        $userId = $userId ?: \Illuminate\Support\Facades\Auth::id();

        return DB::transaction(function () use ($data, $userId) {
            $bag = BatchBag::findOrFail($data['bag_id']);

            // Calculate discrepancies
            $weightDifference = $data['actual_weight'] - $bag->weight_kg;
            $moistureDifference = isset($data['actual_moisture']) ? ($data['actual_moisture'] - $bag->moisture_content) : 0;

            $bag->update([
                'actual_weight' => $data['actual_weight'],
                'actual_moisture' => $data['actual_moisture'] ?? null,
                'weight_discrepancy' => $weightDifference,
                'moisture_discrepancy' => $moistureDifference,
                'condition_status' => $data['condition_status'],
                'inspection_notes' => $data['notes'] ?? null,
                'inspected_by' => $userId,
                'inspected_at' => now(),
            ]);

            $this->auditService->log('bag_inspected', $bag);

            return $bag;
        });
    }
}
