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
        Schema::create('addon_details', function (Blueprint $table) {
            $table->id();
            $table->integer('addon_id')->nullable();
            // $table->string('addon_id')->nullable();
            // $table->unsignedBigInteger('addon_id')->nullable();
            // $table->foreign('addon_id')->references('id')->on('addons');
            $table->string('addon_code')->nullable();
            $table->decimal('purchase_price', 10,2)->nullable();
            $table->decimal('selling_price', 10,2)->nullable();
            $table->string('currency')->nullable();
            $table->integer('lead_time')->nullable();
            $table->string('additional_remarks')->nullable();
            // $table->unsignedBigInteger('created_by')->nullable();
            // $table->foreign('created_by')->references('id')->on('users');
            // $table->unsignedBigInteger('updated_by')->nullable();
            // $table->foreign('updated_by')->references('id')->on('users');
            // $table->unsignedBigInteger('deleted_by')->nullable();
            // $table->foreign('deleted_by')->references('id')->on('users');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addon_details');
    }
};
