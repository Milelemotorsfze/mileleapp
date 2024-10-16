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
        Schema::create('wo_veh_del_status', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('w_o_vehicle_id'); // Foreign key from w_o_vehicles
            $table->unsignedBigInteger('user_id'); // Foreign key from users
            $table->enum('status', ['On Hold', 'Ready', 'Delivered','Delivered With Docs Hold'])->default('On Hold'); // Status field
            $table->text('comment')->nullable(); // Comment field
            $table->unsignedBigInteger('location')->nullable(); // Foreign key from master_office_locations table
            $table->text('gdn_number')->nullable(); // Current vehicle location field
            $table->datetime('delivery_at')->nullable(); // Expected completion datetime
            $table->datetime('doc_delivery_date')->nullable(); // Expected completion datetime

            $table->timestamps(); // created_at and updated_at fields

            // Foreign key constraints
            $table->foreign('w_o_vehicle_id')->references('id')->on('w_o_vehicles')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('location')->references('id')->on('master_office_locations')->onDelete('set null'); // Foreign key from master_office_locations

       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wo_veh_del_status');
    }
};
