<?php

namespace App\Services;

use App\Models\SimrsRoom;
use Illuminate\Support\Collection;

class SimrsRoomService
{
    /**
     * Daftar Instalasi yang dapat dipilih pasien
     */
    public function installations(): Collection
    {
        return SimrsRoom::query()
            ->select([
                'ID',
                'DESKRIPSI',
            ])
            ->where('STATUS', 1)
            ->where('JENIS', 3)
            ->where('ID', 'not like', '103%') //exclude ksm
            ->orderBy('DESKRIPSI')
            ->get()
            ->map(fn($item) => [
                'id' => $item->ID,
                'name' => $item->DESKRIPSI,
            ]);
    }

    /**
     * Daftar ruangan berdasarkan instalasi
     */
    public function rooms(string $installationId): Collection
    {
        return SimrsRoom::query()
            ->select([
                'ID',
                'DESKRIPSI',
            ])
            ->where('STATUS', 1)
            ->where('JENIS', 5)
            ->where('ID', 'like', $installationId . '%')
            ->orderBy('DESKRIPSI')
            ->get()
            ->map(fn($item) => [
                'id' => $item->ID,
                'name' => $item->DESKRIPSI,
            ]);
    }

    public function find(string $id): ?SimrsRoom
    {
        return SimrsRoom::query()
            ->where('ID', $id)
            ->where('STATUS', 1)
            ->first();
    }
}