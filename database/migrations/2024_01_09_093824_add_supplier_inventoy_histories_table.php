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
            Schema::create('supplier_inventory_histories', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('supplier_id')->unsigned()->index()->nullable();
                $table->foreign('supplier_id')->references('id')->on('suppliers');
                $table->bigInteger('master_model_id')->unsigned()->index()->nullable();
                $table->foreign('master_model_id')->references('id')->on('master_models');
                $table->string('chasis')->nullable();
                $table->string('engine_number')->nullable();
                $table->string('color_code')->nullable();
                $table->bigInteger('interior_color_code_id')->unsigned()->index()->nullable();
                $table->foreign('interior_color_code_id')->references('id')->on('color_codes');
                $table->bigInteger('exterior_color_code_id')->unsigned()->index()->nullable();
                $table->foreign('exterior_color_code_id')->references('id')->on('color_codes');
                $table->string('status')->nullable();
                $table->string('pord_month')->nullable();
                $table->string('po_arm')->nullable();
                $table->date('eta_import')->nullable();
                $table->string('delivery_note')->nullable();
                $table->boolean('is_add_new')->nullable();
                $table->date('date_of_entry')->nullable();
                $table->string('country')->nullable();
                $table->string('whole_sales')->nullable()->comment('Trans Cars,Milele Motors');
                $table->string('veh_status')->nullable();
                $table->string('upload_status')->nullable()->comment('Active,Inactive');
                $table->bigInteger('updated_by')->unsigned()->index()->nullable();
                $table->foreign('updated_by')->references('id')->on('users');
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('supplier_inventory_histories');
        }
};
