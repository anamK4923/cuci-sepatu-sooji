<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin Soooji',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'password' => bcrypt('Admin123'),
        ]);

        User::factory()->create([
            'name' => 'Member Soooji',
            'email' => 'member@gmail.com',
            'role' => 'member',
            'password' => bcrypt('Member123'),
        ]);
    }
}
