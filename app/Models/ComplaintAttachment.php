<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplaintAttachment extends Model
{
    protected $fillable = [
        'complaint_id',
        'original_name',
        'file_path',
        'mime_type',
        'file_size',
    ];

    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class);
    }
}