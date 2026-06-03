<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AuditLogController extends Controller
{
    public function index(
        Request $request
    ): Response {

        $logs = AuditLog::query()
            ->with([
                'user:id,name,email',
            ])

            ->when(
                $request->module,
                fn ($q) =>
                $q->where(
                    'module',
                    $request->module
                )
            )

            ->when(
                $request->action,
                fn ($q) =>
                $q->where(
                    'action',
                    $request->action
                )
            )

            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render(
            'Admin/AuditLogs/Index',
            [
                'logs' => $logs,

                'filters' => [
                    'module' => $request->module,
                    'action' => $request->action,
                ],
            ]
        );
    }
}