<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The foreign key made every LC write take a shared lock on the parent
 * quotations row. Quotation store()/update() hold that row exclusively for the
 * whole of their transaction, so a concurrent submit sat waiting on the lock
 * until innodb_lock_wait_timeout (50s) fired - a near one-minute save.
 *
 * The unique index on quotation_id stays, so the one-row-per-quotation rule and
 * the lookup speed are unaffected; only the parent-row locking goes away.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('quotation_lc_details', function (Blueprint $table) {
            $table->dropForeign(['quotation_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotation_lc_details', function (Blueprint $table) {
            $table->foreign('quotation_id')->references('id')->on('quotations');
        });
    }
};
