<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Shared password for all seeded users
        $password = Hash::make('12345678');

        $users = [];

        // Create 10 student accounts
        for ($i = 1; $i <= 10; $i++) {
            $users[] = [
                'name'           => "Student {$i}",
                'email'          => "student{$i}@example.com",
                'password'       => $password,
                'role'           => 'student',
                'approve_status' => 'approved',
            ];
        }

        // Create 5 instructor accounts
        for ($i = 1; $i <= 5; $i++) {
            $users[] = [
                'name'           => "Instructor {$i}",
                'email'          => "instructor{$i}@example.com",
                'password'       => $password,
                'role'           => 'instructor',
                'approve_status' => 'approved',
            ];
        }

        User::insert($users);
    }
}
