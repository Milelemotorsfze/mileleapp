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
        Schema::create('passport_release_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('passport_release_id')->unsigned()->index()->nullable();
            $table->foreign('passport_release_id')->references('id')->on('passport_releases')->onDelete('cascade');
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
        Schema::dropIfExists('passport_release_histories');
    }
};
