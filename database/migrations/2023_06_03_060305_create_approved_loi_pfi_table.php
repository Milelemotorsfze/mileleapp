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
        Schema::create('approved_loi_pfi', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('approved_loi_item_id')->unsigned()->index()->nullable();
            $table->foreign('approved_loi_item_id')->references('id')->on('approved_letter_of_indent_items');
            $table->integer('quantity')->nullable();
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approved_loi_pfi');
    }
};
