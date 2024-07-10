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
        Schema::table('purchased_order_paid_amounts', function (Blueprint $table) {
            // Example: Add a new column
            $table->string('percentage')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchased_order_paid_amounts', function (Blueprint $table) {
            $table->dropColumn('percentage');
        });
    }
};
