<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Teacher
        User::factory()->create([
            'name' => 'Dr. Eko Prasetyo',
            'email' => 'teacher@example.com',
            'password' => bcrypt('password'),
            'role' => 'teacher',
            'nidn_npm' => '11223344',
        ]);

        // Student
        User::factory()->create([
            'name' => 'Budi Santoso',
            'email' => 'student@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'nidn_npm' => '12345678',
        ]);
    }
}
