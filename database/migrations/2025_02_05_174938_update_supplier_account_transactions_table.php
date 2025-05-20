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
            $table->boolean('is_transfer_copy_email_send')->default(0)->nullable()->after('transition_file');
            $table->boolean('is_swift_copy_email_send')->default(0)->nullable()->after('transition_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_account_transaction', function (Blueprint $table) {
            $table->dropColumn('is_transfer_copy_email_send');
            $table->dropColumn('is_swift_copy_email_send');
        });
    }
};
