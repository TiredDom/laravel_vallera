<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@vallera.com'],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@vallera.com',
                'password' => Hash::make('1234567890'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}

