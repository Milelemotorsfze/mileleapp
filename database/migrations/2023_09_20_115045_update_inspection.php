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
        Schema::table('inspection', function (Blueprint $table) {
            $table->date('processing_date')->nullable();
            $table->string('process_remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspection', function (Blueprint $table) {
            $table->dropColumn('processing_date');
            $table->dropColumn('process_remarks');
        });
    }
};
