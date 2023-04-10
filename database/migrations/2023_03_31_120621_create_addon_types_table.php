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
            $table->bigInteger('addon_details_id')->unsigned()->index()->nullable();
            $table->foreign('addon_details_id')->references('id')->on('addon_details')->onDelete('cascade');
            // $table->unsignedBigInteger('brand_id')->nullable();
            // $table->foreign('brand_id')->references('id')->on('brands');
            // $table->unsignedBigInteger('model_id')->nullable();
            // $table->foreign('model_id')->references('id')->on('master_models');
            // $table->integer('brand_id')->nullable();
            // $table->integer('model_id')->nullable();
            $table->string('brand_id')->nullable();
            $table->string('model_id')->nullable();
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('updated_by')->unsigned()->index()->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('deleted_by')->unsigned()->index()->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('cascade');
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
