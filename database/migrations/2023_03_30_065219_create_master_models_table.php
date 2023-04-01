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
        Schema::create('master_models', function (Blueprint $table) {
            $table->id();
            $table->string('steering')->nullable();
            $table->string('model')->nullable();
            $table->string('sfx')->nullable();
            $table->string('amount_uae')->nullable();
            $table->string('amount_belgium')->nullable();
            // $table->foreign('created_by')->references('id')->on('users');
            // $table->unsignedBigInteger('updated_by')->nullable();
            // $table->foreign('updated_by')->references('id')->on('users');
            // $table->unsignedBigInteger('deleted_by')->nullable();
            // $table->foreign('deleted_by')->references('id')->on('users');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_models');
    }
};
