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
        Schema::create('comment_vehicle_mapping', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['store', 'update'])->nullable();
            $table->unsignedBigInteger('comment_id')->nullable(); 
            $table->foreign('comment_id')->references('id')->on('w_o_comments')->onDelete('set null');
            $table->unsignedBigInteger('wo_id')->nullable(); 
            $table->foreign('wo_id')->references('id')->on('work_orders')->onDelete('set null');
            $table->unsignedBigInteger('vehicle_id')->nullable(); 
            $table->foreign('vehicle_id')->references('id')->on('w_o_vehicles')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_vehicle_mapping');
    }
};
