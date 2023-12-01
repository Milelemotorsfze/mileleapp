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
        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->id();
            $table->string('cost_price')->nullable();
            $table->string('selling_price')->nullable();
            $table->string('status')->nullable();
            $table->bigInteger('vendors_id')->unsigned()->index()->nullable();
            $table->foreign('vendors_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->bigInteger('shipping_charges_id')->unsigned()->index()->nullable();
            $table->foreign('shipping_charges_id')->references('id')->on('shipping_charges')->onDelete('cascade');
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('updated_by')->unsigned()->index()->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_rates');
    }
};
