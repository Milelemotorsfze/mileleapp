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
        Schema::create('vehicles_remarks', function (Blueprint $table) {
            $table->id();
            $table->text('remarks');
            $table->string('time')->nullable();
            $table->string('date')->nullable();
            $table->string('department')->nullable();
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->bigInteger('vehicles_id')->unsigned()->index()->nullable();
            $table->foreign('vehicles_id')->references('id')->on('vehicles');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles_remarks');
    }
};
