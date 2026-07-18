<?php

namespace App\Services;

use App\Enums\ComplaintStatus;
use App\Models\Complaint;
use Illuminate\Support\Carbon;

class ComplaintReportService
{
    public function generate(
        ?string $startDate = null,
        ?string $endDate = null
    ): array {

        $query = Complaint::query()
            ->with('category')
            ->when(
                $startDate,
                fn($q) => $q->whereDate(
                    'submitted_at',
                    '>=',
                    $startDate
                )
            )
            ->when(
                $endDate,
                fn($q) => $q->whereDate(
                    'submitted_at',
                    '<=',
                    $endDate
                )
            );

        $complaints = (clone $query)->get();

        $total = $complaints->count();

        $waiting = $complaints
            ->where('status', ComplaintStatus::WAITING)
            ->count();

        $underReview = $complaints
            ->where('status', ComplaintStatus::UNDER_REVIEW)
            ->count();

        $onProcess = $complaints
            ->where('status', ComplaintStatus::ON_PROCESS)
            ->count();

        $solved = $complaints
            ->where('status', ComplaintStatus::SOLVED)
            ->count();

        $rejected = $complaints
            ->where('status', ComplaintStatus::REJECTED)
            ->count();

        // 1. Mengambil selisih durasi dalam satuan MENIT untuk laporan yang sudah selesai (Solved)
        $resolutionMinutes = $complaints
            ->filter(fn($item) => $item->solved_at && $item->submitted_at)
            ->map(function ($item) {
                return Carbon::parse($item->submitted_at)
                    ->diffInMinutes($item->solved_at);
            });

        // 2. Mencari nilai rata-rata, tercepat, dan terlama (masih dalam satuan menit)
        $avgMinutes = $resolutionMinutes->isNotEmpty() ? round($resolutionMinutes->avg()) : 0;
        $minMinutes = $resolutionMinutes->isNotEmpty() ? $resolutionMinutes->min() : 0;
        $maxMinutes = $resolutionMinutes->isNotEmpty() ? $resolutionMinutes->max() : 0;

        // 3. Fungsi penolong internal untuk mengubah total menit ke format "X Jam Y Menit"
        $formatToHoursAndMinutes = function ($totalMinutes) {
            if ($totalMinutes <= 0) {
                return '0 Menit';
            }
            
            $hours = floor($totalMinutes / 60);
            $minutes = $totalMinutes % 60;

            if ($hours > 0) {
                return $minutes > 0 ? "{$hours} Jam {$minutes} Menit" : "{$hours} Jam";
            }
            
            return "{$minutes} Menit";
        };

        // 4. Konversi nilai menit menjadi string teks siap pakai
        $avgResolution = $formatToHoursAndMinutes($avgMinutes);
        $fastestResolution = $formatToHoursAndMinutes($minMinutes);
        $slowestResolution = $formatToHoursAndMinutes($maxMinutes);

        $categoryStats = $complaints
            ->groupBy(
                fn($item) =>
                $item->category?->name
                ?? 'Tidak Berkategori'
            )
            ->map(fn($group) => $group->count())
            ->sortDesc();

        $monthlyComplaints = Complaint::query()
            ->selectRaw("
                DATE_FORMAT(submitted_at,'%Y-%m')
                as month,
                COUNT(*) as total
            ")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'complaints' => $complaints,

            'startDate' => $startDate
                ? Carbon::parse($startDate)
                    ->format('d-m-Y')
                : 'Semua Data',

            'endDate' => $endDate
                ? Carbon::parse($endDate)
                    ->format('d-m-Y')
                : now()->format('d-m-Y'),

            'summary' => [
                'total' => $total,
                'waiting' => $waiting,
                'under_review' => $underReview,
                'on_process' => $onProcess,
                'solved' => $solved,
                'rejected' => $rejected,
                'completion_rate' => $total > 0
                    ? round(
                        ($solved / $total) * 100,
                        2
                    )
                    : 0,
            ],

            'sla' => [
                'avg_hours' => $avgResolution,
                'fastest_hours' => $fastestResolution,
                'slowest_hours' => $slowestResolution,
            ],

            'categoryStats' => $categoryStats,

            'monthlyComplaints' => $monthlyComplaints,
        ];
    }
}