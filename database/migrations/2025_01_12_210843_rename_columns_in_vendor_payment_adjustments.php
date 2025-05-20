<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('vendor_payment_adjustments', function (Blueprint $table) {
            // Rename supplier_account_id to old_supplier_account_id
            $table->renameColumn('supplier_account_id', 'old_supplier_account_id');

            // Rename new_supplier_account_id to supplier_account_id
            $table->renameColumn('new_supplier_account_id', 'supplier_account_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('vendor_payment_adjustments', function (Blueprint $table) {
            // Reverse the renaming
            $table->renameColumn('old_supplier_account_id', 'supplier_account_id');
            $table->renameColumn('supplier_account_id', 'new_supplier_account_id');
        });
    }
};
