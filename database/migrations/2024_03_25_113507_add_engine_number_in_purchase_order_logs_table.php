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
        Schema::table('purchasing_order_log', function (Blueprint $table) {
            $table->string('engine_number')->nullable()->after('estimation_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchasing_order_log', function (Blueprint $table) {
            $table->dropColumn('engine_number');

        });
    }
};
