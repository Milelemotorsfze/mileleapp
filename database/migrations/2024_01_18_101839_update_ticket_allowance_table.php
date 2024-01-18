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
        Schema::table('ticket_allowances', function (Blueprint $table) {
            $table->year('eligibility_year')->nullable();
            $table->date('eligibility_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_allowances', function (Blueprint $table) {
            $table->dropColumn('eligibility_year');
            $table->dropColumn('eligibility_date');
        });
    }
};
