<?php

namespace App\Services;

use App\Models\Complaint;
use App\Models\Room;

class RoomComplaintReportService
{
    public function __construct(
        protected SimrsRoomService $simrsRoomService
    ) {
    }
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
                // 'room:id,name',
                'category:id,name',
            ])
            ->get();
        $roomService = app(SimrsRoomService::class);

        $complaints->each(function ($complaint) use ($roomService) {

            $room = $roomService->find($complaint->room_id);

            $complaint->room_name = $room?->DESKRIPSI;

        });

        $roomSummary = $complaints
            ->groupBy('room_name')
            ->map(function ($items, $roomName) {

                return (object) [
                    'name' => $roomName ?: 'Tanpa Ruangan',
                    'complaints_count' => $items->count(),
                ];

            })
            ->sortByDesc('complaints_count')
            ->values();

        $groupedComplaints = $complaints
            ->groupBy(function ($complaint) {

                return $complaint->room_name
                    ?: 'Tanpa Ruangan';

            });

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