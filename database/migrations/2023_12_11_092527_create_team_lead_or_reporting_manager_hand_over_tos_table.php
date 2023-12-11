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
        Schema::create('lead_or_manager_hand_over', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('lead_or_manager_id')->unsigned()->index()->nullable();
            $table->foreign('lead_or_manager_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('approval_by_id')->unsigned()->index()->nullable();
            $table->foreign('approval_by_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('updated_by')->unsigned()->index()->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('deleted_by')->unsigned()->index()->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_or_manager_hand_over');
    }
};
