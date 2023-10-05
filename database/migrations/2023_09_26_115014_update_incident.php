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
        Schema::table('incident', function (Blueprint $table) {
            $table->date('reported_date')->nullable();
            $table->date('repaired_date')->nullable();  
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incident', function (Blueprint $table) {
            $table->dropColumn('reported_date');
            $table->dropColumn('repaired_date');
        });
    }
};
