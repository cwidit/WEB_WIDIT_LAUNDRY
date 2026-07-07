<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Mengisi data ke tabel levels (idempotent)
        DB::table('level')->updateOrInsert(
            ['level_name' => 'Administrator'],
            ['created_at' => now(), 'updated_at' => now()]
        );
        DB::table('level')->updateOrInsert(
            ['level_name' => 'Operator'],
            ['created_at' => now(), 'updated_at' => now()]
        );
        DB::table('level')->updateOrInsert(
            ['level_name' => 'Owner'],
            ['created_at' => now(), 'updated_at' => now()]
        );

        // 2. Membuat akun default tanpa duplikat
        $adminLevel = DB::table('level')->where('level_name', 'Administrator')->first();
        $operatorLevel = DB::table('level')->where('level_name', 'Operator')->first();
        $ownerLevel = DB::table('level')->where('level_name', 'Owner')->first();

        User::updateOrCreate(
            ['email' => 'admin@laundry.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'), // Password default
                'id_level' => $adminLevel->id ?? null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'operator@laundry.com'],
            [
                'name' => 'Operator',
                'password' => Hash::make('password'),
                'id_level' => $operatorLevel->id ?? null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'owner@laundry.com'],
            [
                'name' => 'Owner',
                'password' => Hash::make('password'),
                'id_level' => $ownerLevel->id ?? null,
            ]
        );

        // Tambah data dummy untuk dashboard
        $this->call(DummyDashboardSeeder::class);
    }
}