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
        Schema::create('sales_person_laugauges', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sales_person')->unsigned()->nullable();
            $table->foreign('sales_person')->references('id')->on('users')->onDelete('cascade');
            $table->string('language');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_person_laugauges');
    }
};
