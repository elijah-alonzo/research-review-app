<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $deanRole = Role::firstOrCreate(['name' => 'dean']);
        $facultyRole = Role::firstOrCreate(['name' => 'faculty']);

        // Create a dean (admin) user
        $dean = User::firstOrCreate(
            ['email' => 'dean@example.com'],
            [
                'first_name' => 'Dean',
                'middle_initial' => null,
                'last_name' => 'User',
                'password' => Hash::make('password'),
            ]
        );
        $dean->syncRoles([$deanRole]);

        // Create a faculty user
        $faculty = User::firstOrCreate(
            ['email' => 'faculty@example.com'],
            [
                'first_name' => 'Faculty',
                'middle_initial' => null,
                'last_name' => 'User',
                'password' => Hash::make('password'),
            ]
        );
        $faculty->syncRoles([$facultyRole]);
    }
}
