<?php

namespace App\Services;

use App\Models\Complaint;
use App\Ai\Agents\HospitalComplaintAgent;
use Exception;

class AiSolutionService
{
    public function generate(Complaint $complaint): string
    {
        $category = $complaint->category?->name ?? 'Tidak diketahui';

        $prompt = "
                Berdasarkan data berikut:
                Kategori: {$category}
                Isi Pengaduan: {$complaint->description}

                Tugas Anda:
                1. Analisis singkat masalah.
                2. Berikan langkah penanganan yang realistis.
                3. Buat draft solusi resmi yang sopan.

                Fokus pada draft solusi yang dapat dikirim kepada manajemen.
                Jangan gunakan markdown.
                ";

        try {
            $response = (new HospitalComplaintAgent())->prompt($prompt);

            return (string) $response;

        } catch (Exception $e) {
            return "Gagal membuat draf solusi otomatis: " . $e->getMessage();
        }
    }

    public function chatConversation(Complaint $complaint, string $userMessage): string
    {
        $category = $complaint->category?->name ?? 'Tidak diketahui';

        $prompt = "
            Anda sedang berdiskusi dengan staf internal mengenai pengaduan berikut:
            Kategori: {$category}
            Isi Pengaduan: {$complaint->description}
            Status Saat Ini: {$complaint->status}

            Pertanyaan/Instruksi staf: {$userMessage}

            Jawablah dengan ringkas, profesional, berorientasi pada SOP rumah sakit, dan JANGAN gunakan format markdown.
        ";

        try {
            $response = (new HospitalComplaintAgent())->prompt($prompt);
            return (string) $response;
        } catch (Exception $e) {
            return "Gagal mendapatkan respons AI: " . $e->getMessage();
        }
    }
}