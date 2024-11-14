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
        Schema::create('leads_task', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id'); // The lead this task is associated with
            $table->unsignedBigInteger('assigned_by'); // Who is assigning the task (user_id)
            $table->string('task_message'); // Task message or description
            $table->string('status')->default('Pending'); // Task status (Pending, In Progress, Completed)
            $table->foreign('assigned_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads_task');
    }
};
