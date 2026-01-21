<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ActivityLog;
use App\Models\User;

class AuditLogSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::where('email', 'superadmin@vallera.com')->first();

        if (!$superAdmin) {
            return;
        }

        $logs = [
            [
                'user_id' => $superAdmin->id,
                'action' => 'promote_user',
                'model_type' => 'User',
                'model_id' => 2,
                'description' => 'Promoted user to admin',
                'ip_address' => '127.0.0.1',
                'created_at' => now()->subDays(5),
            ],
            [
                'user_id' => $superAdmin->id,
                'action' => 'update_order_status',
                'model_type' => 'Order',
                'model_id' => 1,
                'description' => 'Changed order #1 status from pending to processing',
                'ip_address' => '127.0.0.1',
                'created_at' => now()->subDays(4),
            ],
            [
                'user_id' => $superAdmin->id,
                'action' => 'update_order_status',
                'model_type' => 'Order',
                'model_id' => 1,
                'description' => 'Changed order #1 status from processing to completed',
                'ip_address' => '127.0.0.1',
                'created_at' => now()->subDays(3),
            ],
            [
                'user_id' => null,
                'action' => 'create_order',
                'model_type' => 'Order',
                'model_id' => 2,
                'description' => 'System created order #2 with 3 items, total: ₱125,000',
                'ip_address' => '127.0.0.1',
                'created_at' => now()->subDays(2),
            ],
            [
                'user_id' => $superAdmin->id,
                'action' => 'demote_user',
                'model_type' => 'User',
                'model_id' => 3,
                'description' => 'Demoted user from admin',
                'ip_address' => '127.0.0.1',
                'created_at' => now()->subDay(),
            ],
        ];

        foreach ($logs as $log) {
            ActivityLog::create($log);
        }
    }
}
