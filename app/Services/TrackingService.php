<?php

namespace App\Services;

use App\Models\Complaint;

class TrackingService
{
    public function findByCode(string $trackingCode): ?Complaint
    {
        return Complaint::query()
            ->with([
                'category',
                'statusLogs'
            ])
            ->where(
                'tracking_code',
                $trackingCode
            )
            ->first();
    }
}