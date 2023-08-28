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
            // $table->dropColumn('model_year_start');
            // $table->dropColumn('model_year_end');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addon_details', function (Blueprint $table) {
            // $table->year('model_year_start')->nullable();
            // $table->year('model_year_end')->nullable();
        });
    }
};
