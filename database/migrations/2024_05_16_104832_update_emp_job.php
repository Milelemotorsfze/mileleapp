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
        Schema::table('emp_job', function (Blueprint $table) {
            $table->unsignedBigInteger('department')->nullable()->change();
            $table->unsignedBigInteger('designation')->nullable()->change();
            $table->foreign('department')->references('id')->on('master_departments')->onDelete('cascade');
            $table->foreign('designation')->references('id')->on('master_job_positions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emp_job', function (Blueprint $table) {
            $table->dropForeign(['department']);
            $table->dropForeign(['designation']);
            $table->string('department')->nullable(false)->change();
            $table->string('designation')->nullable(false)->change();
        });
    }
};
