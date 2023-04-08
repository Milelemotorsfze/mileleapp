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
        Schema::create('monthly_demands', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('demand_list_id')->unsigned()->index()->nullable();
            $table->unsignedBigInteger('demand_id')->unsigned()->index()->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->string('quantity')->nullable();
            $table->foreign('demand_id')->references('id')->on('demands');
            $table->foreign('demand_list_id')->references('id')->on('demand_lists');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_demands');
    }
};
