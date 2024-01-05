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
        Schema::create('lead_customers', function (Blueprint $table) {
            $table->id();
            $table->string('tradelicense')->nullable();
            $table->string('tender')->nullable();
            $table->string('passport')->nullable();
            $table->string('countryofexport')->nullable();
            $table->bigInteger('calls_id')->unsigned()->index()->nullable();
            $table->foreign('calls_id')->references('id')->on('calls');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_customers');
    }
};
