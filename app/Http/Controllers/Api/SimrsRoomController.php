<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SimrsRoomService;

class SimrsRoomController extends Controller
{
    public function installations(
        SimrsRoomService $service
    ) {
        return response()->json(
            $service->installations()
        );
    }

    public function rooms(
        string $installation,
        SimrsRoomService $service
    ) {
        return response()->json(
            $service->rooms($installation)
        );
    }
}