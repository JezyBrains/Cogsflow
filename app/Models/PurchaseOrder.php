<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'po_number',
        'supplier_id',
        'commodity_type',
        'total_quantity_kg',
        'unit_price',
        'total_amount',
        'delivery_deadline',
        'status',
        'created_by',
        'notes'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Volume currently fulfilled by batches (excluding rejected ones)
     */
    public function getSuppliedQuantityKgAttribute()
    {
        return $this->batches()->where('status', '!=', 'rejected')->sum('total_weight_kg');
    }

    /**
     * Volume remaining to be fulfilled
     */
    public function getRemainingQuantityKgAttribute()
    {
        return max(0, $this->total_quantity_kg - $this->supplied_quantity_kg);
    }

    /**
     * Check if the PO is 100% fulfilled
     */
    public function isFull(): bool
    {
        return $this->remaining_quantity_kg <= 0;
    }
}
