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
        Schema::create('salary_certificates', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name');
            $table->string('branch_name');
            $table->string('country_name');
            $table->string('passport_number');
            $table->string('salary_certficate_request_detail');
            $table->string('issued_by');
          
            $table->enum('company_branch', ['milele_motors_fze', 'milele_fze', 'miele_auto_fze', 'milele_cars_trading_llc', 'milele_car_rental_llc', 'trans_car_fze'])->nullable();
            $table->decimal('salary_in_aed', 8, 2);
            $table->unsignedBigInteger('requested_job_title')->nullable();
            $table->unsignedBigInteger('requested_for')->nullable();
            $table->string('purpose_of_request');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('requested_by')->nullable();
            $table->text('comments')->nullable();
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('requested_job_title')->references('id')->on('master_job_positions')->onDelete('set null');
            $table->date('joining_date')->nullable();
            $table->date('creation_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_certificates');
    }
};
