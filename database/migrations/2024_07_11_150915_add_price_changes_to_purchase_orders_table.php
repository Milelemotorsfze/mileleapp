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
        Schema::table('purchased_order_price_changes', function (Blueprint $table) {
            $table->string('status')->nullable()->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchased_order_price_changes', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
