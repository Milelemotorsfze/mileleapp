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
        Schema::create('loi_item_purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('approved_loi_id')->unsigned()->index()->nullable();
            $table->foreign('approved_loi_id')->references('id')->on('approved_letter_of_indent_items');
            $table->bigInteger('purchase_order_id')->unsigned()->index()->nullable();
            $table->foreign('purchase_order_id')->references('id')->on('purchase_order');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loi_item_purchase_orders');
    }
};
