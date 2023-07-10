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
        Schema::create('purchasing_order_log', function (Blueprint $table) {
            $table->id();
            $table->string('time')->nullable();
            $table->string('date')->nullable();
            $table->string('role')->nullable();
            $table->string('variant')->nullable();
            $table->string('status')->nullable();
            $table->string('estimation_date')->nullable();
            $table->string('territory')->nullable();
            $table->bigInteger('int_colour')->unsigned()->index()->nullable();
            $table->foreign('int_colour')->references('id')->on('color_codes');
            $table->bigInteger('ex_colour')->unsigned()->index()->nullable();
            $table->foreign('ex_colour')->references('id')->on('color_codes');
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->bigInteger('purchasing_order_id')->unsigned()->index()->nullable();
            $table->foreign('purchasing_order_id')->references('id')->on('purchasing_order');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchasing_order_log');
    }
};
