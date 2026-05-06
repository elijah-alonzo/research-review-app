<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a dean (admin) user
        User::firstOrCreate(
            ['email' => 'dean@example.com'],
            [
                'first_name' => 'Dean',
                'middle_initial' => null,
                'last_name' => 'User',
                'password' => Hash::make('password'),
                'role' => 'dean',
            ]
        );

        // Create a faculty user
        User::firstOrCreate(
            ['email' => 'faculty@example.com'],
            [
                'first_name' => 'Faculty',
                'middle_initial' => null,
                'last_name' => 'User',
                'password' => Hash::make('password'),
                'role' => 'faculty',
            ]
        );
    }
}
