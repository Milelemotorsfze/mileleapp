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
        Schema::table('letter_of_indents', function (Blueprint $table) {
        
            $table->bigInteger('deleted_by')->unsigned()->index()->nullable()->after('created_by');
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->bigInteger('updated_by')->unsigned()->index()->nullable()->after('created_by');
            $table->foreign('updated_by')->references('id')->on('users');
            // $table->dropForeign(['supplier_id']);
            // $table->dropColumn('supplier_id');
            // $table->dropForeign(['entity_id']);
            // $table->dropColumn('entity_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('letter_of_indents', function (Blueprint $table) {
            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
         
        });
    }
};
