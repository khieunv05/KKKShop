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

        $table->string('receiver_name')->nullable();

        $table->string('phone')->nullable();

        $table->decimal(
            'total_price',
            15,
            2
        )->default(0);

    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::table('orders', function (Blueprint $table) {

        $table->dropColumn([
            'receiver_name',
            'phone',
            'total_price'
        ]);

        });
    }   
};
