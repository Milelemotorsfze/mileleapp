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
        Schema::create('pre_orders_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('countries_id')->unsigned()->index()->nullable();
            $table->foreign('countries_id')->references('id')->on('countries')->onDelete('cascade');
            $table->text('modelyear')->nullable();
            $table->text('qty')->nullable();
            $table->bigInteger('master_model_lines_id')->unsigned()->index()->nullable();
            $table->foreign('master_model_lines_id')->references('id')->on('master_model_lines')->onDelete('cascade');
            $table->bigInteger('ex_colour')->unsigned()->index()->nullable();
            $table->foreign('ex_colour')->references('id')->on('color_codes')->onDelete('cascade');
            $table->bigInteger('int_colour')->unsigned()->index()->nullable();
            $table->foreign('int_colour')->references('id')->on('color_codes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_orders_items');
    }
};
