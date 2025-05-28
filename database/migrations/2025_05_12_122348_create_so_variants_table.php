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
        Schema::create('so_variants', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('so_id')->unsigned()->index()->nullable();
            $table->foreign('so_id')->references('id')->on('so');
            $table->bigInteger('variant_id')->unsigned()->index()->nullable();
            $table->foreign('variant_id')->references('id')->on('varaints');
            $table->decimal('price', 10,2)->default('0.00')->nullable();
            $table->string('description')->nullable();
            $table->integer('quantity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('so_variants');
    }
};
