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
        Schema::create('separation_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('separations_id')->unsigned()->index()->nullable();
            $table->foreign('separations_id')->references('id')->on('separations')->onDelete('cascade');
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
        Schema::dropIfExists('separation_histories');
    }
};
