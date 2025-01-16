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
        Schema::table('master_model_descriptions', function (Blueprint $table) {
        $table->unsignedBigInteger('master_vehicles_grades_id')->nullable();
        $table->foreign('master_vehicles_grades_id')->references('id')->on('master_vehicles_grades')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_model_descriptions', function (Blueprint $table) {
            $table->dropForeign(['master_vehicles_grades_id']);
            $table->dropColumn('master_vehicles_grades_id');
        });
    }
};
