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

        //admin dummy
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        //user dummy
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'role' => 'user',
        ]);

        //pimpinan dummy
        User::factory()->create([
            'name' => 'Pimpinan',
            'email' => 'pimpinan@example.com',
            'role' => 'pimpinan',
        ]);

        //partner dummy
        User::factory()->create([
            'name' => 'Partner 1',
            'email' => 'partner@example.com',
            'role' => 'partnership',
        ]);

        //batas peminjaman dummy
        


    }
}
