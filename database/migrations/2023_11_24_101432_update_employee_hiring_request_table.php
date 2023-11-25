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
        Schema::table('employee_hiring_requests', function (Blueprint $table) {
            $table->enum('action_by_division_head', ['pending', 'approved','rejected'])->nullable();
            $table->bigInteger('division_head_id')->unsigned()->index()->nullable();
            $table->foreign('division_head_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('division_head_action_at')->nullable();
            $table->text('comments_by_division_head')->nullable();
            $table->enum('final_status', ['closed','open','onhold','cancelled'])->nullable();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_hiring_requests', function (Blueprint $table) {
            $table->dropColumn('action_by_division_head');
            $table->dropColumn('division_head_id');
            $table->dropColumn('division_head_action_at');
            $table->dropColumn('comments_by_division_head');
            $table->dropColumn('final_status');
        });
    }
};
