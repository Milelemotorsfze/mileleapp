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
        Schema::create('addon_descriptions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('addon_id')->unsigned()->index()->nullable();
            $table->foreign('addon_id')->references('id')->on('addons')->onDelete('cascade');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addon_descriptions');
    }
};
