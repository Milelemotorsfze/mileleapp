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
        Schema::table('joining_reports', function (Blueprint $table) {
            $table->enum('internal_transfer_type', ['temporary', 'permanent'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('joining_reports', function (Blueprint $table) {
            $table->dropColumn('internal_transfer_type');
        });
    }
};
