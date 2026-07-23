<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ComplaintController extends Controller
{
    public function index(Request $request): Response
    {
        $complaints = Complaint::query()
            ->with([
                'category:id,name',
                // 'room:id,name'
            ])
            ->when(
                $request->search,
                fn($q, $search) => $q->where(
                    'tracking_code',
                    'like',
                    "%{$search}%"
                )
            )
            ->when(
                $request->status,
                fn($q, $status) => $q->where(
                    'status',
                    $status
                )
            )
            ->when(
                $request->category_id,
                fn($q, $categoryId) => $q->where(
                    'complaint_category_id',
                    $categoryId
                )
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();
        $roomService = app(\App\Services\SimrsRoomService::class);

        $complaints->getCollection()->transform(function ($complaint) use ($roomService) {

            $room = $roomService->find($complaint->room_id);

            $complaint->room_name = $room?->DESKRIPSI;

            return $complaint;
        });

        return Inertia::render(
            'Admin/Complaints/Index',
            [
                'complaints' => $complaints,

                'filters' => [
                    'search' => $request->search,
                    'status' => $request->status,
                    'category_id' => $request->category_id,
                ],

                'categories' => ComplaintCategory::query()
                    ->orderBy('name')
                    ->get([
                        'id',
                        'name',
                    ]),
                // 'rooms' => Room::query()
                //     ->orderBy('name')
                //     ->get([
                //         'id',
                //         'name',
                //     ]),
            ]
        );
    }

    public function show(
        Complaint $complaint
    ) {
        $complaint->load([
            'category',
            // 'room:id,name',
            'attachments',

            'responses' => fn($query) => $query
                ->with([
                    'creator:id,name',
                    'reviewer:id,name',
                ])
                ->latest(),

            'latestResponse' => fn($query) => $query
                ->with([
                    'creator:id,name',
                    'reviewer:id,name',
                ]),

            'statusLogs' => fn($query) => $query
                ->with([
                    'user:id,name',
                ])
                ->latest(),
        ]);

        $complaint->attachments->transform(
            function ($attachment) {
                return [
                    'id' => $attachment->id,
                    'original_name' => $attachment->original_name,
                    'mime_type' => $attachment->mime_type,
                    'file_size' => $attachment->file_size,
                    'url' => Storage::url($attachment->file_path),
                ];
            }
        );

        $room = app(\App\Services\SimrsRoomService::class)
            ->find($complaint->room_id);

        $complaint->room_name = $room?->DESKRIPSI;

        return Inertia::render(
            'Admin/Complaints/Show',
            [
                'complaint' => $complaint,
            ]
        );
    }

    public function updateSubmittedAt(
        Request $request,
        Complaint $complaint
    ) {
        $request->validate([
            'submitted_at' => [
                'required',
                'date',
            ],
        ]);

        $complaint->update([
            'submitted_at' => Carbon::parse(
                $request->submitted_at
            ),
        ]);

        return back()->with(
            'success',
            'Tanggal pengaduan berhasil diperbarui.'
        );
    }
}
