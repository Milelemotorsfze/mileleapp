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
        Schema::table('boe_penalties', function (Blueprint $table) {
            $table->unsignedBigInteger('wo_boe_id');
            $table->foreign('wo_boe_id')->references('id')->on('wo_boe')->onDelete('cascade')->after('id');
            $table->string('invoice_number')->nullable();
            $table->string('fine_type')->nullable();
            $table->dropColumn('excess_days');
            $table->dropColumn('total_penalty_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boe_penalties', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['wo_boe_id']);

            // Drop the column
            $table->dropColumn('wo_boe_id');
            $table->dropColumn('invoice_number');
            $table->dropColumn('fine_type');
            $table->integer('excess_days')->nullable();
            $table->decimal('total_penalty_amount', 10, 2)->nullable();
        });
    }
};
