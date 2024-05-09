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
        Schema::create('muitlple_agents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('quotations_id')->unsigned()->index()->nullable();
            $table->foreign('quotations_id')->references('id')->on('quotations')->onDelete('cascade');
            $table->bigInteger('agents_id')->unsigned()->index()->nullable();
            $table->foreign('agents_id')->references('id')->on('agents')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('muitlple_agents');
    }
};
