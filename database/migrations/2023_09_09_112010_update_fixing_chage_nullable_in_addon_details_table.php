<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `addon_details` CHANGE `fixing_charges_included` `fixing_charges_included` ENUM('yes','no') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL;");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
