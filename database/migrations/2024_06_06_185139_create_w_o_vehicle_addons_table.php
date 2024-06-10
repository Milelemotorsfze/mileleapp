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
        Schema::create('w_o_vehicle_addons', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('w_o_vehicle_id')->unsigned()->index()->nullable();
            $table->foreign('w_o_vehicle_id')->references('id')->on('w_o_vehicles');
            $table->unsignedBigInteger('addon_reference_id')->nullable();   // FOR REFERENCE FOR MILELE MATRIX DATA IN FUTURE
            $table->string('addon_reference_type')->nullable(); // FOR REFERENCE FOR MILELE MATRIX DATA IN FUTURE
            $table->string('addon_code')->nullable();
            $table->string('addon_name')->nullable();
            $table->string('addon_name_description')->nullable();
            $table->integer('addon_quantity')->nullable();
            $table->string('addon_description')->nullable();
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->bigInteger('updated_by')->unsigned()->index()->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->softDeletes();
            $table->bigInteger('deleted_by')->unsigned()->index()->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('w_o_vehicle_addons');
    }
};
