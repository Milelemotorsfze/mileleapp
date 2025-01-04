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
        Schema::table('boe_penalties', function (Blueprint $table) {
            // Rename columns
            if (Schema::hasColumn('boe_penalties', 'payment_date')) {
                $table->renameColumn('payment_date', 'invoice_date');
            }
            if (Schema::hasColumn('boe_penalties', 'amount_paid')) {
                $table->renameColumn('amount_paid', 'penalty_amount');
            }

            // Drop unnecessary columns
            if (Schema::hasColumn('boe_penalties', 'excess_days')) {
                $table->dropColumn('excess_days');
            }
            if (Schema::hasColumn('boe_penalties', 'fine_type')) {
                $table->dropColumn('fine_type');
            }
        });

        // Reorder the columns to match the desired order
        // DB::statement("
        //     ALTER TABLE boe_penalties 
        //     MODIFY COLUMN id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT FIRST,
        //     MODIFY COLUMN wo_boe_id BIGINT UNSIGNED NOT NULL AFTER id,
        //     MODIFY COLUMN invoice_date DATE NULL AFTER wo_boe_id,
        //     MODIFY COLUMN invoice_number VARCHAR(255) NULL AFTER invoice_date,
        //     MODIFY COLUMN penalty_amount DECIMAL(10, 2) NULL AFTER invoice_number,
        //     MODIFY COLUMN payment_receipt VARCHAR(255) NULL AFTER penalty_amount,
        //     MODIFY COLUMN remarks TEXT NULL AFTER payment_receipt,
        //     MODIFY COLUMN created_by BIGINT UNSIGNED NULL AFTER remarks,
        //     MODIFY COLUMN updated_by BIGINT UNSIGNED NULL AFTER created_by;
        // ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boe_penalties', function (Blueprint $table) {
            // Add excess_days column back
            $table->integer('excess_days')->nullable();

            // Rename columns back to original names
            $table->renameColumn('invoice_date', 'payment_date');
            $table->renameColumn('penalty_amount', 'amount_paid');

            // Add fine_type column back
            $table->string('fine_type')->nullable();
        });
    }
};
