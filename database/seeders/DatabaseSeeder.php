<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database for production release.
     */
    public function run(): void
    {
        // 1. Create Production Administrator (Primary)
        User::updateOrCreate(
            ['email' => 'admin@cretech.com'],
            [
                'name' => 'Administrator CreTech',
                'password' => Hash::make('admincretech2026'),
                'role' => 'admin',
                'phone' => '081234567890',
            ]
        );

        // 2. Create Fallback Administrator (Compatible with previous credentials)
        User::updateOrCreate(
            ['email' => 'admin@reviewin.test'],
            [
                'name' => 'Administrator CreTech',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081199887766',
            ]
        );
    }
}
