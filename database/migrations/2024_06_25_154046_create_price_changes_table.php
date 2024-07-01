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
        Schema::create('purchased_order_price_changes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('purchasing_order_id')->unsigned()->index()->nullable();
            $table->foreign('purchasing_order_id')->references('id')->on('purchasing_order')->onDelete('cascade');
            $table->bigInteger('vehicles_id')->unsigned()->index()->nullable();
            $table->foreign('vehicles_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->decimal('original_price', 10, 2);
            $table->decimal('new_price', 10, 2);
            $table->decimal('price_change', 10, 2);
            $table->enum('change_type', ['Surcharge', 'discount']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchased_order_price_changes');
    }
};
