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
        Schema::create('vehicle_netsuite_cost', function (Blueprint $table) {
            $table->id();
            $table->string('cost')->nullable();
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->bigInteger('vehicles_id')->unsigned()->index()->nullable();
            $table->foreign('vehicles_id')->references('id')->on('vehicles');
            $table->string('netsuite_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_netsuite_cost');
    }
};
