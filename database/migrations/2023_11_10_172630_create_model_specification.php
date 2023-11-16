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
        Schema::create('model_specification', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->bigInteger('master_model_lines_id')->unsigned()->index()->nullable();
            $table->foreign('master_model_lines_id')->references('id')->on('master_model_lines');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_specification');
    }
};
