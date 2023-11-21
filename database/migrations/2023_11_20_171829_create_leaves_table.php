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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id')->unsigned()->index()->nullable();
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('type_of_leave', ['annual', 'sick','unpaid','maternity_or_peternity','others'])->nullable();
            $table->string('type_of_leave_description')->nullable();
            $table->date('leave_start_date')->nullable();
            $table->date('leave_end_date')->nullable();
            $table->integer('total_no_of_days')->nullable();
            $table->integer('no_of_paid_days')->nullable();
            $table->integer('no_of_unpaid_days')->nullable();
            $table->text('address_while_on_leave')->nullable();
            $table->string('alternative_home_contact_no')->nullable();
            $table->string('alternative_personal_email')->nullable();
            $table->enum('status', ['pending', 'approved','rejected'])->default('pending');

            $table->enum('action_by_employee', ['pending', 'approved','rejected'])->default('pending');
            $table->timestamp('employee_action_at')->nullable();
            $table->text('comments_by_employee')->nullable();

            $table->decimal('advance_or_loan_balance', 10,2)->default('0.00');
            $table->string('others')->nullable();
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
            $table->bigInteger('to_be_replaced_by')->unsigned()->index()->nullable();
            $table->foreign('to_be_replaced_by')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('leaves');
    }
};
