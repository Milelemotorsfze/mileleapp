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
        Schema::create('vehicle_penalties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wo_vehicle_id')->unique();
            $table->date('payment_date');
            $table->integer('excess_days')->nullable();
            $table->decimal('total_penalty_amount', 10, 2)->nullable();
            $table->decimal('amount_paid', 10, 2)->nullable();
            // $table->enum('status', ['No Penalties', 'Penalties', 'Clear'])->nullable();
            $table->string('payment_receipt')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('vehicle_penalties');
    }
};