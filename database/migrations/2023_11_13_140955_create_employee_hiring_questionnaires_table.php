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
        Schema::create('employee_hiring_questionnaires', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('hiring_request_id')->unsigned()->index()->nullable();
            $table->foreign('hiring_request_id')->references('id')->on('employee_hiring_requests')->onDelete('cascade');
            $table->enum('designation_type', ['prior_designation', 'current_designation'])->nullable();
            $table->bigInteger('designation_id')->unsigned()->index()->nullable();
            $table->foreign('designation_id')->references('id')->on('master_job_positions')->onDelete('cascade');
            $table->integer('no_of_years_of_experience_in_specific_job_role')->nullable();
            $table->enum('reporting_structure', ['management', 'manager','team_lead'])->nullable();
            $table->bigInteger('location_id')->unsigned()->index()->nullable();
            $table->foreign('location_id')->references('id')->on('master_office_locations')->onDelete('cascade');
            $table->integer('number_of_openings')->nullable();
            $table->enum('hiring_time', ['immediate', 'one_month'])->nullable();
            $table->time('work_time_start')->nullable();
            $table->time('work_time_end')->nullable();
            $table->enum('education', ['high_school', 'bachelors','pg_in_same_specialisation_or_related_to_department'])->nullable();
            $table->string('education_certificates')->nullable();
            $table->string('certification')->nullable();
            $table->bigInteger('industry_experience_id')->unsigned()->index()->nullable();
            $table->foreign('industry_experience_id')->references('id')->on('master_specific_industry_experiences')->onDelete('cascade');
            $table->string('specific_company_experience')->nullable();
            $table->decimal('salary_range_start_in_aed', 10,2)->default('0.00');
            $table->decimal('salary_range_end_in_aed', 10,2)->default('0.00');
            $table->bigInteger('visa_type')->unsigned()->index()->nullable();
            $table->foreign('visa_type')->references('id')->on('master_visa_types')->onDelete('cascade');
            $table->bigInteger('nationality')->unsigned()->index()->nullable();
            $table->foreign('nationality')->references('id')->on('countries')->onDelete('cascade');
            $table->integer('min_age')->nullable();
            $table->integer('max_age')->nullable();
            $table->enum('required_to_travel_for_work_purpose', ['yes', 'no'])->nullable();
            $table->enum('requires_multiple_industry_experience', ['yes', 'no'])->nullable();
            $table->enum('team_handling_experience_required', ['yes', 'no'])->nullable();
            $table->enum('driving_licence', ['yes', 'no'])->nullable();
            $table->enum('own_car', ['yes', 'no'])->nullable();
            $table->enum('fuel_expenses_by', ['company', 'own'])->nullable();
            $table->enum('required_to_work_on_trial', ['yes', 'no'])->nullable();
            $table->integer('number_of_trial_days')->nullable();
            $table->enum('commission_involved_in_salary', ['yes', 'no'])->nullable();
            $table->enum('commission_type', ['amount', 'percentage'])->nullable();
            $table->decimal('commission_amount', 10,2)->default('0.00');
            $table->integer('commission_percentage')->nullable();
            $table->text('mandatory_skills')->nullable();
            $table->bigInteger('interviewd_by')->unsigned()->index()->nullable();
            $table->foreign('interviewd_by')->references('id')->on('users')->onDelete('cascade');
            $table->text('job_opening_purpose_objective')->nullable();
            $table->text('screening_questions')->nullable();
            $table->text('technical_test')->nullable();
            $table->text('trial_work_job_description')->nullable();
            $table->enum('internal_department_evaluation', ['yes', 'no'])->nullable();
            $table->enum('external_vendor_evaluation', ['yes', 'no'])->nullable();
            $table->bigInteger('recruitment_source_id')->unsigned()->index()->nullable();
            $table->foreign('recruitment_source_id')->references('id')->on('masters_recuritment_sources')->onDelete('cascade');
            $table->enum('experience', ['local', 'international','home_country'])->nullable();
            $table->enum('travel_experience', ['yes', 'no'])->nullable();
            $table->bigInteger('department_id')->unsigned()->index()->nullable();
            $table->foreign('department_id')->references('id')->on('master_deparments')->onDelete('cascade');
            $table->bigInteger('career_level_id')->unsigned()->index()->nullable();
            $table->foreign('career_level_id')->references('id')->on('master_experience_levels')->onDelete('cascade');
            $table->integer('current_or_past_employer_size_start')->nullable();
            $table->integer('current_or_past_employer_size_end')->nullable();
            $table->decimal('trial_pay_in_aed', 10,2)->default('0.00');
            $table->enum('out_of_office_visit', ['yes', 'no'])->nullable();
            $table->enum('remote_work', ['yes', 'no'])->nullable();
            $table->enum('international_business_trip_required', ['yes', 'no'])->nullable();
            $table->integer('probation_length_in_months')->nullable();
            $table->decimal('probation_pay_amount_in_aed', 10,2)->default('0.00');
            $table->text('incentives_perks_bonus')->nullable();
            $table->string('kpi')->nullable();
            $table->string('practical_test')->nullable();
            $table->string('trial_objectives_and_evaluation_method')->nullable();
            $table->string('duties_and_tasks')->nullable();
            $table->bigInteger('next_career_path_id')->unsigned()->index()->nullable();
            $table->foreign('next_career_path_id')->references('id')->on('master_experience_levels')->onDelete('cascade'); 
            $table->enum('status',['active','inactive'])->default('active');
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
        Schema::dropIfExists('employee_hiring_questionnaires');
    }
};
