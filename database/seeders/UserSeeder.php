<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed users: 1 Admin, 4 Employees
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin Posita',
            'email' => 'admin@posita.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Employee/Kasir Users
        $employees = [
            ['name' => 'Rivaldi', 'email' => 'rivaldi@posita.com'],
            ['name' => 'Amar', 'email' => 'amar@posita.com'],
            ['name' => 'Nurita', 'email' => 'nurita@posita.com'],
            ['name' => 'Belva', 'email' => 'belva@posita.com'],
        ];

        foreach ($employees as $employee) {
            User::create([
                'name' => $employee['name'],
                'email' => $employee['email'],
                'password' => Hash::make('password'),
                'role' => 'employee',
                'is_active' => true,
            ]);
        }
    }
}
