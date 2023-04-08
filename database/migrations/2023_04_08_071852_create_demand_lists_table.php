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
        Schema::create('demand_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('demand_id')->unsigned()->index()->nullable();
            $table->string('model')->nullable();
            $table->string('sfx')->nullable();
            $table->string('variant_name')->nullable();
            $table->unsignedBigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('demand_id')->references('id')->on('demands');
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demand_lists');
    }
};
