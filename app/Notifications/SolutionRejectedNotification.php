<?php

namespace App\Notifications;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SolutionRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Complaint $complaint,
        public User $actor
    ) {}

    public function via(
        object $notifiable
    ): array {
        return ['database'];
    }

    public function toArray(
        object $notifiable
    ): array {
        return [
            'title' => 'Solusi Ditolak',

            'message' =>
                $this->actor->name .
                ' menolak solusi untuk pengaduan ' .
                $this->complaint->tracking_code .
                ' dan meminta perbaikan.',

            'actor_id' =>
                $this->actor->id,

            'actor_name' =>
                $this->actor->name,

            'complaint_id' =>
                $this->complaint->id,

            'tracking_code' =>
                $this->complaint->tracking_code,

            'type' =>
                'solution_rejected',
        ];
    }
}