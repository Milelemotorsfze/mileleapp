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
            $table->bigInteger('country_id')->unsigned()->index()->nullable();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->bigInteger('shipping_port_id')->unsigned()->index()->nullable();
            $table->foreign('shipping_port_id')->references('id')->on('master_shipping_ports')->onDelete('cascade');
            $table->dropColumn('final_destination');
            $table->dropColumn('place_of_delivery');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotation_details', function (Blueprint $table) {
            $table->dropForeign('country_id');
            $table->dropIndex('country_id');
            $table->dropColumn('country_id');
            $table->dropForeign('shipping_port_id');
            $table->dropIndex('shipping_port_id');
            $table->dropColumn('shipping_port_id');
            $table->string('final_destination');
            $table->string('place_of_delivery');
        });
    }
};
