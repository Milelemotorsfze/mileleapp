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
        Schema::create('addon_images', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('addon_id')->unsigned()->index()->nullable();
            $table->foreign('addon_id')->references('id')->on('addons');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addon_images');
    }
};
