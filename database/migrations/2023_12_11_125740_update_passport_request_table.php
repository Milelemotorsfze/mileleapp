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
        Schema::table('passport_requests', function (Blueprint $table) {
            $table->dropColumn(['passport_status']);
            $table->dropForeign(['purposes_of_release']);
            $table->dropColumn('purposes_of_release');
            $table->dropColumn('release_purpose');
            $table->dropColumn('release_submit_status');
            $table->dropColumn('release_action_by_employee');
            $table->dropColumn('release_employee_action_at');
            $table->dropColumn('release_comments_by_employee');
            $table->dropColumn('release_action_by_department_head');
            $table->dropForeign(['release_department_head_id']);
            $table->dropColumn('release_department_head_id');
            $table->dropColumn('release_department_head_action_at');
            $table->dropColumn('release_comments_by_department_head');

            $table->dropColumn('release_action_by_division_head');
            $table->dropForeign(['release_division_head_id']);
            $table->dropColumn('release_division_head_id');
            $table->dropColumn('release_division_head_action_at');
            $table->dropColumn('release_comments_by_division_head');

            $table->dropColumn('release_action_by_hr_manager');
            $table->dropForeign(['release_hr_manager_id']);
            $table->dropColumn('release_hr_manager_id');
            $table->dropColumn('release_hr_manager_action_at');
            $table->dropColumn('release_comments_by_hr_manager');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('passport_requests', function (Blueprint $table) {
            $table->enum('passport_status', ['with_company', 'with_employee'])->default('with_company');
            $table->bigInteger('purposes_of_release')->unsigned()->index()->nullable();
            $table->foreign('purposes_of_release')->references('id')->on('passport_request_purposes')->onDelete('cascade');
            $table->string('release_purpose')->nullable();
            $table->enum('release_submit_status', ['pending', 'approved','rejected'])->nullable();

            $table->enum('release_action_by_employee', ['pending', 'approved','rejected'])->nullable();
            $table->timestamp('release_employee_action_at')->nullable();
            $table->text('release_comments_by_employee')->nullable();

            $table->enum('release_action_by_department_head', ['pending', 'approved','rejected'])->nullable();
            $table->bigInteger('release_department_head_id')->unsigned()->index()->nullable();
            $table->foreign('release_department_head_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('release_department_head_action_at')->nullable();
            $table->text('release_comments_by_department_head')->nullable();

            $table->enum('release_action_by_division_head', ['pending', 'approved','rejected'])->nullable();
            $table->bigInteger('release_division_head_id')->unsigned()->index()->nullable();
            $table->foreign('release_division_head_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('release_division_head_action_at')->nullable();
            $table->text('release_comments_by_division_head')->nullable();

            $table->enum('release_action_by_hr_manager', ['pending', 'approved','rejected'])->nullable();
            $table->bigInteger('release_hr_manager_id')->unsigned()->index()->nullable();
            $table->foreign('release_hr_manager_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('release_hr_manager_action_at')->nullable();
            $table->text('release_comments_by_hr_manager')->nullable();
        });
    }
};
