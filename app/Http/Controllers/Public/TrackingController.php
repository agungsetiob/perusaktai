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
