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
        Schema::table('interview_summary_reports', function (Blueprint $table) {
            $table->text('pif_sign')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interview_summary_reports', function (Blueprint $table) {
            $table->string('pif_sign')->nullable()->change();
        });
    }
};
