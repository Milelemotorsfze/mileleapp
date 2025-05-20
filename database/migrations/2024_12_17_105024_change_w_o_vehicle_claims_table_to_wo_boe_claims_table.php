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
        Schema::rename('w_o_vehicle_claims', 'wo_boe_claims');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('wo_boe_claims', 'w_o_vehicle_claims');
    }
};
