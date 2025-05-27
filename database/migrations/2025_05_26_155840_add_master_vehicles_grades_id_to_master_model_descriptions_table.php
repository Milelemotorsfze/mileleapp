<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('master_model_descriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('master_model_descriptions', 'master_vehicles_grades_id')) {
                $table->unsignedBigInteger('master_vehicles_grades_id')->nullable()->after('id');
                $table->foreign('master_vehicles_grades_id')
                      ->references('id')
                      ->on('master_vehicles_grades')
                      ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('master_model_descriptions', function (Blueprint $table) {
            if (Schema::hasColumn('master_model_descriptions', 'master_vehicles_grades_id')) {
                $table->dropForeign(['master_vehicles_grades_id']);
                $table->dropColumn('master_vehicles_grades_id');
            }
        });
    }
};
