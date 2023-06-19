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
        Schema::table('addon_selling_prices', function (Blueprint $table) {
            $table->bigInteger('status_updated_by')->unsigned()->index()->nullable();
            $table->foreign('status_updated_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        
    }
};
