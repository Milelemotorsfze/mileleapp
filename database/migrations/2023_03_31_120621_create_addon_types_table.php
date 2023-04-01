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
        Schema::create('addon_types', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('addon_details_id')->nullable();
            // $table->foreign('addon_details_id')->references('id')->on('addon_details');
            // $table->unsignedBigInteger('brand_id')->nullable();
            // $table->foreign('brand_id')->references('id')->on('brands');
            // $table->unsignedBigInteger('model_id')->nullable();
            // $table->foreign('model_id')->references('id')->on('master_models');
            $table->integer('addon_details_id')->nullable();
            $table->integer('brand_id')->nullable();
            $table->integer('model_id')->nullable();
            // $table->unsignedBigInteger('created_by')->nullable();
            // $table->foreign('created_by')->references('id')->on('users');
            // $table->unsignedBigInteger('updated_by')->nullable();
            // $table->foreign('updated_by')->references('id')->on('users');
            // $table->unsignedBigInteger('deleted_by')->nullable();
            // $table->foreign('deleted_by')->references('id')->on('users');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addon_types');
    }
};
