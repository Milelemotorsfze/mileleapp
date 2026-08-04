<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchasing_order', function (Blueprint $table) {
            if (!Schema::hasColumn('purchasing_order', 'new_purchase_email_sent_at')) {
                // When the one-per-PO "New Purchase" email was sent (button-triggered on
                // payment release). NULL = not sent yet, so the Send button is shown.
                $table->timestamp('new_purchase_email_sent_at')->nullable()->after('pl_file_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('purchasing_order', function (Blueprint $table) {
            if (Schema::hasColumn('purchasing_order', 'new_purchase_email_sent_at')) {
                $table->dropColumn('new_purchase_email_sent_at');
            }
        });
    }
};
