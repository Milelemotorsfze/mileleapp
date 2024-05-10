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
        Schema::table('letter_of_indents', function (Blueprint $table) {
            $table->dropColumn('so_number');
            $table->date('loi_approval_date')->after('dealers');
            $table->bigInteger('sales_person_id')->unsigned()->index()->nullable()->after('customer_id');
            $table->foreign('sales_person_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('letter_of_indents', function (Blueprint $table) {
            $table->string('so_number');
            $table->dropColumn('loi_approval_date');
            $table->dropForeign(['sales_person_id']);
            $table->dropColumn('sales_person_id');
        });
    }
};
