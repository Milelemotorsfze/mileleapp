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
        Schema::create('po', function (Blueprint $table) {
            $table->id();
            $table->date('po_date');
            $table->text('memo');
            $table->string('type');
            $table->date('eta');
            $table->date('expiration_on_port');
            $table->date('expiration_on_free_zone');
            $table->string('subsidiary');
            $table->string('location');
            $table->string('department');
            $table->string('amount');
            $table->string('currency');
            $table->string('status');
            $table->string('order_type');
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po');
    }
};
