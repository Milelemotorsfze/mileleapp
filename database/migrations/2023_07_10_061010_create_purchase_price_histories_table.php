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
        Schema::create('purchase_price_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('supplier_addon_id')->unsigned()->index()->nullable();
            $table->foreign('supplier_addon_id')->references('id')->on('supplier_addons')->onDelete('cascade');
            $table->decimal('purchase_price_aed', 10,2)->default('0.00');
            $table->decimal('purchase_price_usd', 10,2)->default('0.00');
            $table->enum('status', ['active','inactive']);
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
        Schema::dropIfExists('purchase_price_histories');
    }
   

    
};
