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
        Schema::create('client_leads', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('calls_id')->unsigned()->index()->nullable();
            $table->foreign('calls_id')->references('id')->on('calls');
            $table->bigInteger('clients_id')->unsigned()->index()->nullable();
            $table->foreign('clients_id')->references('id')->on('clients');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_leads');
    }
};
