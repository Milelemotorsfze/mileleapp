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
        Schema::table('vendor_payment_adjustments', function (Blueprint $table) {
            $table->decimal('remaining_amount', 15, 1)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_payment_adjustments', function (Blueprint $table) {
            $table->dropColumn('remaining_amount');
        });
    }
};
