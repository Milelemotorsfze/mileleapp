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
        Schema::create('employee_hiring_requests', function (Blueprint $table) {
            $table->id();
            $table->date('request_date')->nullable();
            $table->bigInteger('department_id')->unsigned()->index()->nullable();
            $table->foreign('department_id')->references('id')->on('master_deparments')->onDelete('cascade');
            $table->bigInteger('location_id')->unsigned()->index()->nullable();
            $table->foreign('location_id')->references('id')->on('master_office_locations')->onDelete('cascade');
            $table->bigInteger('requested_by')->unsigned()->index()->nullable();
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('requested_job_title')->unsigned()->index()->nullable();
            $table->foreign('requested_job_title')->references('id')->on('master_job_positions')->onDelete('cascade');
            $table->bigInteger('reporting_to')->unsigned()->index()->nullable();
            $table->foreign('reporting_to')->references('id')->on('users')->onDelete('cascade');
            $table->integer('number_of_openings')->nullable();
            $table->decimal('salary_range_start_in_aed', 10,2)->default('0.00');
            $table->decimal('salary_range_end_in_aed', 10,2)->default('0.00');
            $table->bigInteger('experience_level')->unsigned()->index()->nullable();
            $table->foreign('experience_level')->references('id')->on('master_experience_levels')->onDelete('cascade');
            $table->time('work_time_start')->nullable();
            $table->time('work_time_end')->nullable();
            $table->enum('type_of_role', ['new_position', 'replacement'])->default('new_position');
            $table->bigInteger('replacement_for_employee')->unsigned()->index()->nullable();
            $table->foreign('replacement_for_employee')->references('id')->on('users')->onDelete('cascade');
            $table->text('explanation_of_new_hiring')->nullable();
            $table->enum('status', ['pending', 'approved','rejected'])->default('pending');

            $table->enum('action_by_hiring_manager', ['pending', 'approved','rejected'])->default('pending');
            $table->bigInteger('hiring_manager_id')->unsigned()->index()->nullable();
            $table->foreign('hiring_manager_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('hiring_manager_action_at')->nullable();
            $table->text('comments_by_hiring_manager')->nullable();

            $table->enum('action_by_department_head', ['pending', 'approved','rejected'])->nullable();
            $table->bigInteger('department_head_id')->unsigned()->index()->nullable();
            $table->foreign('department_head_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('department_head_action_at')->nullable();
            $table->text('comments_by_department_head')->nullable();

            $table->enum('action_by_hr_manager', ['pending', 'approved','rejected'])->nullable();
            $table->bigInteger('hr_manager_id')->unsigned()->index()->nullable();
            $table->foreign('hr_manager_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('hr_manager_action_at')->nullable();
            $table->text('comments_by_hr_manager')->nullable();

            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('updated_by')->unsigned()->index()->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('deleted_by')->unsigned()->index()->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_hiring_requests');
    }
};
