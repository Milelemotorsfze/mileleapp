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
        Schema::table('pdi', function (Blueprint $table) {
            $table->string('reciving')->nullable(); 
            $table->string('reciving_qty')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pdi', function (Blueprint $table) {
            $table->dropColumn('reciving');
            $table->dropColumn('reciving_qty');
        });
    }
};
