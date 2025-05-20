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
        Schema::create('movement_grns', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('movement_reference_id')->unsigned()->index()->nullable();
            $table->foreign('movement_reference_id')->references('id')->on('movements_reference');
            $table->bigInteger('purchase_order_id')->unsigned()->index()->nullable();
            $table->foreign('purchase_order_id')->references('id')->on('purchasing_order');
            $table->string('grn_number')->nullable();
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->bigInteger('updated_by')->unsigned()->index()->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movement_grns');
    }
};
