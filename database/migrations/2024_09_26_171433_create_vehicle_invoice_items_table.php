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
        Schema::create('vehicle_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->string('qty')->nullable();
            $table->string('rate')->nullable();
            $table->bigInteger('vehicles_id')->unsigned()->index()->nullable();
            $table->foreign('vehicles_id')->references('id')->on('vehicles');
            $table->bigInteger('vehicle_invoice_id')->unsigned()->index()->nullable();
            $table->foreign('vehicle_invoice_id')->references('id')->on('vehicle_invoice');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_invoice_items');
    }
};
