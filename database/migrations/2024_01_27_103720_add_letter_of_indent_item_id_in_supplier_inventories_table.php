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
            $table->bigInteger('letter_of_indent_item_id')->unsigned()->index()->nullable()->after('supplier_id');
            $table->foreign('letter_of_indent_item_id')->references('id')->on('letter_of_indent_items');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_inventories', function (Blueprint $table) {
            $table->dropIndex(['letter_of_indent_item_id']);
            $table->dropForeign(['letter_of_indent_item_id']);
            $table->dropColumn(['letter_of_indent_item_id']);

        });
    }
};
