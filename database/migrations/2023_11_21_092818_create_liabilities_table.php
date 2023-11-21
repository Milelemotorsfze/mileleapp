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
        Schema::create('liabilities', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id')->unsigned()->index()->nullable();
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('request_date')->nullable();
            $table->enum('loan', ['yes', 'no'])->default('no');
            $table->decimal('loan_amount', 10,2)->default('0.00');
            $table->enum('advances', ['yes', 'no'])->default('no');
            $table->decimal('advances_amount', 10,2)->default('0.00');
            $table->enum('penalty_or_fine', ['yes', 'no'])->default('no');
            $table->decimal('penalty_or_fine_amount', 10,2)->default('0.00');
            $table->decimal('total_amount', 10,2)->default('0.00');
            $table->integer('no_of_installments')->nullable();
            $table->decimal('amount_per_installment', 10,2)->default('0.00');
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved','rejected'])->default('pending');

            $table->enum('action_by_employee', ['pending', 'approved','rejected'])->default('pending');
            $table->timestamp('employee_action_at')->nullable();
            $table->text('comments_by_employee')->nullable();

            $table->enum('action_by_department_head', ['pending', 'approved','rejected'])->nullable();
            $table->bigInteger('department_head_id')->unsigned()->index()->nullable();
            $table->foreign('department_head_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('department_head_action_at')->nullable();
            $table->text('comments_by_department_head')->nullable();

            $table->enum('action_by_finance_manager', ['pending', 'approved','rejected'])->nullable();
            $table->bigInteger('finance_manager_id')->unsigned()->index()->nullable();
            $table->foreign('finance_manager_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('finance_manager_action_at')->nullable();
            $table->text('comments_by_finance_manager')->nullable();

            $table->enum('action_by_hr_manager', ['pending', 'approved','rejected'])->nullable();
            $table->bigInteger('hr_manager_id')->unsigned()->index()->nullable();
            $table->foreign('hr_manager_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('hr_manager_action_at')->nullable();
            $table->text('comments_by_hr_manager')->nullable();

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
        Schema::dropIfExists('liabilities');
    }
};
