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
        Schema::create('quotation_lc_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('quotation_id')->unsigned()->unique();
            $table->foreign('quotation_id')->references('id')->on('quotations');

            // LC transaction identifiers
            $table->string('lc_number')->nullable();
            $table->string('issuing_bank')->nullable();
            $table->date('lc_expiry_date')->nullable();

            // Document checklist required before shipment can proceed
            $table->boolean('doc_commercial_invoice')->default(false);
            $table->boolean('doc_bill_of_lading')->default(false);
            $table->boolean('doc_packing_list')->default(false);
            $table->boolean('doc_certificate_of_origin')->default(false);
            $table->boolean('doc_inspection_certificate')->default(false);

            // pending | under_review | compliant | discrepant
            $table->string('compliance_status')->default('pending');
            $table->text('compliance_remarks')->nullable();

            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_lc_details');
    }
};
