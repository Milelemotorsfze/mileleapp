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
        Schema::create('w_o_app_dep_aga_veh', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('w_o_approvals_id')->unsigned()->index()->nullable();
            $table->foreign('w_o_approvals_id')->references('id')->on('w_o_approvals');
            $table->unsignedBigInteger('w_o_vehicle_id')->nullable();
            $table->foreign('w_o_vehicle_id')->references('id')->on('w_o_vehicles')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('w_o_app_dep_aga_veh');
    }
};
