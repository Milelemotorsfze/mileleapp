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
        Schema::create('wo_status', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('wo_id'); // Foreign key referencing work_orders table
            $table->unsignedBigInteger('status_changed_by'); // Foreign key referencing users table
            
            // Status field with default value of 'Active' and 'On Hold' as an option
            $table->enum('status', ['Active', 'On Hold'])->default('Active');
            
            // Optional comment field
            $table->text('comment')->nullable();

            // Timestamps
            $table->timestamp('status_changed_at')->nullable();
            $table->timestamps();

            // Foreign key constraint to work_orders table
            $table->foreign('wo_id')->references('id')->on('work_orders')->onDelete('cascade');

            // Foreign key constraint to users table
            $table->foreign('status_changed_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wo_status');
    }
};
