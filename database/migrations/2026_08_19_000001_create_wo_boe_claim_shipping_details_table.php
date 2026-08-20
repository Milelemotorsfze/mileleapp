<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Per-VIN shipping details captured on the claim screen.
     *
     * Claim side only - these values are independent of the work order level
     * container_number / final_destination columns and never read or write them.
     */
    public function up(): void
    {
        Schema::create('wo_boe_claim_shipping_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wo_boe_id');
            $table->unsignedBigInteger('w_o_vehicle_id');
            $table->unsignedBigInteger('wo_boe_claim_id')->nullable();
            $table->string('container_number', 100)->nullable();
            $table->string('bl_number', 100)->nullable();
            $table->unsignedBigInteger('final_destination_country_id')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            // One row per VIN per BOE - makes it impossible for two VINs to share a record.
            $table->unique(['wo_boe_id', 'w_o_vehicle_id'], 'wo_boe_claim_shipping_boe_vehicle_unique');

            $table->index('container_number', 'wobcsd_container_number_idx');
            $table->index('bl_number', 'wobcsd_bl_number_idx');

            // Explicit short constraint names - the auto-generated ones exceed
            // MySQL's 64 character identifier limit for this table name.
            $table->foreign('wo_boe_id', 'wobcsd_wo_boe_id_fk')
                ->references('id')->on('wo_boe')->onDelete('cascade');
            $table->foreign('w_o_vehicle_id', 'wobcsd_w_o_vehicle_id_fk')
                ->references('id')->on('w_o_vehicles')->onDelete('cascade');
            $table->foreign('wo_boe_claim_id', 'wobcsd_wo_boe_claim_id_fk')
                ->references('id')->on('wo_boe_claims')->onDelete('set null');
            $table->foreign('final_destination_country_id', 'wobcsd_country_id_fk')
                ->references('id')->on('countries');
            $table->foreign('created_by', 'wobcsd_created_by_fk')
                ->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by', 'wobcsd_updated_by_fk')
                ->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wo_boe_claim_shipping_details');
    }
};
