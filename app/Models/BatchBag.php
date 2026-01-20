<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchBag extends Model
{
    protected $fillable = [
        'batch_id',
        'bag_serial_number',
        'weight_kg',
        'moisture_content',
        'is_damaged',
        'actual_weight',
        'actual_moisture',
        'weight_discrepancy',
        'moisture_discrepancy',
        'condition_status',
        'inspection_notes',
        'inspected_by',
        'inspected_at'
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
