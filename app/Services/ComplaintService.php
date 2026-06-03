<?php

namespace App\Services;

use App\Models\Complaint;
use App\Models\User;
use App\Notifications\ComplaintSubmittedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ComplaintService
{
    public function create(array $data): Complaint
    {
        return DB::transaction(function () use ($data) {

            $complaint = Complaint::create([
                'tracking_code' => $this->generateTrackingCode(),
                'complaint_category_id' => $data['complaint_category_id'],
                'is_anonymous' => $data['is_anonymous'],

                'name' => $data['is_anonymous']
                    ? null
                    : $data['name'],

                'phone' => $data['is_anonymous']
                    ? null
                    : $data['phone'],

                'nik' => $data['is_anonymous']
                    ? null
                    : $data['nik'],

                'description' => $data['description'],

                'status' => 'waiting',

                'submitted_at' => now(),
            ]);

            if (!empty($data['attachments'])) {

                foreach ($data['attachments'] as $file) {

                    $path = $file->store(
                        'complaints',
                        'public'
                    );

                    $complaint->attachments()->create([
                        'original_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'mime_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            $complaint->statusLogs()->create([
                'old_status' => null,
                'new_status' => $complaint->status,
                'note' => 'Pengaduan dibuat',
            ]);
            User::query()
                ->whereIn('role', [
                    'admin',
                    'supervisor',
                    'super_admin',
                ])
                ->where('is_active', true)
                ->get()
                ->each(function ($user) use ($complaint) {

                    $user->notify(
                        new ComplaintSubmittedNotification(
                            $complaint
                        )
                    );
                });

            return $complaint;
        });
    }

    public function generateTrackingCode(): string
    {
        do {

            $code = 'DHAAN-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(Str::random(7));

        } while (
            Complaint::where(
                'tracking_code',
                $code
            )->exists()
        );

        return $code;
    }
}