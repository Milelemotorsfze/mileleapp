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
        Schema::create('fellowups', function (Blueprint $table) {
            $table->id();
            $table->string('medium')->nullable();
            $table->string('time')->nullable();
            $table->date('date')->nullable();
            $table->bigInteger('calls_id')->unsigned()->index()->nullable();
            $table->foreign('calls_id')->references('id')->on('calls')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fellowups');
    }
};
