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
        Schema::create('variants_reels', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('available_colour_id')->unsigned()->index()->nullable();
            $table->foreign('available_colour_id')->references('id')->on('available_colour')->onDelete('cascade');
            $table->string('reel_path')->nullable();
            $table->string('video_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variants_reels');
    }
};
