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
        DB::statement("ALTER TABLE work_orders MODIFY lto ENUM('yes', 'no') NULL DEFAULT NULL AFTER so_number");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE work_orders MODIFY lto ENUM('yes', 'no') NOT NULL AFTER so_number");
    }
};
