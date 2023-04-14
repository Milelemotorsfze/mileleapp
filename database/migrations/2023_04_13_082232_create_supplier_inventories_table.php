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
        Schema::create('supplier_inventories', function (Blueprint $table) {
            $table->id();
            $table->string('supplier')->comments('TTC','AMS','CPS');
            $table->string('model')->nullable();
            $table->string('sfx')->nullable();
            $table->string('chasis')->nullable();
            $table->string('engine_number')->nullable();
            $table->string('color_code')->nullable();
            $table->string('color_name')->nullable();
            $table->string('status')->nullable();
            $table->string('pord_month')->nullable();
            $table->string('po_arm')->nullable();
            $table->string('eta_import')->nullable();
            $table->string('uniques')->nullable();
            $table->date('date_of_entry')->nullable();
            $table->date('date')->nullable();
            $table->string('country')->nullable();
            $table->string('whole_sales')->nullable();
            $table->string('veh_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_inventories');
    }
};
