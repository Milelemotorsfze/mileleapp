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
        Schema::dropIfExists('department_head_approvals');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('department_head_approvals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('department_id')->unsigned()->index()->nullable();
            $table->foreign('department_id')->references('id')->on('master_deparments')->onDelete('cascade');
            $table->bigInteger('department_head_id')->unsigned()->index()->nullable();
            $table->foreign('department_head_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('approval_by_id')->unsigned()->index()->nullable();
            $table->foreign('approval_by_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('status', ['active', 'inactive'])->default('active');
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
};
