<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreComplaintRequest;
use App\Models\ComplaintCategory;
use App\Models\Room;
use App\Services\ComplaintService;
use Inertia\Inertia;
use Inertia\Response;

class ComplaintController extends Controller
{
    public function create(): Response
    {
        return Inertia::render(
            'Public/Complaint/Create',
            [
                'categories' => ComplaintCategory::query()
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->get([
                        'id',
                        'name',
                    ]),
                'rooms' => Room::query()
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->get([
                        'id',
                        'name',
                    ]),
            ]
        );
    }

    public function store(
        StoreComplaintRequest $request,
        ComplaintService $service
    ) {
        $complaint = $service->create(
            $request->validated()
        );

        return redirect()->route(
            'complaints.success',
            $complaint->tracking_code
        );
    }

    public function success(string $trackingCode)
    {
        return Inertia::render(
            'Public/Complaint/Success',
            [
                'tracking_code' => $trackingCode,
            ]
        );
    }
}