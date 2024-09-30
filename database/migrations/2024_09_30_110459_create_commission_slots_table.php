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
        Schema::create('commission_slots', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name of the commission slot
            $table->decimal('rate', 8, 2); // Commission rate (e.g. 0.05 for 5%)
            $table->decimal('min_sales', 10, 2)->nullable(); // Minimum sales amount for the commission rate
            $table->decimal('max_sales', 10, 2)->nullable(); // Maximum sales amount for the commission rate
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_slots');
    }
};
