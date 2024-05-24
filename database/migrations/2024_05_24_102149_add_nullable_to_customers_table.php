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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('document_number')->nullable()->change();
            $table->string('category')->nullable()->change();
            $table->string('source')->nullable()->change();
            $table->string('type')->nullable()->change();
            $table->string('license_file')->nullable()->change();
            $table->string('trade_license_file')->nullable()->change();
            $table->string('passport_file')->nullable()->change();
            $table->string('national_id_file')->nullable()->change();
            $table->string('status')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('document_number')->change();
            $table->string('category')->change();
            $table->string('source')->change();
            $table->string('type')->change();
            $table->string('trade_license_file')->change();
            $table->string('passport_file')->change();
            $table->string('national_id_file')->change();
            $table->string('status')->change();
            $table->string('license_file')->change();
        });
    }
};
