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
        Schema::create('client_account_transition', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_account_id')->unsigned()->index()->nullable();
            $table->foreign('client_account_id')->references('id')->on('client_account');
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->string('transition_type')->nullable();
            $table->string('amount')->nullable();
            $table->string('currency')->nullable();
            $table->string('file_path')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_account_transition');
    }
};
