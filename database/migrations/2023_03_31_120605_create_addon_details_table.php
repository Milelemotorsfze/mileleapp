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
            $table->bigInteger('addon_id')->unsigned()->index()->nullable();
            $table->foreign('addon_id')->references('id')->on('addons')->onDelete('cascade');
            $table->string('addon_code')->nullable();
            $table->decimal('purchase_price', 10,2)->nullable();
            $table->decimal('selling_price', 10,2)->nullable();
            $table->string('currency')->nullable();
            $table->integer('lead_time')->nullable();
            $table->string('additional_remarks')->nullable();
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('updated_by')->unsigned()->index()->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('deleted_by')->unsigned()->index()->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('cascade');
            $table->string('image')->nullable();
            $table->string('image2')->nullable();
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
