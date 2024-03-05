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
        Schema::create('posting_platforms', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('lead_source_id')->unsigned()->index()->nullable();
            $table->foreign('lead_source_id')->references('id')->on('lead_source')->onDelete('cascade');
            $table->string('videos')->nullable();
            $table->string('reels')->nullable();
            $table->string('Pictures')->nullable();
            $table->string('ads')->nullable();
            $table->string('stories')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posting_platforms');
    }
};
