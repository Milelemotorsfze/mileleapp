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
        Schema::table('liabilities', function (Blueprint $table) {
            $table->dropColumn('loan');
            $table->dropColumn('loan_amount');
            $table->dropColumn('advances');
            $table->dropColumn('advances_amount');
            $table->dropColumn('penalty_or_fine');
            $table->dropColumn('penalty_or_fine_amount');
            $table->enum('type', ['loan','advances','penalty_or_fine'])->nullable();
            $table->string('code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('liabilities', function (Blueprint $table) {
            $table->enum('loan', ['yes', 'no'])->default('no');
            $table->decimal('loan_amount', 10,2)->default('0.00');
            $table->enum('advances', ['yes', 'no'])->default('no');
            $table->decimal('advances_amount', 10,2)->default('0.00');
            $table->enum('penalty_or_fine', ['yes', 'no'])->default('no');
            $table->decimal('penalty_or_fine_amount', 10,2)->default('0.00');
            $table->dropColumn('type');
            $table->dropColumn('code');
        });
    }
};
