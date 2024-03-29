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
        Schema::create('addon_details', function (Blueprint $table) {
            $table->id();
            $table->enum('addon_type_name', ['P', 'D', 'DP', 'E', 'S','SP','W','K']);
            $table->bigInteger('addon_id')->unsigned()->index()->nullable();
            $table->foreign('addon_id')->references('id')->on('addons')->onDelete('cascade');
            $table->string('addon_code')->nullable();
            $table->string('part_number')->nullable();
            $table->decimal('purchase_price', 10,2)->default('0.00');
            $table->decimal('selling_price', 10,2)->default('0.00');
            $table->string('payment_condition')->nullable();
            $table->string('currency')->default('AED')->nullable();
            $table->string('lead_time')->nullable();
            $table->text('additional_remarks')->nullable();
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('updated_by')->unsigned()->index()->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('deleted_by')->unsigned()->index()->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('cascade');
            $table->enum('is_all_brands', ['yes', 'no'])->default('no');
            $table->string('image')->nullable();
            $table->string('image2')->nullable();
            $table->enum('status', ['inactive', 'active'])->default('active');
            $table->enum('fixing_charges_included', ['yes', 'no'])->default('yes');
            $table->decimal('fixing_charge_amount', 10,2)->default('0.00');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addon_details');
    }
};
