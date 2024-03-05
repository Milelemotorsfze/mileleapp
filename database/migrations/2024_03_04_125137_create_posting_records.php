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
        Schema::create('posting_records', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('posting_platforms_id')->unsigned()->index()->nullable();
            $table->foreign('posting_platforms_id')->references('id')->on('posting_platforms')->onDelete('cascade');
            $table->bigInteger('varaints_id')->unsigned()->index()->nullable();
            $table->foreign('varaints_id')->references('id')->on('varaints')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posting_records');
    }
};
