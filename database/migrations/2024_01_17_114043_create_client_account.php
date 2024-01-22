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
        Schema::create('client_account', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('clients_id')->unsigned()->index()->nullable();
            $table->foreign('clients_id')->references('id')->on('clients');
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->string('currency')->nullable();
            $table->string('credit_limit')->nullable();
            $table->string('credit')->nullable();
            $table->string('deposite')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_account');
    }
};
