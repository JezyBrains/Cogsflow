<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispatch extends Model
{
    protected $fillable = [
        'dispatch_number',
        'batch_id',
        'vehicle_reg_number',
        'trailer_number',
        'driver_id',
        'driver_name',
        'driver_phone',
        'destination',
        'route_plan',
        'status',
        'dispatched_at',
        'estimated_arrival',
        'actual_arrival',
        'dispatcher_id'
    ];

    protected $casts = [
        'dispatched_at' => 'datetime',
        'estimated_arrival' => 'datetime',
        'actual_arrival' => 'datetime'
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function dispatcher()
    {
        return $this->belongsTo(User::class, 'dispatcher_id');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
