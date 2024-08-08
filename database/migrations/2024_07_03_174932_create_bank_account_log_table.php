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
        Schema::create('bank_account_log', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 15, 2);
            $table->string('type')->nullable();
            $table->bigInteger('bank_accounts_id')->unsigned()->index()->nullable();
            $table->foreign('bank_accounts_id')->references('id')->on('bank_accounts')->onDelete('cascade');
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_account_log');
    }
};
