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
        Schema::create('sales_targets_lead_time', function (Blueprint $table) {
            $table->id();
            $table->string('lead_from')->nullable();
            $table->string('lead_to')->nullable();
            $table->string('leads_days')->nullable();
            $table->bigInteger('sales_targets_id')->unsigned()->index()->nullable();
            $table->foreign('sales_targets_id')->references('id')->on('sales_targets');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_targets_lead_time');
    }
};
