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
        Schema::table('lead_customers', function (Blueprint $table) {
            $table->dropForeign(['calls_id']);
        });
        Schema::table('lead_customers', function (Blueprint $table) {
            $table->dropColumn('calls_id');
        });
        Schema::rename('lead_customers', 'clients');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('clients', 'lead_customers');
        Schema::table('lead_customers', function (Blueprint $table) {
            $table->unsignedBigInteger('calls_id');
            $table->foreign('calls_id')->references('id')->on('calls');
        });
    }
};
