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
        Schema::create('supplier_account_transaction', function (Blueprint $table) {
            $table->id();
            $table->decimal('totalamount', 15, 1)->nullable();
            $table->decimal('adjustamount', 15, 1)->nullable();
            $table->string('transaction_type')->nullable();
            $table->bigInteger('purchasing_order_id')->unsigned()->index()->nullable();
            $table->foreign('purchasing_order_id')->references('id')->on('purchasing_order')->onDelete('cascade');
            $table->bigInteger('supplier_account_id')->unsigned()->index()->nullable();
            $table->foreign('supplier_account_id')->references('id')->on('supplier_account')->onDelete('cascade');
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_account_transaction');
    }
};
