<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplaintStatusLog extends Model
{
    protected $fillable = [
        'complaint_id',
        'user_id',
        'old_status',
        'new_status',
        'note',
    ];

    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}