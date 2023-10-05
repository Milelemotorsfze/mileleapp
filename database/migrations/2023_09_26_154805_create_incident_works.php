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
        Schema::create('incident_works', function (Blueprint $table) {
            $table->id();
            $table->string('works')->nullable();
            $table->string('status')->nullable();
            $table->string('remarks')->nullable();
            $table->bigInteger('incident_id')->unsigned()->index()->nullable();
            $table->foreign('incident_id')->references('id')->on('incident')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_works');
    }
};
