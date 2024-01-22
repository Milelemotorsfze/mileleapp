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
        Schema::create('over_time_date_times', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('over_times_id')->unsigned()->index()->nullable();
            $table->foreign('over_times_id')->references('id')->on('over_times');
            $table->datetime('start_datetime')->nullable();
            $table->datetime('end_datetime')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('over_time_date_times');
    }
};
