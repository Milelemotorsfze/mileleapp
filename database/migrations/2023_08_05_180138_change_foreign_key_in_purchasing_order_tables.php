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
            $table->bigInteger('vendors_id')->unsigned()->index()->nullable();
            $table->foreign('vendors_id')->references('id')->on('suppliers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchasing_order', function (Blueprint $table) {
            //
        });
    }
};
