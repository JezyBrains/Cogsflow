<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Attachment extends Model
{
    protected $fillable = [
        'name',
        'file_path',
        'file_type',
        'file_size',
        'document_type', // e.g., 'Invoice', 'LPO', 'Quality Certificate'
        'attachable_id',
        'attachable_type',
        'uploaded_by'
    ];

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
