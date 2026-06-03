<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class ComplaintResponse extends Model
{
    protected $fillable = [
        'complaint_id',
        'created_by',
        'solution',
        'approval_status',
        'reviewed_by',
        'review_note',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(
            User::class,
            'reviewed_by'
        );
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }
}