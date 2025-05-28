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
            if (!Schema::hasColumn('varaints', 'grade_name')) {
                $table->string('grade_name')->nullable();
            }

            if (!Schema::hasColumn('varaints', 'window_type')) {
                $table->string('window_type')->nullable();
            }

            if (!Schema::hasColumn('varaints', 'master_model_descriptions_id')) {
                $table->unsignedBigInteger('master_model_descriptions_id')->nullable();
                $table->foreign('master_model_descriptions_id')
                      ->references('id')
                      ->on('master_model_descriptions')
                      ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('varaints', function (Blueprint $table) {
            if (Schema::hasColumn('varaints', 'master_model_descriptions_id')) {
                $table->dropForeign(['master_model_descriptions_id']);
                $table->dropColumn('master_model_descriptions_id');
            }

            if (Schema::hasColumn('varaints', 'grade_name')) {
                $table->dropColumn('grade_name');
            }

            if (Schema::hasColumn('varaints', 'window_type')) {
                $table->dropColumn('window_type');
            }
        });
    }
};
