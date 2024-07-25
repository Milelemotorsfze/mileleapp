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
        Schema::table('purchasing_order_swift_copies', function (Blueprint $table) {
            $table->bigInteger('sat_id')->unsigned()->index()->nullable();
            $table->foreign('sat_id')->references('id')->on('supplier_account_transaction');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchasing_order_swift_copies', function (Blueprint $table) {
            $table->dropForeign(['sat_id']);
            $table->dropColumn('sat_id');
         
        });
    }
};
