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
        Schema::create('vehicle_invoice', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('clients_id')->unsigned()->index()->nullable();
            $table->foreign('clients_id')->references('id')->on('clients');
            $table->bigInteger('so_id')->unsigned()->index()->nullable();
            $table->foreign('so_id')->references('id')->on('so');
            $table->string('invoice_number')->nullable();
            $table->date('date')->nullable();
            $table->bigInteger('pol')->unsigned()->index()->nullable();
            $table->foreign('pol')->references('id')->on('master_shipping_ports');
            $table->bigInteger('pod')->unsigned()->index()->nullable();
            $table->foreign('pod')->references('id')->on('master_shipping_ports');
            $table->string('sub_total')->nullable();
            $table->string('discount')->nullable();
            $table->string('net_amount')->nullable();
            $table->string('vat')->nullable();
            $table->string('shipping_charges')->nullable();
            $table->string('gross_amount')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_invoice');
    }
};
