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
        Schema::create('w_o_vehicle_claims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wo_vehicle_id');
            $table->date('claim_date');
            $table->integer('claim_reference_number')->nullable();
            $table->enum('status', ['Submitted', 'Approved', 'Cancelled'])->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            // Foreign key reference 
            $table->foreign('wo_vehicle_id')->references('id')->on('w_o_vehicles')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('w_o_vehicle_claims');
    }
};
