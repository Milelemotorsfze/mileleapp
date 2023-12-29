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
        Schema::table('pfi', function (Blueprint $table) {
            $table->double('released_amount')->nullable()->after('pfi_date');
            $table->bigInteger('supplier_id')->unsigned()->index()->nullable()->after('id');
            $table->string('currency')->nullable()->after('pfi_date');
            $table->string('delivery_location')->nullable()->after('pfi_date');

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pfi', function (Blueprint $table) {
            $table->dropColumn('released_amount');
            $table->dropColumn('currency');
            $table->dropColumn('delivery_location');
            $table->dropIndex('supplier_id');
            $table->dropForeign('supplier_id');
            $table->dropColumn('supplier_id');
        });
    }
};
