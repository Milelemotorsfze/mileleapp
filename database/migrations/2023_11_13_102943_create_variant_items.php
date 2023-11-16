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
        Schema::create('variant_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('varaint_id')->unsigned()->index()->nullable();
            $table->foreign('varaint_id')->references('id')->on('varaints');
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
        Schema::dropIfExists('variant_items');
    }
};
