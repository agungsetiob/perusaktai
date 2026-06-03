<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ComplaintCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }
}