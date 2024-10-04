<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{ 
    public function up(): void
    {
        Schema::table('letter_of_indents', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);    
            $table->dropColumn('customer_id');
            $table->dropForeign(['entity_id']);    
            $table->dropColumn('entity_id');
            $table->dropForeign(['supplier_id']);
            $table->dropColumn('supplier_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('letter_of_indents', function (Blueprint $table) {

            $table->bigInteger('customer_id')->unsigned()->index()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->bigInteger('entity_id')->unsigned()->index()->nullable();
            $table->foreign('entity_id')->references('id')->on('customers');
            $table->bigInteger('supplier_id')->unsigned()->index()->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            // $table->bigInteger('customer_id')->nullable();
            // $table->bigInteger('supplier_id')->nullable();
            // $table->bigInteger('entity_id')->nullable();
        });
    }
};
