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
        Schema::create('joining_reports', function (Blueprint $table) {
            $table->id();
            $table->enum('joining_type', ['new_employee', 'internal_transfer','vacations_or_leave'])->nullable();
            
            $table->date('trial_period_joining_date')->nullable();
            $table->date('permanent_joining_date')->nullable();
            $table->bigInteger('permanent_joining_location_id')->unsigned()->index()->nullable();
            $table->foreign('permanent_joining_location_id')->references('id')->on('master_office_locations')->onDelete('cascade');

            $table->bigInteger('transfer_from_department_id')->unsigned()->index()->nullable();
            $table->foreign('transfer_from_department_id')->references('id')->on('master_deparments')->onDelete('cascade');
            $table->date('transfer_from_date')->nullable();
            $table->bigInteger('transfer_from_location_id')->unsigned()->index()->nullable();
            $table->foreign('transfer_from_location_id')->references('id')->on('master_office_locations')->onDelete('cascade');

            $table->bigInteger('transfer_to_department_id')->unsigned()->index()->nullable();
            $table->foreign('transfer_to_department_id')->references('id')->on('master_deparments')->onDelete('cascade');
            $table->date('transfer_to_date')->nullable();
            $table->bigInteger('transfer_to_location_id')->unsigned()->index()->nullable();
            $table->foreign('transfer_to_location_id')->references('id')->on('master_office_locations')->onDelete('cascade');

            $table->date('leave_joining_date')->nullable();
            $table->bigInteger('location_id')->unsigned()->index()->nullable();
            $table->foreign('location_id')->references('id')->on('master_office_locations')->onDelete('cascade');

            $table->string('remarks')->nullable();

            $table->bigInteger('prepared_by_id')->unsigned()->index()->nullable();
            $table->foreign('prepared_by_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('action_by_prepared_by', ['pending', 'approved','rejected'])->default('pending');
            $table->timestamp('prepared_by_action_at')->nullable();
            $table->text('comments_by_prepared_by')->nullable();

            $table->bigInteger('employee_id')->unsigned()->index()->nullable();
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('action_by_employee', ['pending', 'approved','rejected'])->default('pending');
            $table->timestamp('employee_action_at')->nullable();
            $table->text('comments_by_employee')->nullable();

            $table->enum('action_by_hr_manager', ['pending', 'approved','rejected'])->nullable();
            $table->bigInteger('hr_manager_id')->unsigned()->index()->nullable();
            $table->foreign('hr_manager_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('hr_manager_action_at')->nullable();
            $table->text('comments_by_hr_manager')->nullable();

            $table->enum('action_by_department_head', ['pending', 'approved','rejected'])->nullable();
            $table->bigInteger('department_head_id')->unsigned()->index()->nullable();
            $table->foreign('department_head_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('department_head_action_at')->nullable();
            $table->text('comments_by_department_head')->nullable();

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
        Schema::dropIfExists('joining_reports');
    }
};
