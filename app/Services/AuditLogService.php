<?php

namespace App\Services;

use App\Models\AuditLog;

class AuditLogService
{
    public function log(
        string $module,
        string $action,
        ?object $subject,
        string $description,
        ?array $oldValues = null,
        ?array $newValues = null
    ): void {

        AuditLog::create([
            'user_id' => auth()->id(),

            'module' => $module,

            'action' => $action,

            'subject_type' => $subject
                ? get_class($subject)
                : null,

            'subject_id' => $subject?->id,

            'old_values' => $oldValues,

            'new_values' => $newValues,

            'description' => $description,

            'ip_address' => request()->ip(),

            'user_agent' => request()->userAgent(),
        ]);
    }
}