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
        Schema::create('bl_form', function (Blueprint $table) {
            $table->id();
            $table->string('bl_number')->unique();
            $table->string('so_number');
            $table->string('no_of_containers');
            $table->string('trackable_web');
            $table->string('looks_genuine');
            $table->string('shipper_details');
            $table->string('so_des_country');
            $table->string('veh_ext_country');
            $table->string('bl_des_country');
            $table->string('port');
            $table->string('bl_date');
            $table->string('realnoreal');
            $table->string('status');
            $table->string('bl_attachment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bl_form');
    }
};
