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
        Schema::create('supplier_account', function (Blueprint $table) {
            $table->id();
            $table->decimal('opening_balance', 15, 1)->nullable();
            $table->decimal('current_balance', 15, 1)->nullable();
            $table->bigInteger('suppliers_id')->unsigned()->index()->nullable();
            $table->foreign('suppliers_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_account');
    }
};
