<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference_number',
        'category_id',
        'amount',
        'currency',
        'transaction_date',
        'recordable_type',
        'recordable_id',
        'payment_method',
        'payee_payer_name',
        'notes',
        'status',
        'recorded_by',
        'approved_by'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(FinanceCategory::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function recordable()
    {
        return $this->morphTo();
    }
}
