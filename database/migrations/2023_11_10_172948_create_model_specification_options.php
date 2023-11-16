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
        Schema::create('model_specification_options', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->bigInteger('model_specification_id')->unsigned()->index()->nullable();
            $table->foreign('model_specification_id')->references('id')->on('model_specification');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_specification_options');
    }
};
