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
        Schema::create('preorder_pos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('purchasing_order_id')->unsigned()->index()->nullable();
            $table->foreign('purchasing_order_id')->references('id')->on('purchasing_order')->onDelete('cascade');
            $table->bigInteger('pre_orders_items_id')->unsigned()->index()->nullable();
            $table->foreign('pre_orders_items_id')->references('id')->on('pre_orders_items')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preorder_pos');
    }
};
