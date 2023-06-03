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
        Schema::table('approved_letter_of_indent_items', function (Blueprint $table) {
            $table->bigInteger('letter_of_indent_id')->unsigned()->index()->nullable()->after('id');
            $table->foreign('letter_of_indent_id')->references('id')->on('letter_of_indents');
            $table->bigInteger('pfi_id')->unsigned()->index()->nullable()->after('letter_of_indent_item_id');
            $table->foreign('pfi_id')->references('id')->on('pfi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('approved_letter_of_indent_items', function (Blueprint $table) {
            $table->dropForeign(['letter_of_indent_id']);
            $table->dropIndex(['letter_of_indent_id']);
            $table->dropColumn('letter_of_indent_id');
            $table->dropForeign(['pfi_id']);
            $table->dropIndex(['pfi_id']);
            $table->dropColumn('pfi_id');
        });
    }
};
