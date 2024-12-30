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
        Schema::table('purchasing_order', function (Blueprint $table) {
            $table->enum('payment_status', ['Unpaid','Partially Paid','Paid'])->nullable()->after('status');
            $table->enum('payment_initiated_status', ['Pending','Initiated'])->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchasing_order', function (Blueprint $table) {
             $table->dropColumn('payment_status');
             $table->dropColumn('payment_initiated_status');
        });
    }
};
