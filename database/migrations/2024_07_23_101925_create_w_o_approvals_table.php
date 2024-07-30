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
        Schema::create('w_o_approvals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('work_order_id')->unsigned()->index()->nullable();
            $table->foreign('work_order_id')->references('id')->on('work_orders');
            $table->enum('type', ['finance','coo'])->nullable();
            $table->enum('status', ['pending','approved','rejected'])->default('pending')->nullable();
            $table->datetime('action_at')->nullable();
            $table->text('comments')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('w_o_approvals');
    }
};
