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
        Schema::table('supplier_account_transaction', function (Blueprint $table) {
            $table->string('swift_copy')->nullable();
            $table->string('transition_file')->nullable();
            $table->bigInteger('bank_accounts_id')->unsigned()->index()->nullable();
            $table->foreign('bank_accounts_id')->references('id')->on('bank_accounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_account_transaction', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropForeign(['bank_accounts_id']);
            $table->dropColumn('bank_accounts_id');
         
        });
    }
};
