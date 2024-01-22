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
        Schema::create('sales_targets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sales_person_id')->unsigned()->index()->nullable();
            $table->foreign('sales_person_id')->references('id')->on('users');
            $table->string('walkingleads')->nullable();
            $table->string('marketTarget')->nullable();
            $table->string('productbasemarketing')->nullable();
            $table->string('exportsale')->nullable();
            $table->string('localsale')->nullable();
            $table->string('lease')->nullable();
            $table->string('googlereview')->nullable();
            $table->string('kits')->nullable();
            $table->string('shipping')->nullable();
            $table->string('spareparts')->nullable();
            $table->string('accessiores')->nullable();
            $table->string('uniquecustomers')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_targets');
    }
};
