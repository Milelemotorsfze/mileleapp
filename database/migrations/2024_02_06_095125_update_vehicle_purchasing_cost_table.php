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
        Schema::table('vehicle_purchasing_cost', function (Blueprint $table) {
            $table->decimal('unit_price', 10, 2)->after('currency')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_purchasing_cost', function (Blueprint $table) {
            $table->dropColumn('unit_price');
        });
    }
};
