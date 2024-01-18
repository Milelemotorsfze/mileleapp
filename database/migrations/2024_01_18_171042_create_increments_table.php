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
        Schema::create('increments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id')->unsigned()->index()->nullable();
            $table->foreign('employee_id')->references('id')->on('users');
            $table->decimal('basic_salary', 10,2)->default('0.00');
            $table->decimal('other_allowances', 10,2)->default('0.00');
            $table->decimal('total_salary', 10,2)->default('0.00');
            $table->date('increament_effective_date')->nullable();
            $table->decimal('increment_amount', 10,2)->default('0.00');
            $table->decimal('revised_basic_salary', 10,2)->default('0.00');
            $table->decimal('revised_other_allowance', 10,2)->default('0.00');
            $table->decimal('revised_total_salary', 10,2)->default('0.00');
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
        Schema::dropIfExists('increments');
    }
};
