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
        Schema::table('quotation_details', function (Blueprint $table) {
            $table->bigInteger('to_shipping_port_id')->unsigned()->index()->after('quotation_id')->nullable();
            $table->foreign('to_shipping_port_id')->references('id')->on('master_shipping_ports');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotation_details', function (Blueprint $table) {
            $table->dropForeign('to_shipping_port_id');
            $table->dropIndex('to_shipping_port_id');
            $table->dropColumn('to_shipping_port_id');
        });
    }
};
