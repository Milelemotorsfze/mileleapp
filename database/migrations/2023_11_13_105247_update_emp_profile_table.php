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
            $table->string('employee_code')->nullable()->after('id');
            $table->bigInteger('designation_id')->unsigned()->index()->nullable()->after('last_name');
            $table->foreign('designation_id')->references('id')->on('master_job_positions')->onDelete('cascade');
            $table->bigInteger('department_id')->unsigned()->index()->nullable()->after('designation_id');
            $table->foreign('department_id')->references('id')->on('master_deparments')->onDelete('cascade');
            $table->bigInteger('gender')->unsigned()->index()->nullable()->after('department_id')->change();
            $table->foreign('gender')->references('id')->on('master_genders')->onDelete('cascade');
            $table->date('dob')->nullable()->after('gender');
            $table->string('birthday_month')->nullable()->after('dob');
            $table->integer('age')->nullable()->after('birthday_month');
            $table->bigInteger('marital_status')->unsigned()->index()->nullable()->after('age');
            $table->foreign('marital_status')->references('id')->on('master_marital_statuses')->onDelete('cascade');
            $table->bigInteger('religion')->unsigned()->index()->nullable()->after('marital_status')->change();
            $table->foreign('religion')->references('id')->on('master_religions')->onDelete('cascade');
            $table->bigInteger('nationality')->unsigned()->index()->nullable()->after('religion')->change();
            $table->foreign('nationality')->references('id')->on('countries')->onDelete('cascade');
            $table->string('personal_email_address')->nullable()->after('nationality');
            $table->string('name_of_father')->nullable()->after('personal_email_address');
            $table->string('name_of_mother')->nullable()->after('name_of_father');
            $table->string('e_c_p_name_in_uae')->nullable()->after('address_uae');
            $table->string('e_c_p_mobile_number_in_uae')->nullable()->after('e_c_p_name_in_uae');
            $table->bigInteger('e_c_p_relation_in_uae')->unsigned()->index()->nullable()->after('e_c_p_mobile_number_in_uae');
            $table->foreign('e_c_p_relation_in_uae')->references('id')->on('master_person_relations')->onDelete('cascade');
            $table->string('e_c_p_email_in_uae')->nullable()->after('e_c_p_relation_in_uae');
            $table->string('e_c_p_name_in_h_c')->nullable()->after('address_home');
            $table->string('e_c_p_mobile_number_in_h_c')->nullable()->after('e_c_p_name_in_h_c');
            $table->bigInteger('e_c_p_relation_in_h_c')->unsigned()->index()->nullable()->after('e_c_p_mobile_number_in_h_c');
            $table->foreign('e_c_p_relation_in_h_c')->references('id')->on('master_person_relations')->onDelete('cascade');
            $table->string('e_c_p_email_in_h_c')->nullable()->after('e_c_p_relation_in_h_c');
            $table->string('cec_or_person_code_number')->nullable()->after('e_c_p_email_in_h_c');
            $table->string('emirates_id')->nullable()->after('cec_or_person_code_number');
            $table->string('passport_number')->nullable()->after('emirates_id')->unique()->change();
            $table->string('passport_place_of_issue')->nullable()->after('passport_number');
            $table->enum('passport_status', ['with_employee','with_milele'])->after('passport_place_of_issue')->nullable();
            $table->bigInteger('visa_type')->unsigned()->index()->nullable()->after('passport_status')->change();
            $table->foreign('visa_type')->references('id')->on('master_visa_types')->onDelete('cascade');
            $table->integer('visa_number')->nullable()->after('visa_type');
            $table->date('visa_issue_date')->nullable()->after('visa_number');
            $table->date('visa_expiry_date')->nullable()->after('visa_issue_date');
            $table->date('reminder_date_for_visa_renewal')->nullable()->after('visa_expiry_date');
            $table->bigInteger('visa_issueing_country')->unsigned()->index()->nullable()->after('reminder_date_for_visa_renewal');
            $table->foreign('visa_issueing_country')->references('id')->on('countries')->onDelete('cascade');
            $table->bigInteger('sponsorship')->unsigned()->index()->nullable()->after('visa_issueing_country');
            $table->foreign('sponsorship')->references('id')->on('master_sponcerships')->onDelete('cascade');
            $table->date('company_joining_date')->nullable()->after('sponsorship');
            $table->enum('current_status', ['active','inacive','onleave'])->default('active')->after('company_joining_date')->nullable();
            $table->date('status_date')->nullable()->after('current_status');
            $table->integer('probation_duration_in_months')->nullable()->after('status_date');
            $table->date('probation_period_start_date')->nullable()->after('probation_duration_in_months');
            $table->date('probation_period_end_date')->nullable()->after('probation_period_start_date');
            $table->enum('employment_contract_type', ['limited_contract','unlimited_contract'])->after('probation_period_end_date')->nullable();
            $table->date('employment_contract_start_date')->nullable()->after('employment_contract_type');
            $table->date('employment_contract_end_date')->nullable()->after('employment_contract_start_date');
            $table->integer('employment_contract_probation_period_in_months')->nullable()->after('employment_contract_end_date');
            $table->date('employment_contract_probation_end_date')->nullable()->after('employment_contract_probation_period_in_months');
            $table->bigInteger('work_location')->unsigned()->index()->nullable()->after('employment_contract_probation_end_date');
            $table->foreign('work_location')->references('id')->on('master_office_locations')->onDelete('cascade');
            $table->bigInteger('division')->unsigned()->index()->nullable()->after('work_location');
            $table->foreign('division')->references('id')->on('master_division_with_heads')->onDelete('cascade');
            $table->bigInteger('team_lead_or_reporting_manager')->unsigned()->index()->nullable()->after('division');
            $table->foreign('team_lead_or_reporting_manager')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('division_head')->unsigned()->index()->nullable()->after('team_lead_or_reporting_manager');
            $table->foreign('division_head')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('basic_salary', 10,2)->default('0.00')->after('division_head');
            $table->decimal('other_allowances', 10,2)->default('0.00')->after('basic_salary');
            $table->decimal('total_salary', 10,2)->default('0.00')->after('other_allowances');
            $table->date('increament_effective_date')->nullable()->after('total_salary');
            $table->decimal('increment_amount', 10,2)->default('0.00')->after('increament_effective_date');
            $table->decimal('revised_basic_salary', 10,2)->default('0.00')->after('increment_amount');
            $table->decimal('revised_other_allowance', 10,2)->default('0.00')->after('revised_basic_salary');
            $table->decimal('revised_total_salary', 10,2)->default('0.00')->after('revised_other_allowance');
            $table->string('insurance_policy_number')->nullable()->after('revised_total_salary');
            $table->string('insurance_card_number')->nullable()->after('insurance_policy_number');
            $table->date('insurance_policy_start_date')->nullable()->after('insurance_card_number');
            $table->date('insurance_policy_end_date')->nullable()->after('insurance_policy_start_date');
            $table->enum('leaving_type', ['resigned','terminated'])->after('insurance_policy_end_date')->nullable();
            $table->bigInteger('leaving_reason')->unsigned()->index()->nullable()->after('leaving_type');
            $table->foreign('leaving_reason')->references('id')->on('users')->onDelete('cascade');
            $table->enum('notice_period_to_serve', ['yes','no'])->default('no')->after('leaving_reason')->nullable();
            $table->integer('notice_period_duration')->nullable()->after('notice_period_to_serve');
            $table->date('last_working_day')->nullable()->after('notice_period_duration');
            $table->date('visa_cancellation_received_date')->nullable()->after('notice_period_duration');
            $table->date('change_status_or_exit_UAE_date')->nullable()->after('visa_cancellation_received_date');
            $table->enum('insurance_cancellation_done', ['yes','no'])->default('no')->after('change_status_or_exit_UAE_date')->nullable();
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('updated_by')->unsigned()->index()->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('deleted_by')->unsigned()->index()->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emp_profile', function (Blueprint $table) {
            $table->dropColumn('employee_code');
            $table->dropColumn('designation_id');
            $table->dropColumn('department_id');
            $table->string('gender')->nullable()->change();
            $table->dropColumn('dob');
            $table->dropColumn('birthday_month');
            $table->dropColumn('age');
            $table->dropColumn('marital_status');
            $table->string('religion')->nullable()->change();
            $table->string('nationality')->nullable();
            $table->dropColumn('personal_email_address');
            $table->dropColumn('name_of_father');
            $table->dropColumn('name_of_mother');
            $table->dropColumn('e_c_p_name_in_uae');
            $table->dropColumn('e_c_p_mobile_number_in_uae');
            $table->dropColumn('e_c_p_relation_in_uae');
            $table->dropColumn('e_c_p_email_in_uae');
            $table->dropColumn('e_c_p_name_in_h_c');
            $table->dropColumn('e_c_p_mobile_number_in_h_c');
            $table->dropColumn('e_c_p_relation_in_h_c');
            $table->dropColumn('e_c_p_email_in_h_c');
            $table->dropColumn('cec_or_person_code_number');
            $table->dropColumn('emirates_id');
            $table->string('passport_number')->nullable()->change();
            $table->dropColumn('passport_place_of_issue');
            $table->dropColumn('passport_status');
            $table->string('visa_type')->nullable()->change();
            $table->dropColumn('visa_number');
            $table->dropColumn('visa_issue_date');
            $table->dropColumn('visa_expiry_date');
            $table->dropColumn('reminder_date_for_visa_renewal');
            $table->dropColumn('visa_issueing_country');
            $table->dropColumn('sponsorship');
            $table->dropColumn('company_joining_date');
            $table->dropColumn('current_status');
            $table->dropColumn('status_date');
            $table->dropColumn('probation_duration_in_months');
            $table->dropColumn('probation_period_start_date');
            $table->dropColumn('probation_period_end_date');
            $table->dropColumn('employment_contract_type');
            $table->dropColumn('employment_contract_start_date');
            $table->dropColumn('employment_contract_end_date');
            $table->dropColumn('employment_contract_probation_period_in_months');
            $table->dropColumn('employment_contract_probation_end_date');
            $table->dropColumn('work_location');
            $table->dropColumn('division');
            $table->dropColumn('team_lead_or_reporting_manager');
            $table->dropColumn('division_head');
            $table->dropColumn('basic_salary');
            $table->dropColumn('other_allowances');
            $table->dropColumn('total_salary');
            $table->dropColumn('increament_effective_date');
            $table->dropColumn('increment_amount');
            $table->dropColumn('revised_basic_salary');
            $table->dropColumn('revised_other_allowance');
            $table->dropColumn('revised_total_salary');
            $table->dropColumn('insurance_policy_number');
            $table->dropColumn('insurance_card_number');
            $table->dropColumn('insurance_policy_start_date');
            $table->dropColumn('insurance_policy_end_date');
            $table->dropColumn('leaving_type');
            $table->dropColumn('leaving_reason');
            $table->dropColumn('notice_period_to_serve');
            $table->dropColumn('notice_period_duration');
            $table->dropColumn('last_working_day');
            $table->dropColumn('visa_cancellation_received_date');
            $table->dropColumn('change_status_or_exit_UAE_date');
            $table->dropColumn('insurance_cancellation_done');
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
            $table->dropColumn('deleted_by');
            $table->dropSoftDeletes();
     });
    }
};
