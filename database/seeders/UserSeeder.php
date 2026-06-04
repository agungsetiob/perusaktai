<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Data untuk Role: admin
        DB::table('users')->insert([
            'name' => 'Ahmad Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Data untuk Role: supervisor
        DB::table('users')->insert([
            'name' => 'Budi Humas',
            'email' => 'kasubbag@example.com',
            'role' => 'supervisor',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Data untuk Role: super_admin
        DB::table('users')->insert([
            'name' => 'Chandra Super Admin',
            'email' => 'superadmin@example.com',
            'role' => 'super_admin',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}