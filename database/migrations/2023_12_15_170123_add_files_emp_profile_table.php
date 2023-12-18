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
            $table->string('resume')->nullable();
            $table->string('visa')->nullable();
            $table->string('emirates_id_file')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emp_profile', function (Blueprint $table) {
            $table->dropColumn('resume');
            $table->dropColumn('visa');
            $table->dropColumn('emirates_id_file');
        });
    }
};
