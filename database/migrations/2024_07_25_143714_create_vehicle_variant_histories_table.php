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
        Schema::create('vehicle_variant_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('varaints_old')->unsigned()->index()->nullable();
            $table->foreign('varaints_old')->references('id')->on('varaints')->onDelete('cascade');
            $table->bigInteger('varaints_new')->unsigned()->index()->nullable();
            $table->foreign('varaints_new')->references('id')->on('supplier_account_transaction')->onDelete('cascade');
            $table->bigInteger('vehicles_id')->unsigned()->index()->nullable();
            $table->foreign('vehicles_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_variant_histories');
    }
};
