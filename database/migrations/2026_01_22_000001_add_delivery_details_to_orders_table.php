<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('delivery_name')->nullable();
            $table->string('delivery_phone')->nullable();
            $table->text('delivery_address')->nullable();
            $table->string('delivery_city')->nullable();
            $table->string('delivery_province')->nullable();
            $table->string('delivery_postal_code')->nullable();
            $table->text('delivery_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_name',
                'delivery_phone',
                'delivery_address',
                'delivery_city',
                'delivery_province',
                'delivery_postal_code',
                'delivery_notes'
            ]);
        });
    }
};
