<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Complaint;

class TrackingController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Public/Tracking/Index');
    }

    public function show(string $tracking_code): Response
    {
        $complaint = Complaint::query()
            ->where('tracking_code', $tracking_code)
            ->with([
                'category:id,name',

                'statusLogs:id,complaint_id,old_status,new_status,note,created_at',

                'responses' => function ($query) {
                    $query
                        ->select(
                            'id',
                            'complaint_id',
                            'solution',
                            'approval_status',
                            'created_at'
                        )
                        ->where(
                            'approval_status',
                            \App\Enums\ResponseApprovalStatus::APPROVED
                        )
                        ->latest();
                },
            ])
            ->first();

        if (!$complaint) {
            return Inertia::render('Public/Tracking/Index', [
                'errorMessage' => 'Kode tracking tidak ditemukan',
                'old' => [
                    'tracking_code' => $tracking_code,
                ],
            ]);
        }

        return Inertia::render('Public/Tracking/Result', [
            'complaint' => $complaint,
        ]);
    }

    public function embedLayanan(): Response
    {
        $targetUrl = 'https://web-rsud.test/dokter/jadwal';

        return Inertia::render('Public/EmbedJadwalDokter', [
            'targetUrl' => $targetUrl
        ]);
    }
}
