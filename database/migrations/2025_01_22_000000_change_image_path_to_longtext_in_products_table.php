<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check the database driver
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            // SQLite doesn't support MODIFY COLUMN directly and TEXT can hold arbitrary length
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            // For MySQL/Postgres, we want to ensure it's LONGTEXT to hold Base64
            // We use raw SQL for MySQL to force LONGTEXT
            DB::statement('ALTER TABLE products MODIFY image_path LONGTEXT NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            DB::statement('ALTER TABLE products MODIFY image_path VARCHAR(255) NULL');
        });
    }
};
