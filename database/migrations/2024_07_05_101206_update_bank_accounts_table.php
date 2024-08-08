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
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->dropColumn('bank_name');
            $table->unsignedBigInteger('bank_master_id')->after('id');
            $table->foreign('bank_master_id')->references('id')->on('bank_master')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->dropForeign(['bank_master_id']);
            $table->dropColumn('bank_master_id');
            $table->string('bank_name')->unique();
        });
    }
};
