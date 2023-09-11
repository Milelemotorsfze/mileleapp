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
        Schema::create('booking_extended', function (Blueprint $table) {
            $table->id();
            $table->text('reason')->nullable();
            $table->string('days')->nullable();
            $table->bigInteger('booking_id')->unsigned()->index()->nullable();
            $table->foreign('booking_id')->references('id')->on('booking')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_extended');
    }
};
