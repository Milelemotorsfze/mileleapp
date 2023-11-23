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
        Schema::create('interview_summary_reports', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('hiring_request_id')->unsigned()->index()->nullable();
            $table->foreign('hiring_request_id')->references('id')->on('employee_hiring_requests')->onDelete('cascade');
            $table->string('candidate_name')->nullable();
            $table->bigInteger('nationality')->unsigned()->index()->nullable();
            $table->foreign('nationality')->references('id')->on('countries')->onDelete('cascade');
            $table->bigInteger('gender')->unsigned()->index()->nullable();
            $table->foreign('gender')->references('id')->on('master_genders')->onDelete('cascade');
            $table->bigInteger('name_of_interviewer')->unsigned()->index()->nullable();
            $table->foreign('name_of_interviewer')->references('id')->on('users')->onDelete('cascade');
            $table->date('date_of_interview')->nullable();
            $table->date('date_of_telephonic_interview')->nullable();
            $table->text('telephonic_interview')->nullable();
            $table->enum('rate_dress_appearance', ['poor','fair','average','good','superior'])->nullable();
            $table->enum('rate_body_language_appearance', ['poor','fair','average','good','superior'])->nullable();
            $table->date('date_of_first_round')->nullable();
            $table->text('first_round')->nullable();
            $table->date('date_of_second_round')->nullable();
            $table->text('second_round')->nullable();
            $table->date('date_of_third_round')->nullable();
            $table->text('third_round')->nullable();
            $table->date('date_of_forth_round')->nullable();
            $table->text('forth_round')->nullable();
            $table->date('date_of_fifth_round')->nullable();
            $table->text('fifth_round')->nullable();
            $table->text('final_evaluation_of_candidate')->nullable();
            $table->enum('candidate_selected', ['yes','no'])->nullable();
            $table->enum('status', ['pending','approved','rejected'])->default('pending')->nullable();

            $table->enum('action_by_department_head', ['pending', 'approved','rejected'])->nullable();
            $table->bigInteger('department_head_id')->unsigned()->index()->nullable();
            $table->foreign('department_head_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('department_head_action_at')->nullable();
            $table->text('comments_by_department_head')->nullable();

            $table->enum('action_by_division_head', ['pending', 'approved','rejected'])->nullable();
            $table->bigInteger('division_head_id')->unsigned()->index()->nullable();
            $table->foreign('division_head_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('division_head_action_at')->nullable();
            $table->text('comments_by_division_head')->nullable();

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
        Schema::dropIfExists('interview_summary_reports');
    }
};
