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
        Schema::create('soitems', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('so_id')->unsigned()->index()->nullable();
            $table->foreign('so_id')->references('id')->on('so')->onDelete('cascade');
            $table->bigInteger('quotation_items_id')->unsigned()->index()->nullable();
            $table->foreign('quotation_items_id')->references('id')->on('quotation_items')->onDelete('cascade');
            $table->bigInteger('vehicles_id')->unsigned()->index()->nullable();
            $table->foreign('vehicles_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soitems');
    }
};
