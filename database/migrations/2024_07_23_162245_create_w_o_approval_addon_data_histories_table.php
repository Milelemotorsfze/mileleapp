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
        Schema::create('w_o_approval_addon_data_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('w_o_approvals_id')->unsigned()->index()->nullable();
            $table->foreign('w_o_approvals_id')->references('id')->on('w_o_approvals');
            $table->bigInteger('wo_addon_history_id')->unsigned()->index()->nullable();
            $table->foreign('wo_addon_history_id')->references('id')->on('w_o_vehicle_addon_record_histories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('w_o_approval_addon_data_histories');
    }
};
