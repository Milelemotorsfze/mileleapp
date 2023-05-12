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
        Schema::create('supplier_addon_temps', function (Blueprint $table) {
            $table->id();
            $table->string('addon_code')->nullable();
            $table->enum('currency', ['AED','USD'])->default('AED')->nullable();
            $table->string('purchase_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_addon_temps');
    }
};
