<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappLog extends Model
{
    protected $fillable = [
        'complaint_id',
        'phone',
        'message',
        'status',
        'response',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    public function complaint()
    {
        return $this->belongsTo(
            Complaint::class
        );
    }
}