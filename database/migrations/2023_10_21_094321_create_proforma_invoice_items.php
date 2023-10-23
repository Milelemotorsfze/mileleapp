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
        Schema::create('proforma_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->text('unit_price')->nullable();
            $table->text('qty')->nullable();
            $table->text('total')->nullable();
            $table->bigInteger('proforma_invoice_id')->unsigned()->index()->nullable();
            $table->foreign('proforma_invoice_id')->references('id')->on('proforma_invoice')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proforma_invoice_items');
    }
};
