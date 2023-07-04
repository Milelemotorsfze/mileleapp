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
        Schema::create('vehicle_price_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('available_colour_id')->unsigned()->index()->nullable();
            $table->decimal('old_price')->nullable();
            $table->decimal('new_price')->nullable();
            $table->bigInteger('updated_by')->unsigned()->index()->nullable();
            $table->string('status')->comment('New, Updated')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('available_colour_id')->references('id')->on('available_colour');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_price_histories');
    }
};
