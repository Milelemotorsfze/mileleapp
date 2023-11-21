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
        Schema::table('variant_request', function (Blueprint $table) {
            $table->bigInteger('int_colour')->unsigned()->index()->nullable();
            $table->foreign('int_colour')->references('id')->on('color_codes')->onDelete('cascade');
            $table->bigInteger('ex_colour')->unsigned()->index()->nullable();
            $table->foreign('ex_colour')->references('id')->on('color_codes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
