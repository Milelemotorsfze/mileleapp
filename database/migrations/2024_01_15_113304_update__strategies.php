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
        Schema::table('strategies', function (Blueprint $table) {
            $table->bigInteger('target_sales_person')->unsigned()->index()->nullable();
            $table->foreign('target_sales_person')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('strategies', function (Blueprint $table) {
            $table->dropIndex(['target_sales_person']);
            $table->dropForeign(['target_sales_person']);
            $table->dropColumn('target_sales_person');
        });
    }
};
