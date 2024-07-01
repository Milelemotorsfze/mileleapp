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
            $table->foreign('pol')->references('id')->on('master_shipping_ports')->onDelete('cascade');
            $table->foreign('pod')->references('id')->on('master_shipping_ports')->onDelete('cascade');
            $table->foreign('fd')->references('id')->on('countries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchasing_order', function (Blueprint $table) {
            $table->dropForeign(['pol']);
            $table->dropForeign(['pod']);
            $table->dropForeign(['fd']);
        });
    }
};
