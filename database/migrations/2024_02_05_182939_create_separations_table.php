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
        Schema::create('separations', function (Blueprint $table) {
            $table->id();
            $table->enum('status',['pending','approved','rejected'])->default('pending');
            $table->date('last_working_date')->nullable();
            $table->bigInteger('separation_type')->unsigned()->index()->nullable();
            $table->foreign('separation_type')->references('id')->on('seperation_types');
            $table->string('seperation_type_other')->nullable();
            $table->bigInteger('replacement')->unsigned()->index()->nullable();
            $table->foreign('replacement')->references('id')->on('separation_replacement_types');
            $table->string('replacement_other')->nullable();
            $table->text('jd_of_separation_employee')->nullable();
            $table->text('handover_tasks_description')->nullable();
           
            $table->bigInteger('employee_id')->unsigned()->index()->nullable();
            $table->foreign('employee_id')->references('id')->on('users');
            $table->enum('action_by_employee', ['pending', 'approved','rejected'])->default('pending')->nullable();
            $table->timestamp('employee_action_at')->nullable();
            $table->text('comments_by_employee')->nullable();

            $table->bigInteger('takeover_employee_id')->unsigned()->index()->nullable();
            $table->foreign('takeover_employee_id')->references('id')->on('users');
            $table->enum('employment_type',['new_hire_under_probation','existing_staff'])->nullable();
            $table->enum('action_by_takeover_employee', ['pending', 'approved','rejected'])->default('pending')->nullable();
            $table->timestamp('takeover_employee_action_at')->nullable();
            $table->text('comments_by_takeover_employee')->nullable();

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
            $table->timestamp('jd_verified_at')->nullable();
            $table->timestamp('tasks_verified_at')->nullable();
            $table->timestamp('sign_verified_at')->nullable();

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
        Schema::dropIfExists('separations');
    }
};
