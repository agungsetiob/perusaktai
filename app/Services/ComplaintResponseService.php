<?php

namespace App\Services;

use App\Enums\ComplaintStatus;
use App\Enums\ResponseApprovalStatus;
use App\Models\Complaint;
use App\Models\ComplaintResponse;
use App\Models\User;
use App\Notifications\SolutionSubmittedNotification;
use App\Notifications\SolutionApprovedNotification;
use App\Notifications\SolutionRejectedNotification;
use App\Notifications\ComplaintRejectedNotification;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\DB;

class ComplaintResponseService
{
    private AuditLogService $auditLog;

    public function __construct(AuditLogService $auditLog)
    {
        $this->auditLog = $auditLog;
    }

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
        });
    }
}