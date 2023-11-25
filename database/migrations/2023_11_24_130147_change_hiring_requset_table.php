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
            $table->dropColumn('action_by_hiring_manager');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_hiring_requests', function (Blueprint $table) {
            $table->enum('action_by_hiring_manager', ['pending', 'approved','rejected'])->default('pending');
        });
    }
};
