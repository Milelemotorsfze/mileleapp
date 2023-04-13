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
        Schema::table('varaints', function (Blueprint $table) {
            $table->dropColumn('model_line');
            $table->bigInteger('master_model_lines_id')->unsigned()->index()->nullable();
            $table->foreign('master_model_lines_id')->references('id')->on('master_model_lines')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('varaints', function (Blueprint $table) {
            $table->dropColumn('master_model_lines_id');
        });
    }
};
