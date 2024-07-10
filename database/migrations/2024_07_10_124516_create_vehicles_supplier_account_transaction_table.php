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
        Schema::create('vehicles_supplier_account_transaction', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vehicles_id')->unsigned()->index()->nullable();
            $table->foreign('vehicles_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->bigInteger('sat_id')->unsigned()->index()->nullable();
            $table->foreign('sat_id')->references('id')->on('supplier_account_transaction')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles_supplier_account_transaction');
    }
};
