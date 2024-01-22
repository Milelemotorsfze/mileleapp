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
        Schema::create('modified_variants', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->bigInteger('addons_id')->unsigned()->index()->nullable();
            $table->foreign('addons_id')->references('id')->on('addons');
            $table->bigInteger('modified_variant_items')->unsigned()->index()->nullable();
            $table->foreign('modified_variant_items')->references('id')->on('variant_items');
            $table->bigInteger('modified_varaint_id')->unsigned()->index()->nullable();
            $table->foreign('modified_varaint_id')->references('id')->on('varaints');
            $table->bigInteger('base_varaint_id')->unsigned()->index()->nullable();
            $table->foreign('base_varaint_id')->references('id')->on('varaints');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modified_variants');
    }
};
