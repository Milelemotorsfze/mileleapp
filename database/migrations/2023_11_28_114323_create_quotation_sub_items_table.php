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
        Schema::create('quotation_sub_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('quotation_item_parent_id')->unsigned()->index()->nullable();
            $table->foreign('quotation_item_parent_id')->references('id')->on('quotation_items');
            $table->bigInteger('quotation_item_id')->unsigned()->index()->nullable();
            $table->foreign('quotation_item_id')->references('id')->on('quotation_items');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_sub_items');
    }
};
