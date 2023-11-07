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
        Schema::table('other_logistics_charges', function (Blueprint $table) {
            $table->string('code')->nullable()->after('id');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('other_logistics_charges', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->dropSoftDeletes();
        });
    }
};
