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
        Schema::table('so', function (Blueprint $table) {
            $table->dropColumn('payment_percentage');
            $table->dropColumn('so_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('so', function (Blueprint $table) {
            $table->string('payment_percentage')->nullable();
            $table->string('so_date')->nullable();
        });
    }
};
