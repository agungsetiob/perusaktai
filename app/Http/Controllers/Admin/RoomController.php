<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Room;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class RoomController extends Controller
{
    public function index(): Response
    {
        $this->authorize(
            'viewAny',
            Room::class
        );

        return Inertia::render(
            'Admin/Rooms/Index',
            [
                'rooms' => Room::query()
                    ->orderBy('name')
                    ->get([
                        'id',
                        'name',
                        'is_active',
                    ]),
            ]
        );
    }

    public function store(
        StoreRoomRequest $request
    ): RedirectResponse {

        $this->authorize(
            'create',
            Room::class
        );

        $room = Room::create(
            $request->validated()
        );

        app(AuditLogService::class)->log(
            module: 'Room',
            action: 'Create Room',
            subject: $room,
            description:
            "Membuat ruang {$room->name}",
            newValues: $room->toArray(),
        );

        return back()->with(
            'success',
            'Ruang berhasil ditambahkan.'
        );
    }

    public function update(
        UpdateRoomRequest $request,
        Room $room
    ): RedirectResponse {

        $this->authorize(
            'update',
            $room
        );

        $oldValues = $room->toArray();

        $room->update(
            $request->validated()
        );

        app(AuditLogService::class)->log(
            module: 'Room',
            action: 'Update Room',
            subject: $room,
            description:
            "Mengubah ruang {$room->name}",
            oldValues: $oldValues,
            newValues: $room->fresh()->toArray(),
        );

        return back()->with(
            'success',
            'Ruang berhasil diperbarui.'
        );
    }

    public function destroy(
        Room $room
    ): RedirectResponse {

        $this->authorize(
            'delete',
            $room
        );

        $oldValues = $room->toArray();

        $room->update([
            'is_active' => false,
        ]);

        app(AuditLogService::class)->log(
            module: 'Room',
            action: 'Deactivate Room',
            subject: $room,
            description:
            "Menonaktifkan ruang {$room->name}",
            oldValues: $oldValues,
            newValues: $room->fresh()->toArray(),
        );

        return back()->with(
            'success',
            'Ruang berhasil dinonaktifkan.'
        );
    }
}