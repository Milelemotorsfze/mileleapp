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
        Schema::table('pfi', function (Blueprint $table) {
            $table->bigInteger('client_id')->unsigned()->index()->nullable()->after('supplier_id');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->bigInteger('country_id')->unsigned()->index()->nullable()->after('supplier_id');
            $table->foreign('country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pfi', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
            $table->dropForeign(['country_id']);
            $table->dropColumn('country_id');
        });
    }
};
