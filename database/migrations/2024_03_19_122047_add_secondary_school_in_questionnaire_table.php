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
        Schema::table('employee_hiring_questionnaires', function (Blueprint $table) {
            $table->dropColumn('education');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_hiring_questionnaires', function (Blueprint $table) {
            $table->enum('education', ['high_school', 'bachelors','pg_in_same_specialisation_or_related_to_department'])->after('work_time_end')->nullable(); 
        });
    }
};
