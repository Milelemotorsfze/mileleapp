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
        Schema::table('master_models', function (Blueprint $table) {
            $table->year('model_year')->after('sfx')->nullable();
            $table->boolean('is_transcar')->after('sfx')->nullable();
            $table->boolean('is_milele')->after('sfx')->nullable();
            $table->string('milele_loi_description')->after('sfx')->nullable();
            $table->string('transcar_loi_description')->after('sfx')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_models', function (Blueprint $table) {
            $table->dropColumn('model_year');
            $table->dropColumn('is_transcar');
            $table->dropColumn('is_milele');
            $table->dropColumn('milele_loi_description');
            $table->dropColumn('transcar_loi_description');
        });
    }
};
