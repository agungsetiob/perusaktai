<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreComplaintRequest;
use App\Models\ComplaintCategory;
use App\Models\Room;
use App\Services\ComplaintService;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

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
        $response = Http::asForm()->post(
            'https://challenges.cloudflare.com/turnstile/v0/siteverify',
            [
                'secret' => config('services.turnstile.secret'),
                'response' => $request->turnstile_token,
                'remoteip' => $request->ip(),
            ]
        );

        if (! $response->json('success')) {
            throw ValidationException::withMessages([
                'turnstile_token' => 'Verifikasi keamanan gagal.',
            ]);
        }
        
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
