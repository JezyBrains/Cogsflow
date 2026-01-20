<?php

namespace App\Services;

use App\Models\Supplier;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProcurementService
{
    protected $audit;

    public function __construct(AuditService $audit)
    {
        $this->audit = $audit;
    }

    /**
     * Create a new supplier.
     */
    public function createSupplier(array $data)
    {
        return DB::transaction(function () use ($data) {
            $supplier = Supplier::create($data);
            $this->audit->log('supplier_created', $supplier, null, $supplier->toArray());
            return $supplier;
        });
    }

    /**
     * Create a new purchase order.
     */
    public function createPurchaseOrder(array $data, ?int $userId = null)
    {
        $userId = $userId ?: \Illuminate\Support\Facades\Auth::id();
        if (!$userId) {
            throw new \Exception("Authentication context missing for Purchase Order creation.");
        }
        return DB::transaction(function () use ($data, $userId) {
            $data['created_by'] = $userId;
            $data['total_amount'] = $data['total_quantity_kg'] * $data['unit_price'];
            $data['po_number'] = 'PO-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(2)));

            $po = PurchaseOrder::create($data);
            $this->audit->log('purchase_order_created', $po, null, $po->toArray());
            return $po;
        });
    }

    /**
     * Update PO status.
     */
    public function updateStatus(PurchaseOrder $po, string $status)
    {
        $oldValues = $po->toArray();
        $po->update(['status' => $status]);
        $this->audit->log('purchase_order_status_updated', $po, $oldValues, ['status' => $status]);
        return $po;
    }
}
