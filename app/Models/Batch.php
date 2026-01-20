<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = [
        'batch_number',
        'supplier_id',
        'purchase_order_id',
        'commodity_type',
        'expected_bags',
        'total_weight_kg',
        'average_moisture',
        'quality_grade',
        'status',
        'received_by',
        'received_at'
    ];

    protected $casts = [
        'received_at' => 'datetime',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function bags()
    {
        return $this->hasMany(BatchBag::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function dispatches()
    {
        return $this->hasMany(Dispatch::class);
    }
}
