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
        Schema::create('sales_order_history_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sales_order_history_id')->unsigned()->index()->nullable();
            $table->foreign('sales_order_history_id')->references('id')->on('sales_order_histories');
            $table->enum('type', ['Set','Unset','Change'])->nullable();
            $table->string('model_type')->nullable();
            $table->string('field_name')->nullable();
            $table->string('old_value')->nullable();
            $table->string('new_value')->nullable();
            $table->index(['model_type', 'field_name']);
            $table->index(['old_value', 'new_value']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_order_history_details');
    }
};
