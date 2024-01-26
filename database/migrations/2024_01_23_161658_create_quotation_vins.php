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
        Schema::create('quotation_vins', function (Blueprint $table) {
            $table->id();
            $table->string('vin')->nullable();
            $table->bigInteger('quotation_items_id')->unsigned()->index()->nullable();
            $table->foreign('quotation_items_id')->references('id')->on('quotation_items')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_vins');
    }
};
