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
        Schema::create('so', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('quotation_id')->unsigned()->index()->nullable();
            $table->foreign('quotation_id')->references('id')->on('quotations');
            $table->string('logistics_detail_id');
            $table->bigInteger('sales_person_id')->unsigned()->index()->nullable();
            $table->foreign('sales_person_id')->references('id')->on('users');
            $table->text('notes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('so');
    }
};
