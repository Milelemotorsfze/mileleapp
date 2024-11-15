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
        Schema::create('pfi_item_purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pfi_item_id')->unsigned()->index()->nullable();
            $table->foreign('pfi_item_id')->references('id')->on('pfi_items');
            $table->bigInteger('purchase_order_id')->unsigned()->index()->nullable();
            $table->foreign('purchase_order_id')->references('id')->on('purchasing_order');
            $table->bigInteger('master_model_id')->unsigned()->index()->nullable();
            $table->foreign('master_model_id')->references('id')->on('master_models');
            $table->integer('quantity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pfi_item_purchase_orders');
    }
};
