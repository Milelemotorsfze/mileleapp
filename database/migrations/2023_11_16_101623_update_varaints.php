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
        Schema::table('varaints', function (Blueprint $table) {
            $table->dropColumn('number_of_cylinders');
            $table->dropColumn('engine_type');
            $table->dropColumn('displacement');
            $table->dropColumn('max_power_kw');
            $table->dropColumn('max_power_hp');
            $table->dropColumn('max_torque_mn');
            $table->dropColumn('body_style');
            $table->dropColumn('number_of_doors');
            $table->dropColumn('ground_clearance');
            $table->dropColumn('wheelbase');
            $table->dropColumn('dimensions');
            $table->dropColumn('transmission');
            $table->dropColumn('front_differential');
            $table->dropColumn('rear_differential');
            $table->dropColumn('fuel_tank_capacity');
            $table->dropColumn('additional_fuel_tank_capacity');
            $table->dropColumn('curb_weight');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('varaints', function (Blueprint $table) {
            $table->string('number_of_cylinders')->nullable();
            $table->string('engine_type')->nullable();
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
            $table->string('front_differential')->nullable();
            $table->string('rear_differential')->nullable();
            $table->string('fuel_tank_capacity')->nullable();
            $table->string('additional_fuel_tank_capacity')->nullable();
            $table->string('curb_weight')->nullable();
        });
    }
};
