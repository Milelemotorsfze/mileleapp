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
        Schema::create('approval_by_positions', function (Blueprint $table) {
            $table->id();
            $table->string('approved_by_position')->nullable();
            $table->bigInteger('approved_by_id')->unsigned()->index()->nullable();
            $table->foreign('approved_by_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('handover_to_id')->unsigned()->index()->nullable();
            $table->foreign('handover_to_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('approval_by_positions');
    }
};
