<?php

namespace App\Services;

use App\Enums\ComplaintStatus;
use App\Enums\ResponseApprovalStatus;
use App\Models\Complaint;
use App\Models\ComplaintResponse;
use App\Models\User;
use App\Models\WhatsappLog;
use App\Notifications\SolutionSubmittedNotification;
use App\Notifications\SolutionApprovedNotification;
use App\Notifications\SolutionRejectedNotification;
use App\Notifications\ComplaintRejectedNotification;
use App\Services\AuditLogService;
use App\Services\FonnteService;
use App\Support\PhoneFormatter;
use App\Notifications\WhatsappNotification;
use Illuminate\Support\Facades\DB;

class ComplaintResponseService
{
    private AuditLogService $auditLog;

    public function __construct(
        AuditLogService $auditLog,
        FonnteService $fonnte
    ) {
        $this->auditLog = $auditLog;
        $this->fonnte = $fonnte;
    }
    private FonnteService $fonnte;

    public function submitSolution(
        Complaint $complaint,
        User $actor,
        string $solution
    ): ComplaintResponse {

        return DB::transaction(function () use ($complaint, $actor, $solution) {

            $response = $complaint
                ->responses()
                ->create([
                    'created_by' => $actor->id,
                    'solution' => $solution,
                    'approval_status' => ResponseApprovalStatus::PENDING,
                ]);

            $oldStatus = $complaint->status;

            $complaint->update([
                'status' => ComplaintStatus::UNDER_REVIEW,
            ]);

            $complaint->statusLogs()->create([
                'user_id' => $actor->id,
                'old_status' => $oldStatus,
                'new_status' => ComplaintStatus::UNDER_REVIEW,
                'note' => 'Solusi diajukan untuk direview manajemen',
            ]);

            $this->auditLog->log(
                module: 'complaint_response',
                action: 'Submit Solution',
                subject: $response,
                description: "Pengguna {$actor->name} mengajukan solusi untuk pengaduan dengan kode pelacakan {$complaint->tracking_code}",
                newValues: $response->toArray(),
            );

            User::query()
                ->whereIn('role', [
                    'supervisor',
                    'super_admin',
                ])
                ->where('is_active', true)
                ->get()
                ->each(function ($user) use ($complaint, $actor) {

                    $user->notify(
                        new SolutionSubmittedNotification(
                            $complaint,
                            $actor
                        )
                    );
                });

            return $response;
        });
    }

    public function approve(
        ComplaintResponse $response,
        User $actor,
        ?string $note = null
    ): void {

        DB::transaction(function () use ($response, $actor, $note) {

            $response->update([
                'approval_status' => ResponseApprovalStatus::APPROVED,
                'reviewed_by' => $actor->id,
                'review_note' => $note,
                'reviewed_at' => now(),
            ]);

            $complaint = $response->complaint;

            $oldStatus = $complaint->status;

            $complaint->update([
                'status' => ComplaintStatus::ON_PROCESS,
            ]);

            $complaint->statusLogs()->create([
                'user_id' => $actor->id,
                'old_status' => $oldStatus,
                'new_status' => ComplaintStatus::ON_PROCESS,
                'note' => $note ?? 'Solusi disetujui',
            ]);

            $this->auditLog->log(
                module: 'complaint_response',
                action: 'Approve Solution',
                subject: $response,
                description: "Pengguna {$actor->name} menyetujui solusi untuk pengaduan dengan kode pelacakan {$complaint->tracking_code}",
                oldValues: [
                    'approval_status' => ResponseApprovalStatus::PENDING,
                ],
                newValues: [
                    'approval_status' => ResponseApprovalStatus::APPROVED,
                ],
            );

            $response
                ->creator
                ->notify(
                    new SolutionApprovedNotification(
                        $complaint,
                        $actor
                    )
                );
        });
    }

    public function reject(
        ComplaintResponse $response,
        User $actor,
        string $note
    ): void {

        DB::transaction(function () use ($response, $actor, $note) {

            $response->update([
                'approval_status' => ResponseApprovalStatus::REJECTED,
                'reviewed_by' => $actor->id,
                'review_note' => $note,
                'reviewed_at' => now(),
            ]);

            $complaint = $response->complaint;

            $oldStatus = $complaint->status;

            $complaint->update([
                'status' => ComplaintStatus::WAITING,
            ]);

            $complaint->statusLogs()->create([
                'user_id' => $actor->id,
                'old_status' => $oldStatus,
                'new_status' => ComplaintStatus::WAITING,
                'note' => $note,
            ]);

            $this->auditLog->log(
                module: 'complaint_response',
                action: 'Reject Solution',
                subject: $response,
                description: "Pengguna {$actor->name} menolak solusi untuk pengaduan dengan kode pelacakan {$complaint->tracking_code}",
                oldValues: [
                    'approval_status' => ResponseApprovalStatus::PENDING,
                ],
                newValues: [
                    'approval_status' => ResponseApprovalStatus::REJECTED,
                ],
            );
            $response
                ->creator
                ->notify(
                    new SolutionRejectedNotification(
                        $complaint,
                        $actor
                    )
                );
        });
    }

    public function rejectComplaint(
        Complaint $complaint,
        User $actor,
        string $note
    ): void {

        DB::transaction(function () use ($complaint, $actor, $note) {

            $oldStatus = $complaint->status;

            $complaint->update([
                'status' => ComplaintStatus::REJECTED,
            ]);

            $complaint->statusLogs()->create([
                'user_id' => $actor->id,
                'old_status' => $oldStatus,
                'new_status' => ComplaintStatus::REJECTED,
                'note' => $note,
            ]);

            $this->auditLog->log(
                module: 'complaint',
                action: 'Reject Complaint',
                subject: $complaint,
                description: "Pengguna {$actor->name} menolak pengaduan dengan kode pelacakan {$complaint->tracking_code}",
                oldValues: [
                    'status' => $oldStatus,
                ],
                newValues: [
                    'status' => ComplaintStatus::REJECTED,
                ],
            );

            $latestResponse = $complaint
                ->responses()
                ->latest()
                ->first();

            if (
                $latestResponse &&
                $latestResponse->creator
            ) {
                $latestResponse
                    ->creator
                    ->notify(
                        new ComplaintRejectedNotification(
                            $complaint,
                            $actor
                        )
                    );
            }
        });
    }

    public function solve(
        Complaint $complaint,
        User $actor
    ): void {

        DB::transaction(function () use ($complaint, $actor) {

            $oldStatus = $complaint->status;

            $complaint->update([
                'status' => ComplaintStatus::SOLVED,
                'solved_at' => now(),
            ]);

            $complaint->statusLogs()->create([
                'user_id' => $actor->id,
                'old_status' => $oldStatus,
                'new_status' => ComplaintStatus::SOLVED,
                'note' => 'Pengaduan telah diselesaikan',
            ]);

            $this->auditLog->log(
                module: 'Complaint',
                action: 'Solve Complaint',
                subject: $complaint,
                description: "Pengguna {$actor->name} menyelesaikan pengaduan dengan kode pelacakan {$complaint->tracking_code}",
                oldValues: [
                    'status' => $oldStatus,
                ],
                newValues: [
                    'status' => ComplaintStatus::SOLVED,
                    'solved_at' => now(),
                ],
            );

            if (
                !$complaint->is_anonymous &&
                $complaint->phone
            ) {
                $solution = $complaint
                    ->responses()
                    ->where('approval_status', 'approved')
                    ->latest()
                    ->first()?->solution ?? '-';

                $message = <<<TEXT
                    🏥 *Pemberitahuan Penyelesaian Pengaduan*

                    Yth. {$complaint->name},

                    Pengaduan yang Anda sampaikan telah selesai ditindaklanjuti.

                    📋 Nomor Pengaduan:
                    {$complaint->tracking_code}

                    💬 Hasil Tindak Lanjut:
                    {$solution}

                    Apabila masih terdapat hal yang perlu dikonfirmasi, silakan menghubungi petugas layanan pengaduan.

                    Terima kasih atas masukan yang telah Anda berikan untuk peningkatan mutu pelayanan rumah sakit.

                    Hormat kami,

                    *Tim Pengelola Pengaduan*
                    TEXT;

                $result = $this->fonnte->send(
                    $complaint->phone,
                    $message
                );

                WhatsappLog::create([
                    'complaint_id' => $complaint->id,
                    'phone' => $complaint->phone,
                    'message' => $message,
                    'status' => $result['success']
                        ? 'success'
                        : 'failed',
                    'response' => json_encode(
                        $result['body']
                    ),
                    'sent_at' => now(),
                ]);
                $this->auditLog->log(
                    module: 'whatsapp',
                    action: 'Send Notification',
                    subject: $complaint,
                    description: "Mengirim notifikasi WhatsApp penyelesaian pengaduan {$complaint->tracking_code}",
                    newValues: [
                        'phone' => $complaint->phone,
                        'status' => $result['success']
                            ? 'success'
                            : 'failed',
                    ]
                );
            }
        });
    }
}