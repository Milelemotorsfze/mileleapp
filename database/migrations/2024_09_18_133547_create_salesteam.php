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
        Schema::create('salesteam', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('person_id')->unsigned()->index()->nullable();
            $table->foreign('person_id')->references('id')->on('users');
            $table->bigInteger('lead_person_id')->unsigned()->index()->nullable();
            $table->foreign('lead_person_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salesteam');
    }
};
