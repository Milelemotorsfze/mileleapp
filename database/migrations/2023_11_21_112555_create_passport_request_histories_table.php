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
        Schema::create('passport_request_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('passport_request_id')->unsigned()->index()->nullable();
            $table->foreign('passport_request_id')->references('id')->on('passport_requests')->onDelete('cascade');
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
        Schema::dropIfExists('passport_request_histories');
    }
};
