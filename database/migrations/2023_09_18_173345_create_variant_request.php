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
        Schema::create('variant_request', function (Blueprint $table) {
            $table->id();
            $table->string('model_detail')->nullable();
            $table->string('steering')->nullable();
            $table->string('upholestry')->nullable();
            $table->string('seat')->nullable();
            $table->string('detail')->nullable();
            $table->string('my')->nullable();
            $table->string('gearbox')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('name')->nullable();
            $table->string('engine')->nullable();
            $table->string('status')->nullable();
            $table->bigInteger('brands_id')->unsigned()->index()->nullable();
            $table->foreign('brands_id')->references('id')->on('brands')->onDelete('cascade');
            $table->bigInteger('master_model_lines_id')->unsigned()->index()->nullable();
            $table->foreign('master_model_lines_id')->references('id')->on('master_model_lines')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variant_request');
    }
};
