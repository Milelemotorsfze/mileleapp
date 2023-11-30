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
        Schema::create('agents_commission', function (Blueprint $table) {
            $table->id();
            $table->string('commission');
            $table->string('status');
            $table->bigInteger('agents_id')->unsigned()->index()->nullable();
            $table->foreign('agents_id')->references('id')->on('agents');
            $table->bigInteger('quotation_id')->unsigned()->index()->nullable();
            $table->foreign('quotation_id')->references('id')->on('quotations');
            $table->bigInteger('so_id')->unsigned()->index()->nullable();
            $table->foreign('so_id')->references('id')->on('so');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents_commission');
    }
};
