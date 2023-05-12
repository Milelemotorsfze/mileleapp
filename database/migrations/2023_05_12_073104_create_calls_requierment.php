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
        Schema::create('calls_requirement', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('brand_id')->unsigned()->index()->nullable();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->bigInteger('model_line_id')->unsigned()->index()->nullable();
            $table->foreign('model_line_id')->references('id')->on('master_model_lines')->onDelete('cascade');
            $table->bigInteger('lead_id')->unsigned()->index()->nullable();
            $table->foreign('lead_id')->references('id')->on('calls')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calls_requirement');
    }
};