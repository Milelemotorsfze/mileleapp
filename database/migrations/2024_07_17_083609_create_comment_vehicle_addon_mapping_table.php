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
        Schema::create('comment_vehicle_addon_mapping', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['store', 'update','delete'])->nullable();
            $table->unsignedBigInteger('comment_vehicle_mapping_id')->nullable(); 
            $table->foreign('comment_vehicle_mapping_id')->references('id')->on('comment_vehicle_mapping')->onDelete('set null');
            $table->unsignedBigInteger('addon_id')->nullable(); 
            $table->foreign('addon_id')->references('id')->on('w_o_vehicle_addons')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_vehicle_addon_mapping');
    }
};
