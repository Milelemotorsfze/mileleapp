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
        Schema::create('pfi_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pfi_id')->unsigned()->index()->nullable();
            $table->foreign('pfi_id')->references('id')->on('pfi');
            $table->bigInteger('loi_item_id')->unsigned()->index()->nullable();
            $table->foreign('loi_item_id')->references('id')->on('letter_of_indent_items');
            $table->bigInteger('master_model_id')->unsigned()->index()->nullable();
            $table->foreign('master_model_id')->references('id')->on('master_models');
            $table->integer('pfi_quantity');
            $table->decimal('unit_price', 10,2)->default('0.00');
            $table->foreign('created_by')->references('id')->on('users');
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->timestamps();
            $table->softDeletes();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pfi_items');
    }
};
