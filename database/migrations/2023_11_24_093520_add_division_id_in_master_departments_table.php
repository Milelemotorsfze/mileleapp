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
        Schema::table('master_deparments', function (Blueprint $table) {
            $table->bigInteger('division_id')->unsigned()->index()->nullable();
            $table->foreign('division_id')->references('id')->on('master_division_with_heads')->onDelete('cascade');
            $table->bigInteger('department_head_id')->unsigned()->index()->nullable();
            $table->foreign('department_head_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('approval_by_id')->unsigned()->index()->nullable();
            $table->foreign('approval_by_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_deparments', function (Blueprint $table) {
            $table->dropColumn('division_id');
            $table->dropColumn('department_head_id');
            $table->dropColumn('approval_by_id');
        });
    }
};
