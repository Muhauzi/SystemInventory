<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BatasPeminjaman;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        //     'role' => 'admin',
        // ]);

        // User::factory()->create([
        //     'name' => 'Test User 2',
        //     'email' => 'user@example.com',
        //     'role' => 'user',
        // ]);

        BatasPeminjaman::create([
            'batas_nominal' => 10000000, // batas default peminjaman 10 juta
        ]);
    }
}
