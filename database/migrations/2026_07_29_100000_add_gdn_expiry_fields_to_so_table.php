<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'Expired' so an SO can be auto-expired when no GDN is issued within 30 days.
        DB::statement("
            ALTER TABLE so
            MODIFY COLUMN status
            ENUM('Pending', 'Approved', 'Rejected', 'Cancelled', 'Expired')
            DEFAULT 'Pending'
        ");

        Schema::table('so', function (Blueprint $table) {
            if (!Schema::hasColumn('so', 'expiry_last_notified_date')) {
                // Last date the 6-day pre-expiry reminder email was sent (one per day).
                $table->date('expiry_last_notified_date')->nullable()->after('status');
            }
            if (!Schema::hasColumn('so', 'expired_at')) {
                // When the SO was auto-expired for having no GDN within 30 days.
                $table->timestamp('expired_at')->nullable()->after('expiry_last_notified_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('so', function (Blueprint $table) {
            $table->dropColumn(['expiry_last_notified_date', 'expired_at']);
        });

        DB::statement("
            ALTER TABLE so
            MODIFY COLUMN status
            ENUM('Pending', 'Approved', 'Rejected', 'Cancelled')
            DEFAULT 'Pending'
        ");
    }
};
