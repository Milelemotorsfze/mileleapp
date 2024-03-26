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
        Schema::table('posting_records', function (Blueprint $table) {
            $table->bigInteger('int_colour')->unsigned()->index()->nullable();
            $table->foreign('int_colour')->references('id')->on('color_codes')->onDelete('cascade');
            $table->bigInteger('ext_colour')->unsigned()->index()->nullable();
            $table->foreign('ext_colour')->references('id')->on('color_codes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posting_records', function (Blueprint $table) {
            // Drop the foreign key constraints
            $table->dropForeign(['int_colour']);
            $table->dropForeign(['ext_colour']);
            
            // Drop the columns
            $table->dropColumn('int_colour');
            $table->dropColumn('ext_colour');
        });
    }
};
