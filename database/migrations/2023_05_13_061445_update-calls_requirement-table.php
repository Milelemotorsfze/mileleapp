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
        Schema::table('calls_requirement', function (Blueprint $table) {
            $table->bigInteger('model_line_id')->unsigned()->index()->nullable();
            $table->foreign('model_line_id')->references('id')->on('master_model_lines')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calls_requirement', function (Blueprint $table) {
            //
        });
    }
};
