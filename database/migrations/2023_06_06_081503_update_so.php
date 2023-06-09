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
            $table->date('so-date')->nullable();
            $table->string('so-number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('so', function (Blueprint $table) {
            $table->DropColoumn('so-date');
            $table->DropColoumn('so-number');
        });
    }
};
