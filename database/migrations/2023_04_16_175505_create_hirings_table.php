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
        Schema::create('hirings', function (Blueprint $table) {
            $table->id();
            $table->string('job_title')->nullable();
            $table->string('job_details')->nullable();
            $table->string('job_role')->nullable();
            $table->string('job_education')->nullable();
            $table->string('job_experiance')->nullable();
            $table->string('job_skills')->nullable();
            $table->string('job_other')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hirings');
    }
};
