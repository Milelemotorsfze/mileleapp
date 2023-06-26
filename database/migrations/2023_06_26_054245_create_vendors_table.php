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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_type')->comment('COMPANY,INDIVIDUAL')->nullable();
            $table->string('trade_name_or_individual_name')->nullable();
            $table->string('category')->nullable();
            $table->string('web_address')->nullable();
            $table->longText('comment')->nullable();
            $table->string('Id_number')->nullable();
            $table->string('business_registration')->nullable();
            $table->string('primary_subsidiary')->nullable();
            $table->string('reference')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('nationality')->nullable();
            $table->string('passport_copy_filr')->nullable();
            $table->string('trade_license_number')->nullable();
            $table->string('trade_license_file')->nullable();
            $table->string('trade_registration_place')->nullable();
            $table->string('vat_certificate_file')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('alternate_contact_number')->nullable();
            $table->string('fax')->nullable();
            $table->longText('address_details')->nullable();
            $table->string('address_map')->nullable();
            $table->string('preference_id')->nullable();
            $table->longText('default_shipping_address')->nullable();
            $table->longText('default_billing_address')->nullable();
            $table->string('label')->nullable();
            $table->longText('address')->nullable();
            $table->string('notes')->nullable();
            $table->string('email_preference')->nullable()->comment('Default,Yes,No');
            $table->string('print_on_check_as')->nullable();
            $table->string('send_transaction_via')->nullable()->comment('Email,Print,Vax');
            $table->string('status')->nullable()->comment('active,inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
