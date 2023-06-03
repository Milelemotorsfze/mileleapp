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
        Schema::create('emp_job', function (Blueprint $table) {
            $table->id();
            $table->string('department');
            $table->string('designation');
            $table->date('joining_date');
            $table->string('employee_type');
            $table->string('report_to');
            $table->string('reported_designation');
            $table->string('office_address');
            $table->bigInteger('emp_profile_id')->unsigned()->index()->nullable();
            $table->foreign('emp_profile_id')->references('id')->on('emp_profile')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_job');
    }
};
