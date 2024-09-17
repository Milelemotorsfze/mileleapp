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
        Schema::table('work_orders', function (Blueprint $table) {
            $table->string('preferred_shipping_line_of_customer')->nullable()->after('delivery_date');
            $table->text('bill_of_loading_details')->nullable()->after('preferred_shipping_line_of_customer');
            $table->text('shipper')->nullable()->after('bill_of_loading_details');
            $table->text('consignee')->nullable()->after('shipper');
            $table->text('notify_party')->nullable()->after('consignee');
            $table->text('special_or_transit_clause_or_request')->nullable()->after('notify_party');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn('preferred_shipping_line_of_customer');
            $table->dropColumn('bill_of_loading_details');
            $table->dropColumn('shipper');
            $table->dropColumn('consignee');
            $table->dropColumn('notify_party');
            $table->dropColumn('special_or_transit_clause_or_request');
        });
    }
};
