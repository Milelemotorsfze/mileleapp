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
        Schema::create('wo_vehicle_pdi_status', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('w_o_vehicle_id'); // Foreign key from w_o_vehicles
            $table->unsignedBigInteger('user_id'); // Foreign key from users
            $table->enum('status', ['Not Initiated', 'Scheduled', 'Completed'])->default('Not Initiated'); // Status field
            $table->text('comment')->nullable(); // Comment field
            $table->datetime('pdi_scheduled_at')->nullable(); // Expected completion datetime
            $table->enum('passed_status', ['Passed', 'Failed'])->nullable();

            $table->text('qc_inspector_remarks')->nullable(); // Current vehicle location field
            $table->timestamps(); // created_at and updated_at fields

            // Foreign key constraints
            $table->foreign('w_o_vehicle_id')->references('id')->on('w_o_vehicles')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wo_vehicle_pdi_status');
    }
};
