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
        Schema::create('salesperson_clients', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sales_person_id')->unsigned()->index()->nullable();
            $table->foreign('sales_person_id')->references('id')->on('users');
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
        Schema::dropIfExists('salesperson_clients');
    }
};
