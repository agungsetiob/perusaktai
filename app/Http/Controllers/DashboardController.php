<?php

namespace App\Http\Controllers;

use App\Enums\ComplaintStatus;
use App\Models\Complaint;
use App\Services\ComplaintReportService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        $monthlyComplaints = Complaint::query()
            ->selectRaw("
                DATE_FORMAT(submitted_at, '%Y-%m') as month,
                COUNT(*) as total
            ")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $topCategories = Complaint::query()
            ->selectRaw('
                complaint_category_id,
                COUNT(*) as total
            ')
            ->with('category:id,name')
            ->groupBy('complaint_category_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $statusDistribution = [
            [
                'label' => 'Menunggu',
                'value' => Complaint::where(
                    'status',
                    ComplaintStatus::WAITING
                )->count(),
            ],

            [
                'label' => 'Ditinjau',
                'value' => Complaint::where(
                    'status',
                    ComplaintStatus::UNDER_REVIEW
                )->count(),
            ],

            [
                'label' => 'Diproses',
                'value' => Complaint::where(
                    'status',
                    ComplaintStatus::ON_PROCESS
                )->count(),
            ],

            [
                'label' => 'Selesai',
                'value' => Complaint::where(
                    'status',
                    ComplaintStatus::SOLVED
                )->count(),
            ],

            [
                'label' => 'Ditolak',
                'value' => Complaint::where(
                    'status',
                    ComplaintStatus::REJECTED
                )->count(),
            ],
        ];

        $avgResolutionMinutes = Complaint::query()
            ->whereNotNull('solved_at')
            ->selectRaw(
                'AVG(TIMESTAMPDIFF(MINUTE, submitted_at, solved_at)) as avg_time'
            )
            ->value('avg_time');

        $avgResolutionMinutes = (int) round($avgResolutionMinutes ?? 0);

        $solvedThisMonth = Complaint::query()
            ->where('status', ComplaintStatus::SOLVED)
            ->whereMonth('solved_at', now()->month)
            ->whereYear('solved_at', now()->year)
            ->count();

        $totalComplaints = Complaint::count();

        $solvedComplaints = Complaint::where(
            'status',
            ComplaintStatus::SOLVED
        )->count();

        $completionRate = $totalComplaints > 0
            ? round(
                ($solvedComplaints / $totalComplaints) * 100,
                1
            )
            : 0;

        return Inertia::render(
            'Admin/Dashboard',
            [
                'stats' => [
                    'total' => $totalComplaints,

                    'waiting' => Complaint::where(
                        'status',
                        ComplaintStatus::WAITING
                    )->count(),

                    'under_review' => Complaint::where(
                        'status',
                        ComplaintStatus::UNDER_REVIEW
                    )->count(),

                    'on_process' => Complaint::where(
                        'status',
                        ComplaintStatus::ON_PROCESS
                    )->count(),

                    'solved' => $solvedComplaints,

                    'rejected' => Complaint::where(
                        'status',
                        ComplaintStatus::REJECTED
                    )->count(),

                    'avg_resolution_hours' =>
                        floor($avgResolutionMinutes / 60) .
                        ' jam ' .
                        ($avgResolutionMinutes % 60) .
                        ' menit',

                    'solved_this_month' => $solvedThisMonth,

                    'completion_rate' => $completionRate,
                ],

                'monthlyComplaints' => $monthlyComplaints,

                'topCategories' => $topCategories,

                'statusDistribution' => $statusDistribution,

                'latestComplaints' => Complaint::query()
                    ->with('category:id,name')
                    ->latest()
                    ->take(5)
                    ->get([
                        'id',
                        'tracking_code',
                        'complaint_category_id',
                        'status',
                        'submitted_at',
                    ]),
            ]
        );
    }
}