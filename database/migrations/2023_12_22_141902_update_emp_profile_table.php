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
            $table->datetime('documents_verified_at')->nullable();
            $table->bigInteger('documents_verified_by')->unsigned()->index()->nullable();
            $table->foreign('documents_verified_by')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emp_profile', function (Blueprint $table) {
            $table->dropColumn('documents_verified_at');
            $table->dropForeign(['documents_verified_by']);
            $table->dropColumn('documents_verified_by');
        });
    }
};
