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
        Schema::create('supplier_available_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('supplier_id')->unsigned()->index()->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->bigInteger('payment_methods_id')->unsigned()->index()->nullable();
            $table->foreign('payment_methods_id')->references('id')->on('payment_methods')->onDelete('cascade');
            $table->enum('is_primary_payment_method', ['yes','no'])->default('no')->nullable();
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('updated_by')->unsigned()->index()->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('deleted_by')->unsigned()->index()->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_available_payment_methods');
    }
};
