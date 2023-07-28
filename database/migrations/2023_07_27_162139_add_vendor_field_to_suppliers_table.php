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
        Schema::table('suppliers', function (Blueprint $table)
        {
            $table->string('type')->comment('Individual,Company')->nullable()->after('supplier');
            $table->string('trade_licence_number')->nullable()->after('email');
            $table->string('trade_registration_place')->nullable()->after('email');
            $table->string('passport_number')->nullable()->after('email');
            $table->string('nationality')->nullable()->after('email');
            $table->longText('address')->nullable()->after('email');
            $table->string('fax')->nullable()->after('email');
            $table->string('passport_copy_file')->nullable()->after('email');
            $table->string('trade_license_number')->nullable()->after('email');
            $table->string('trade_license_file')->nullable()->after('email');
            $table->string('vat_certificate_file')->nullable()->after('email');
            $table->string('prefered_id')->nullable()->after('email');
            $table->string('prefered_label')->nullable()->after('email');
            $table->longText('shipping_address')->nullable()->after('email');
            $table->longText('billing_address')->nullable()->after('email');
            $table->string('notes')->nullable()->after('email');
            $table->longText('comment')->nullable()->after('email');
            $table->string('web_address')->nullable()->after('email');
            $table->string('communication_channels')->nullable()->after('email');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            //
        });
    }
};
