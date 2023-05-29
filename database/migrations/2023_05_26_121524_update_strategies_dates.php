<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('strategies_dates', function (Blueprint $table) {
            $table->dropForeign(['strategies_id']); // Drop the existing foreign key constraint
            $table->foreign('strategies_id')->references('id')->on('strategies')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('strategies_dates', function (Blueprint $table) {
            $table->dropForeign(['strategies_id']);
            // If you need to revert the changes, you can define the previous foreign key constraint here
            // Example: $table->foreign('strategies_id')->references('id')->on('strategies');
        });
    }
};
