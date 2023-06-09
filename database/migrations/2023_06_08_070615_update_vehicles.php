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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('territory')->nullable();
            $table->string('documzinout')->nullable();
            $table->bigInteger('bl_id')->unsigned()->index()->nullable();
            $table->foreign('bl_id')->references('id')->on('bl')->onDelete('cascade'); 
            $table->bigInteger('documents_id')->unsigned()->index()->nullable();
            $table->foreign('documents_id')->references('id')->on('documents')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->DropColoumn('territory');
            $table->DropColoumn('documzinout');
        });
    }
};
