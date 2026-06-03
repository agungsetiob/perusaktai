<?php

namespace App\Notifications;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ComplaintSubmittedNotification extends Notification
{
    use Queueable;
    public function __construct(
        public Complaint $complaint
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
            'title' => 'Pengaduan Baru',

            'message' =>
                'Pengaduan baru dengan kode ' .
                $this->complaint->tracking_code,

            'complaint_id' =>
                $this->complaint->id,
        ];
    }
}
