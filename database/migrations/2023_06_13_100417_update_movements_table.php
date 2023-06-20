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
        Schema::table('movements', function (Blueprint $table) {
            $table->bigInteger('from')->unsigned()->index()->nullable()->change();
            $table->foreign('from')->references('id')->on('warehouse')->onDelete('cascade');
            $table->bigInteger('to')->unsigned()->index()->nullable()->change();
            $table->foreign('to')->references('id')->on('warehouse')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('movements', function (Blueprint $table) {
            $table->dropForeign(['from']);
            $table->dropForeign(['to']);

            $table->string('from')->change();
            $table->string('to')->change();
        });
    }
};
