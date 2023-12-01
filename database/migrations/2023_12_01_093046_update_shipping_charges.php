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
        Schema::table('shipping_charges', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->dropColumn('name');
            $table->dropColumn('description');
            $table->bigInteger('vendors_id')->unsigned()->index()->nullable();
            $table->foreign('vendors_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->bigInteger('to_port')->unsigned()->index()->nullable();
            $table->foreign('to_port')->references('id')->on('master_shipping_ports')->onDelete('cascade');
            $table->bigInteger('from_port')->unsigned()->index()->nullable();
            $table->foreign('from_port')->references('id')->on('master_shipping_ports')->onDelete('cascade');
            $table->bigInteger('shipping_medium_id')->unsigned()->index()->nullable();
            $table->foreign('shipping_medium_id')->references('id')->on('shipping_medium')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_charges', function (Blueprint $table) {
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->dropForeign('vendors_id');
            $table->dropIndex('vendors_id');
            $table->dropColumn('vendors_id');
            $table->dropForeign('to_port');
            $table->dropIndex('to_port');
            $table->dropColumn('to_port');
            $table->dropForeign('from_port');
            $table->dropIndex('from_port');
            $table->dropColumn('from_port');
            $table->dropForeign('shipping_medium_id');
            $table->dropIndex('shipping_medium_id');
            $table->dropColumn('shipping_medium_id');
        });
    }
};
