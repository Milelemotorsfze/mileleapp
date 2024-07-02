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
        Schema::table('purchasing_order', function (Blueprint $table) {
            // Changing columns to unsigned big integer
            $table->unsignedBigInteger('pol')->change();
            $table->unsignedBigInteger('pod')->change();
            $table->unsignedBigInteger('fd')->change();

            // Adding foreign key constraints
            $table->foreign('pol')->references('id')->on('master_shipping_ports')->onDelete('cascade');
            $table->foreign('pod')->references('id')->on('master_shipping_ports')->onDelete('cascade');
            $table->foreign('fd')->references('id')->on('countries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchasing_order', function (Blueprint $table) {
            // Dropping foreign key constraints
            $table->dropForeign(['pol']);
            $table->dropForeign(['pod']);
            $table->dropForeign(['fd']);
        });

        Schema::table('purchasing_order', function (Blueprint $table) {
            // Changing columns to integer (intermediate type)
            $table->integer('pol')->nullable()->change();
            $table->integer('pod')->nullable()->change();
            $table->integer('fd')->nullable()->change();
        });

        Schema::table('purchasing_order', function (Blueprint $table) {
            // Changing columns to string
            $table->string('pol', 255)->nullable()->change();
            $table->string('pod', 255)->nullable()->change();
            $table->string('fd', 255)->nullable()->change();
        });
    }
};