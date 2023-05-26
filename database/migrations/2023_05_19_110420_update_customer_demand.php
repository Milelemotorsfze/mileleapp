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
        Schema::table('customer_demand', function (Blueprint $table) {
            $table->string('status')->nullable();
            $table->bigInteger('initial_contact_id')->unsigned()->index()->nullable();
            $table->foreign('initial_contact_id')->references('id')->on('initial_contact');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table->dropColumn('status');
    }
};
