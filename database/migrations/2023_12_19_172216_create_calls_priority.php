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
        Schema::create('calls_priority', function (Blueprint $table) {
            $table->id();
            $table->string('priority')->nullable();
            $table->bigInteger('lead_source_id')->unsigned()->index()->nullable();
            $table->foreign('lead_source_id')->references('id')->on('lead_source')->onDelete('cascade');
            $table->bigInteger('set_by_id')->unsigned()->index()->nullable();
            $table->foreign('set_by_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calls_priority');
    }
};
