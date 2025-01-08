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
        Schema::table('loi_expiry_conditions', function (Blueprint $table) {
            $table->enum('expiry_duration_type',['Month','Year'])->default('Year')->nullable()
                                ->after('expiry_duration_year');
            $table->renameColumn('expiry_duration_year','expiry_duration')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loi_expiry_conditions', function (Blueprint $table) {
            $table->dropColumn('expiry_duration_type');
            $table->renameColumn('expiry_duration','expiry_duration_year')->change();
        });
    }
};
