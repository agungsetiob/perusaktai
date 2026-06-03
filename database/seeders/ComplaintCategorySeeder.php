<?php

namespace Database\Seeders;

use App\Models\ComplaintCategory;
use Illuminate\Database\Seeder;

class ComplaintCategorySeeder extends Seeder
{
    public function run(): void
    {
        ComplaintCategory::insert([
            ['name' => 'Pelayanan Dokter'],
            ['name' => 'Pelayanan Perawat'],
            ['name' => 'Farmasi'],
            ['name' => 'Administrasi'],
            ['name' => 'Fasilitas'],
            ['name' => 'Kebersihan'],
            ['name' => 'Lainnya'],
        ]);
    }
}