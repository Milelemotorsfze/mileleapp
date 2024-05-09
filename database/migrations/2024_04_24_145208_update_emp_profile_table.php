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
        Schema::table('emp_profile', function (Blueprint $table) {
            $table->string('visa_number')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emp_profile', function (Blueprint $table) {
            $table->integer('visa_number')->nullable()->change();
        });
    }
};
