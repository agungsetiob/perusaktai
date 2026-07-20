<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SimrsRoom;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SimrsRoomController extends Controller
{
    public function index(Request $request)
    {
        $rooms = SimrsRoom::query()

            ->when(
                $request->search,
                fn($q, $search) =>
                $q->where('DESKRIPSI', 'like', "%{$search}%")
            )

            ->when(
                $request->jenis,
                fn($q, $jenis) =>
                $q->where('JENIS', $jenis)
            )

            ->when(
                $request->jenis_kunjungan,
                fn($q, $jenis) =>
                $q->where('JENIS_KUNJUNGAN', $jenis)
            )

            ->orderBy('ID')

            ->paginate(10)

            ->withQueryString();

        return Inertia::render(
            'Admin/SimrsRooms/Index',
            [
                'rooms' => $rooms,

                'filters' => $request->only([
                    'search',
                    'jenis',
                    'jenis_kunjungan',
                ]),
            ]
        );
    }
}
