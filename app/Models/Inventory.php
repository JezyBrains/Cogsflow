<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';

    protected $fillable = [
        'item_code',
        'grain_type',
        'description',
        'current_stock_mt',
        'minimum_level_mt',
        'unit_cost',
        'location',
        'status'
    ];

    public function getBalanceKgAttribute()
    {
        return $this->current_stock_mt * 1000;
    }
}
