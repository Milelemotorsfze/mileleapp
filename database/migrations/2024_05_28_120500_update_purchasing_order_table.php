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
        Schema::table('purchasing_order', function (Blueprint $table) {
            $table->text('remarks')->nullable(); // Add a new column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchasing_order', function (Blueprint $table) {
            $table->dropColumn('remarks'); // Drop the column if rolled back
        });
    }
};
