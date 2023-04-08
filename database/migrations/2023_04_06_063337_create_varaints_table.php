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
        Schema::create('varaints', function (Blueprint $table) {
            $table->id();
            $table->string('model');
            $table->string('sfx'); 
            $table->string('name');
            $table->string('engine')->nullable();
            $table->string('number_of_cylinders')->nullable();
            $table->string('engine_type')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('displacement')->nullable();
            $table->string('max_power_kw')->nullable();
            $table->string('max_power_hp')->nullable();
            $table->string('max_torque_mn')->nullable();
            $table->string('body_style')->nullable();
            $table->string('number_of_doors')->nullable();
            $table->string('ground_clearance')->nullable();
            $table->string('wheelbase')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('transmission')->nullable();
            $table->string('gearbox')->nullable();
            $table->string('front_differential')->nullable();
            $table->string('rear_differential')->nullable();
            $table->string('fuel_tank_capacity')->nullable();
            $table->string('additional_fuel_tank_capacity')->nullable();
            $table->string('curb_weight')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('varaints');
    }
};
