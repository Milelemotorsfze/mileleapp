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
        Schema::table('emp_profile', function (Blueprint $table) {
            $table->string('educational_qualification')->nullable();
            $table->year('year_of_completion')->nullable();
            $table->string('residence_telephone_number')->nullable();
            $table->string('spouse_name')->nullable();
            $table->string('spouse_passport_number')->nullable();
            $table->date('spouse_passport_expiry_date')->nullable();
            $table->date('spouse_dob')->nullable();
            $table->bigInteger('spouse_nationality')->unsigned()->index()->nullable();
            $table->foreign('spouse_nationality')->references('id')->on('countries')->onDelete('cascade');
            $table->datetime('personal_information_created_at')->nullable();
            $table->bigInteger('personal_information_created_by')->unsigned()->index()->nullable();
            $table->foreign('personal_information_created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emp_profile', function (Blueprint $table) {
            $table->dropColumn('educational_qualification');
            $table->dropColumn('year_of_completion');
            $table->dropColumn('residence_telephone_number');
            $table->dropColumn('spouse_name');
            $table->dropColumn('spouse_passport_number');
            $table->dropColumn('spouse_passport_expiry_date');
            $table->dropColumn('spouse_dob');
            $table->dropColumn('spouse_nationality');
            $table->dropColumn('personal_information_created_at');
            $table->dropColumn('personal_information_created_by');
        });
    }
};
