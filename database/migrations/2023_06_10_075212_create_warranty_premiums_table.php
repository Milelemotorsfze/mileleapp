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
        Schema::create('warranty_premiums', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('warranty_policies_id')->unsigned()->index()->nullable();
            $table->foreign('warranty_policies_id')->references('id')->on('master_warranty_policies')->onDelete('cascade');          
            $table->enum('vehicle_category1', ['electric', 'non_electric']);
            $table->enum('vehicle_category2', ['normal_and_premium', 'lux_sport_exotic']);
            $table->string('eligibility_year')->nullable();
            $table->string('eligibility_milage')->nullable();
            $table->string('extended_warranty_period')->nullable();
            $table->enum('is_open_milage', ['yes', 'no']);
            $table->string('extended_warranty_milage')->nullable();
            $table->decimal('claim_limit_in_aed', 10,2);
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('updated_by')->unsigned()->index()->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('deleted_by')->unsigned()->index()->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('cascade');
            $table->enum('status', ['active','inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warranty_premiums');
    }
};
