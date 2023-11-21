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
        Schema::create('quotation_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('quotation_id')->unsigned()->index()->nullable();
            $table->foreign('quotation_id')->references('id')->on('quotations');
            $table->string('final_destination')->nullable();
            $table->string('incoterm')->nullable();
            $table->string('place_of_delivery')->nullable();
            $table->string('place_of_supply')->nullable();
            $table->string('document_validity')->nullable();
            $table->string('system_code')->nullable();
            $table->string('payment_terms')->nullable();
            $table->double('advance_amount')->nullable();
            $table->string('representative_name')->nullable();
            $table->string('representative_number')->nullable();
            $table->string('cb_name')->nullable();
            $table->string('cb_number')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_details');
    }
};
