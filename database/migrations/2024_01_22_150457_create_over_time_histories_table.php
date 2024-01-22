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
        Schema::create('over_time_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('over_times_id')->unsigned()->index()->nullable();
            $table->foreign('over_times_id')->references('id')->on('over_times')->onDelete('cascade');
            $table->text('icon')->nullable();
            $table->string('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('over_time_histories');
    }
};
