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
        Schema::table('supplier_inventories', function (Blueprint $table) {
            $table->bigInteger('supplier_id')->unsigned()->index()->nullable()->after('id');
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->dropColumn('supplier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_inventories', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropIndex(['supplier_id']);
            $table->dropColumn('supplier_id');
            $table->string('supplier');
        });
    }
};
