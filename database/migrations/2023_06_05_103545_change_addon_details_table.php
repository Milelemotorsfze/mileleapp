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
        Schema::table('addon_details', function (Blueprint $table) {
           $table->decimal('selling_price', 10,2)->default('0.00')->nullable()->change();
           $table->decimal('fixing_charge_amount', 10,2)->default('0.00')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addon_details', function (Blueprint $table) {
            //
        });
    }
};
