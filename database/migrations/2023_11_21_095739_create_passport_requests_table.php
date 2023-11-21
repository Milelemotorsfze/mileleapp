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
        Schema::create('passport_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id')->unsigned()->index()->nullable();
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('passport_status', ['with_company', 'with_employee'])->default('with_company');

            $table->bigInteger('purposes_of_submit')->unsigned()->index()->nullable();
            $table->foreign('purposes_of_submit')->references('id')->on('passport_request_purposes')->onDelete('cascade');
            $table->enum('submit_status', ['pending', 'approved','rejected'])->default('pending');

            $table->enum('submit_action_by_employee', ['pending', 'approved','rejected'])->default('pending');
            $table->timestamp('submit_employee_action_at')->nullable();
            $table->text('submit_comments_by_employee')->nullable();

            $table->enum('submit_action_by_department_head', ['pending', 'approved','rejected'])->nullable();
            $table->bigInteger('submit_department_head_id')->unsigned()->index()->nullable();
            $table->foreign('submit_department_head_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('submit_department_head_action_at')->nullable();
            $table->text('submit_comments_by_department_head')->nullable();

            $table->enum('submit_action_by_division_head', ['pending', 'approved','rejected'])->nullable();
            $table->bigInteger('submit_division_head_id')->unsigned()->index()->nullable();
            $table->foreign('submit_division_head_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('submit_division_head_action_at')->nullable();
            $table->text('submit_comments_by_division_head')->nullable();

            $table->enum('submit_action_by_hr_manager', ['pending', 'approved','rejected'])->nullable();
            $table->bigInteger('submit_hr_manager_id')->unsigned()->index()->nullable();
            $table->foreign('submit_hr_manager_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('submit_hr_manager_action_at')->nullable();
            $table->text('submit_comments_by_hr_manager')->nullable();

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
        Schema::dropIfExists('passport_requests');
    }
};
