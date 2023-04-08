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
        Schema::create('migration_demand_list', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('demand_id')->unsigned()->index()->nullable();
            $table->foreign('demand_id')->references('id')->on('demands')->onDelete('cascade');
            
            $table->bigInteger('student_id')->unsigned()->index()->nullable();
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');

            $table->bigInteger('varaint_id')->unsigned()->index()->nullable();
            $table->foreign('varaint_id')->references('id')->on('varaints')->onDelete('cascade');
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
        Schema::dropIfExists('migration_demand_list');
    }
};
