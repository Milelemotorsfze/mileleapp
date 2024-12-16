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
        Schema::table('work_orders', function (Blueprint $table) {
            // Add the ENUM column 'has_claim' with values 'yes' and 'no'
            $table->enum('has_claim', ['yes', 'no'])->nullable()->default(null)->after('coe_office_direct_approval_comments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            // Drop the column 'has_claim' on rollback
            $table->dropColumn('has_claim');
        });
    }
};
