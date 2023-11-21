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
        Schema::create('variant_request_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('variant_request_id')->unsigned()->index()->nullable();
            $table->foreign('variant_request_id')->references('id')->on('variant_request');
            $table->bigInteger('model_specification_id')->unsigned()->index()->nullable();
            $table->foreign('model_specification_id')->references('id')->on('model_specification');
            $table->bigInteger('model_specification_options_id')->unsigned()->index()->nullable();
            $table->foreign('model_specification_options_id')->references('id')->on('model_specification_options');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variant_request_items');
    }
};
