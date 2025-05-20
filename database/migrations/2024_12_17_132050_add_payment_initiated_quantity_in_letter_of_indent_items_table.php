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
        Schema::table('letter_of_indent_items', function (Blueprint $table) {
            $table->integer('po_payment_initiated_quantity')->default(0)->nullable()->after('utilized_quantity');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('letter_of_indent_items', function (Blueprint $table) {
            $table->dropColumn('po_payment_initiated_quantity');
        });
    }
};
