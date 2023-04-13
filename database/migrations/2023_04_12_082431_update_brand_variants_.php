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
        Schema::table('varaints', function (Blueprint $table) {
        $table->dropColumn('brand');
        $table->bigInteger('brands_id')->unsigned()->index()->nullable();
        $table->foreign('brands_id')->references('id')->on('brands')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('varaints', function (Blueprint $table) {
            $table->dropColumn('brands_id');
        });
    }
};
