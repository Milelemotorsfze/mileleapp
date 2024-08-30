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
        Schema::table('work_orders', function (Blueprint $table) {
            // Adding the sales_person_id column and setting it as a foreign key
            $table->unsignedBigInteger('sales_person_id')->nullable()->after('id');

            // Setting up the foreign key constraint
            $table->foreign('sales_person_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null'); // You can choose 'cascade' or 'restrict' depending on your needs
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            // Dropping the foreign key constraint
            $table->dropForeign(['sales_person_id']);

            // Dropping the sales_person_id column
            $table->dropColumn('sales_person_id');
        });
    }
};
