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
        Schema::create('kit_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('addon_details_id')->unsigned()->index()->nullable();
            $table->foreign('addon_details_id')->references('id')->on('addon_details')->onDelete('cascade');
            $table->bigInteger('supplier_addon_id')->unsigned()->index()->nullable();
            $table->foreign('supplier_addon_id')->references('id')->on('supplier_addons')->onDelete('cascade');
            $table->integer('quantity')->nullable();
            $table->decimal('unit_price_in_aed', 10,2)->default('0.00')->nullable();
            $table->decimal('total_price_in_aed', 10,2)->default('0.00')->nullable(); 
            $table->decimal('unit_price_in_usd', 10,2)->default('0.00')->nullable();
            $table->decimal('total_price_in_usd', 10,2)->default('0.00')->nullable();
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
        Schema::dropIfExists('kit_items');
    }
};
