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
        Schema::table('purchasing_order', function (Blueprint $table) {
            $table->bigInteger('payment_term_id')->unsigned()->index()->nullable();
            $table->foreign('payment_term_id')->references('id')->on('payment_terms')->onDelete('cascade');   
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchasing_order', function (Blueprint $table) {
            $table->dropColumn('payment_term_id');
            $table->dropForeign(['payment_term_id']);
            $table->dropColumn('payment_term_id');    
        });
    }
};
