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
        Schema::create('approved_letter_of_indent_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('letter_of_indent_item_id')->unsigned()->index()->nullable();
            $table->foreign('letter_of_indent_item_id')->references('id')->on('letter_of_indent_items');
            $table->integer('quantity');
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
        Schema::dropIfExists('approved_letter_of_indent_items');
    }
};
