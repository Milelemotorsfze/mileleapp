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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['export_exw', 'export_cnf','local_sale','lto'])->nullable();
            $table->date('date')->nullable();
            $table->string('so_number')->unique()->nullable();
            $table->string('batch')->nullable();
            $table->string('wo_number')->unique()->nullable();

            $table->unsignedBigInteger('customer_reference_id')->nullable();   // FOR REFERENCE FOR MILELE MATRIX DATA IN FUTURE
            $table->string('customer_reference_type')->nullable(); // FOR REFERENCE FOR MILELE MATRIX DATA IN FUTURE
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_company_number')->nullable();
            $table->string('customer_address')->nullable();

            $table->string('customer_representative_name')->nullable();
            $table->string('customer_representative_email')->nullable();
            $table->string('customer_representative_contact')->nullable();
            // $table->string('customer_representative_details')->nullable();

            $table->string('freight_agent_name')->nullable();
            $table->string('freight_agent_email')->nullable();
            $table->string('freight_agent_contact_number')->nullable();
            // $table->string('freight_agent_details')->nullable();

            $table->string('port_of_loading')->nullable();
            $table->string('port_of_discharge')->nullable();
            $table->string('final_destination')->nullable();
            $table->enum('transport_type', ['air', 'sea','road'])->nullable();
            $table->string('brn_file')->nullable();
            $table->string('brn')->nullable();
            $table->string('container_number')->nullable();
            $table->bigInteger('airline_reference_id')->unsigned()->index()->nullable(); // FOR REFERENCE FOR MILELE MATRIX DATA IN FUTURE
            $table->foreign('airline_reference_id')->references('id')->on('master_airlines');
            $table->string('airline')->nullable();
            $table->string('airway_bill')->nullable();
            $table->string('shipping_line')->nullable();
            $table->string('forward_import_code')->nullable();
            $table->string('trailer_number_plate')->nullable();
            $table->string('transportation_company')->nullable();
            $table->string('transporting_driver_contact_number')->nullable();
            $table->string('airway_details')->nullable();
            $table->string('transportation_company_details')->nullable();

            $table->string('currency')->nullable();
            $table->decimal('so_total_amount', 10,2)->default('0.00');
            $table->integer('so_vehicle_quantity')->nullable(); 
            $table->enum('deposit_received_as', ['total_deposit', 'custom_deposit'])->nullable();
            $table->decimal('amount_received', 10,2)->default('0.00');
            $table->decimal('balance_amount', 10,2)->default('0.00');            

           

            // $table->string('units_per_boe')->nullable();
            $table->string('delivery_location')->nullable();
            $table->string('delivery_contact_person')->nullable();
            $table->date('delivery_date')->nullable();

            $table->string('signed_pfi')->nullable();
            $table->string('signed_contract')->nullable();
            $table->string('payment_receipts')->nullable();
            $table->string('noc')->nullable();
            $table->string('enduser_trade_license')->nullable();
            $table->string('enduser_passport')->nullable();
            $table->string('enduser_contract')->nullable();
            $table->string('vehicle_handover_person_id')->nullable();

            $table->bigInteger('sales_support_data_confirmation_by')->unsigned()->index()->nullable();
            $table->foreign('sales_support_data_confirmation_by')->references('id')->on('users');
            $table->datetime('sales_support_data_confirmation_at')->nullable();

            $table->bigInteger('finance_approval_by')->unsigned()->index()->nullable();
            $table->foreign('finance_approval_by')->references('id')->on('users');
            $table->datetime('finance_approved_at')->nullable();

            $table->bigInteger('coe_office_approval_by')->unsigned()->index()->nullable();
            $table->foreign('coe_office_approval_by')->references('id')->on('users');
            $table->datetime('coe_office_approved_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->bigInteger('updated_by')->unsigned()->index()->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->bigInteger('deleted_by')->unsigned()->index()->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
        });
    }
//deposit_aganist_vehicle // ONE SO HAVE MULTIPLE DEPOSIT AGANIST VEHICLE
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
