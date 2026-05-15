<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::firstOrCreate(
            ['email' => 'kingalameen@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('king:0000'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
