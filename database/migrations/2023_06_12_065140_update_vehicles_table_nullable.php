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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('ex_colour')->nullable()->change();
            $table->string('int_colour')->nullable()->change();
            // Replace 'column_name' with the actual column name you want to make nullable
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('ex_colour')->change();
            $table->string('int_colour')->change();
            // Replace 'column_name' with the actual column name
            // and its original data type (e.g., string, integer, etc.)
        });
    }
};
