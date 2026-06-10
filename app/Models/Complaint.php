<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Complaint extends Model
{
    protected $fillable = [
        'tracking_code',
        'complaint_category_id',
        'is_anonymous',
        'name',
        'phone',
        'nik',
        'description',
        'status',
        'submitted_at',
        'solved_at',
        'room_id'
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'submitted_at' => 'datetime',
        'solved_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ComplaintCategory::class, 'complaint_category_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ComplaintAttachment::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(ComplaintResponse::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(ComplaintStatusLog::class);
    }

    public function latestResponse()
    {
        return $this->hasOne(
            ComplaintResponse::class
        )->latestOfMany();
    }

    public function whatsappLogs()
    {
        return $this->hasMany(
            WhatsappLog::class
        );
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}