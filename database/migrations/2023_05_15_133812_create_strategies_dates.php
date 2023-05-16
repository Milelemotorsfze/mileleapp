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
        Schema::create('strategies_dates', function (Blueprint $table) {
            $table->id();
            $table->string('cost');
            $table->date('starting_date');
            $table->date('ending_date');
            $table->bigInteger('strategies_id')->unsigned()->index()->nullable();
            $table->timestamps();

            $table->foreign('strategies_id')->references('id')->on('strategies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('strategies_dates');
    }
};
