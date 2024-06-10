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
        Schema::create('w_o_vehicles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('work_order_id')->unsigned()->index()->nullable();
            $table->foreign('work_order_id')->references('id')->on('work_orders');
            $table->bigInteger('vehicle_id')->unsigned()->index()->nullable(); // FOR REFERENCE FOR MILELE MATRIX DATA IN FUTURE
            $table->foreign('vehicle_id')->references('id')->on('vehicles'); // FOR REFERENCE FOR MILELE MATRIX DATA IN FUTURE
            $table->integer('boe_number')->nullable();
            $table->string('vin')->nullable();
            $table->string('brand')->nullable();
            // $table->string('variant_details')->nullable(); // no need
            $table->string('variant')->nullable();
            $table->string('engine')->nullable();
            $table->string('model_description')->nullable();
            $table->string('model_year')->nullable();
            $table->string('model_year_to_mention_on_documents')->nullable();
            $table->string('steering')->nullable();
            $table->string('exterior_colour')->nullable();
            $table->string('interior_colour')->nullable();
            $table->string('warehouse')->nullable();
            $table->string('territory')->nullable();
            $table->string('preferred_destination')->nullable();
            $table->string('import_document_type')->nullable();
            $table->string('ownership_name')->nullable();
            $table->text('modification_or_jobs_to_perform_per_vin')->nullable();
            $table->enum('certification_per_vin', ['rta_without_number_plate', 'rta_with_number_plate','certificate_of_origin','certificate_of_conformity','qisj_inspection','eaa_inspection'])->nullable();
            $table->text('special_request_or_remarks')->nullable();
            $table->string('shipment')->nullable();
            $table->timestamps();
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->bigInteger('updated_by')->unsigned()->index()->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->softDeletes();
            $table->bigInteger('deleted_by')->unsigned()->index()->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('w_o_vehicles');
    }
};
