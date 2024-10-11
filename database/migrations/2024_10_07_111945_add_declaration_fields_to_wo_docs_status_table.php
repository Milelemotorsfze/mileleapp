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
        Schema::table('wo_docs_status', function (Blueprint $table) {
            // Declaration number, a 13-digit number, not mandatory (nullable)
            $table->string('declaration_number', 13)->nullable();

            // Declaration date, not mandatory (nullable)
            $table->date('declaration_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wo_docs_status', function (Blueprint $table) {
            // Remove the columns if the migration is rolled back
            $table->dropColumn('declaration_number');
            $table->dropColumn('declaration_date');
        });
    }
};
