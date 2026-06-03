<?php

namespace App\Services;

use App\Models\Complaint;
use Exception;

class AiSolutionService
{
    public function generate(Complaint $complaint): string 
    {
        $category = $complaint->category?->name ?? 'Tidak diketahui';

        //Definisikan instruksi peran (System Instruction)
        $instructions = "Anda adalah petugas penanganan pengaduan rumah sakit yang profesional, empati, dan solutif.";

        //Susun perintah isi laporan (User Prompt)
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
            //Eksekusi
            $response = \Laravel\Ai\agent(
                instructions: $instructions,
            )->prompt($prompt);
            
            //Cast hasil response menjadi string
            return (string) $response;
            
        } catch (Exception $e) {
            // Fallback jika API key Gemini bermasalah atau kuota limit
            return "Gagal membuat draf solusi otomatis: " . $e->getMessage();
        }
    }
}