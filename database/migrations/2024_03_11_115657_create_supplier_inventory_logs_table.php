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
        Schema::create('supplier_inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('supplier_inventory_id')->unsigned()->index()->nullable();
            $table->foreign('supplier_inventory_id')->references('id')->on('supplier_inventories');
            $table->bigInteger('updated_by')->unsigned()->index()->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->string('action');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_inventory_logs');
    }
};
