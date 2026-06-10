<?php

namespace App\Services;

use App\Models\Complaint;
use App\Models\Room;

class RoomComplaintReportService
{
    public function generate(
        ?string $startDate,
        ?string $endDate
    ): array {

        $query = Complaint::query();

        if ($startDate) {
            $query->whereDate(
                'submitted_at',
                '>=',
                $startDate
            );
        }

        if ($endDate) {
            $query->whereDate(
                'submitted_at',
                '<=',
                $endDate
            );
        }

        $complaints = $query
            ->with([
                'room:id,name',
                'category:id,name',
            ])
            ->get();

        $roomSummary = Room::query()
            ->withCount([
                'complaints' => function ($query)
                use (
                    $startDate,
                    $endDate
                ) {

                    if ($startDate) {
                        $query->whereDate(
                            'submitted_at',
                            '>=',
                            $startDate
                        );
                    }

                    if ($endDate) {
                        $query->whereDate(
                            'submitted_at',
                            '<=',
                            $endDate
                        );
                    }
                }
            ])
            ->orderByDesc(
                'complaints_count'
            )
            ->get();

        $groupedComplaints = $complaints
            ->groupBy(
                fn($complaint) =>
                $complaint->room?->name
                ?? 'Tanpa Ruangan'
            );

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,

            'roomSummary' =>
            $roomSummary,

            'groupedComplaints' =>
            $groupedComplaints,

            'totalComplaints' =>
            $complaints->count(),
        ];
    }
}