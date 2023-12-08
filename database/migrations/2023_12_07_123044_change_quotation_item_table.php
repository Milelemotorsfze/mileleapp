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
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->string('reference_type')->nullable()->change();
            $table->bigInteger('reference_id')->nullable()->change();
            $table->double('system_code_amount')->nullable()->after('total_amount');
            $table->string('system_code_currency')->nullable()->after('total_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->string('reference_type')->change();
            $table->bigInteger('reference_id')->change();
            $table->dropColumn('system_code_amount');
            $table->dropColumn('system_code_currency');
        });
    }
};
