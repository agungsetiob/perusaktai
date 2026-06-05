<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FonnteService
{
    public function send(
        string $phone,
        string $message
    ): array {

        $response = Http::withHeaders([
            'Authorization' => config('services.fonnte.token'),
        ])->post(
            'https://api.fonnte.com/send',
            [
                'target' => $phone,
                'message' => $message,
            ]
        );

        return [
            'success' => $response->successful(),
            'body' => $response->json(),
        ];
    }
}