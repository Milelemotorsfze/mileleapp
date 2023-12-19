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
        Schema::table('employee_spoken_languages', function (Blueprint $table) {
            $table->bigInteger('candidate_id')->unsigned()->index()->nullable();
            $table->foreign('candidate_id')->references('id')->on('emp_profile')->onDelete('cascade');
        });
        Schema::table('childrens', function (Blueprint $table) {
            $table->bigInteger('candidate_id')->unsigned()->index()->nullable();
            $table->foreign('candidate_id')->references('id')->on('emp_profile')->onDelete('cascade');
        });
        Schema::table('u_a_e_emergency_contacts', function (Blueprint $table) {
            $table->bigInteger('candidate_id')->unsigned()->index()->nullable();
            $table->foreign('candidate_id')->references('id')->on('emp_profile')->onDelete('cascade');
        });
        Schema::table('home_country_emergency_contacts', function (Blueprint $table) {
            $table->bigInteger('candidate_id')->unsigned()->index()->nullable();
            $table->foreign('candidate_id')->references('id')->on('emp_profile')->onDelete('cascade');
        });
        Schema::table('emp_doc', function (Blueprint $table) {
            $table->bigInteger('candidate_id')->unsigned()->index()->nullable();
            $table->foreign('candidate_id')->references('id')->on('emp_profile')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_spoken_languages', function (Blueprint $table) {
            $table->dropForeign('candidate_id');
                $table->dropIndex('candidate_id');
                $table->dropColumn('candidate_id');
        });
        Schema::table('childrens', function (Blueprint $table) {
            $table->dropForeign('candidate_id');
                $table->dropIndex('candidate_id');
                $table->dropColumn('candidate_id');
        });
        Schema::table('u_a_e_emergency_contacts', function (Blueprint $table) {
            $table->dropForeign('candidate_id');
                $table->dropIndex('candidate_id');
                $table->dropColumn('candidate_id');
        });
        Schema::table('home_country_emergency_contacts', function (Blueprint $table) {
            $table->dropForeign('candidate_id');
                $table->dropIndex('candidate_id');
                $table->dropColumn('candidate_id');
        });
        Schema::table('emp_doc', function (Blueprint $table) {
            $table->dropForeign('candidate_id');
                $table->dropIndex('candidate_id');
                $table->dropColumn('candidate_id');
        });
    }
};
