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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('po_id')->unsigned()->index()->nullable();
            $table->foreign('po_id')->references('id')->on('po');
            $table->bigInteger('so_id')->unsigned()->index()->nullable();
            $table->foreign('so_id')->references('id')->on('so');
            $table->bigInteger('booking_id')->unsigned()->index()->nullable();
            $table->foreign('booking_id')->references('id')->on('booking');
            $table->bigInteger('conversion_id')->unsigned()->index()->nullable();
            $table->foreign('conversion_id')->references('id')->on('conversion');
            $table->bigInteger('grn_id')->unsigned()->index()->nullable();
            $table->foreign('grn_id')->references('id')->on('grn');
            $table->bigInteger('gdn_id')->unsigned()->index()->nullable();
            $table->foreign('gdn_id')->references('id')->on('gdn');
            $table->bigInteger('varaints_id')->unsigned()->index()->nullable();
            $table->foreign('varaints_id')->references('id')->on('varaints');
            $table->string('int_colour');
            $table->string('ex_colour');
            $table->string('max_price');
            $table->string('vin');
            $table->string('engine');
            $table->text('remarks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
