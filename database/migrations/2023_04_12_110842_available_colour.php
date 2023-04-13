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
        Schema::create('available_colour', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('varaint_id')->unsigned()->index()->nullable();
            $table->foreign('varaint_id')->references('id')->on('varaints')->onDelete('cascade');
            $table->string('int_colour');
            $table->string('ext_colour');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('available_colour');
    }
};
