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
        Schema::table('master_model_descriptions', function (Blueprint $table) {
        $table->bigInteger('created_by')->unsigned()->index()->nullable();
        $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        $table->bigInteger('updated_by')->unsigned()->index()->nullable();
        $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        $table->bigInteger('deleted_by')->unsigned()->index()->nullable();
        $table->foreign('deleted_by')->references('id')->on('users')->onDelete('cascade');
        $table->softDeletes();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
