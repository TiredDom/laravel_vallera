<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            ProductSeeder::class,
        ]);

        $userName = env('SEED_USER_NAME', 'Test User');
        $userEmail = env('SEED_USER_EMAIL', 'test@example.com');
        $userCount = (int) env('SEED_USER_COUNT', 1);

        \App\Models\User::factory()->count($userCount - 1)->create();
        \App\Models\User::factory()->create([
            'name' => $userName,
            'email' => $userEmail,
        ]);

        $this->call([
            AuditLogSeeder::class,
        ]);
    }
}
