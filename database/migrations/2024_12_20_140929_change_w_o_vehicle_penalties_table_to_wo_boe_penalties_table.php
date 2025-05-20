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
        Schema::rename('vehicle_penalties', 'boe_penalties');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('boe_penalties', 'vehicle_penalties');
    }
};
