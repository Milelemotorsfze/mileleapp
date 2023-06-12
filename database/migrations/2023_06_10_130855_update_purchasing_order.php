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
            $table->string('status')->nullable();
            $table->bigInteger('suppliers_id')->unsigned()->index()->nullable();
            $table->foreign('suppliers_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchasing_order', function (Blueprint $table) {
            $table->dropForeign(['suppliers_id']);
            $table->dropColumn('status');
            $table->dropColumn('suppliers_id');
        });
    }
};
