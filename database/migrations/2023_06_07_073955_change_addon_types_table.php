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
        Schema::table('addon_types', function (Blueprint $table) {
            $table->bigInteger('model_number')->unsigned()->index()->nullable();
            $table->foreign('model_number')->references('id')->on('master_model_descriptions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addon_types', function (Blueprint $table) {
            //
        });
    }
};
