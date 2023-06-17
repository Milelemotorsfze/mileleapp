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
        Schema::create('warranty_selling_price_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('warranty_brand_id')->unsigned()->index()->nullable();
            $table->decimal('old_price')->nullable();
            $table->decimal('updated_price')->nullable();
            $table->bigInteger('updated_by')->unsigned()->index()->nullable();
            $table->bigInteger('status_updated_by')->unsigned()->index()->nullable();

            $table->bigInteger('deleted_by')->unsigned()->index()->nullable();
            $table->enum('status',['rejected','approved', 'pending'])->nullable();
            $table->foreign('warranty_brand_id')->references('id')->on('warranty_brands');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->foreign('status_updated_by')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warranty_selling_price_histories');
    }
};
