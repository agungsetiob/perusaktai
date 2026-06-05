<?php

namespace App\Notifications;

use App\Models\Complaint;

class WhatsappNotification
{
    public function __construct(
        public Complaint $complaint
    ) {
    }

    public function message(): string
    {
        return
            "Halo,\n\n" .

            "Pengaduan Anda telah selesai ditindaklanjuti.\n\n" .

            "Kode Tracking:\n" .
            $this->complaint->tracking_code .

            "\n\nStatus: SELESAI"

            . "\n\nTerima kasih telah menggunakan layanan pengaduan.";
    }
}