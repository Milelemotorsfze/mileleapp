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
        Schema::table('suppliers', function (Blueprint $table) {
            $table->bigInteger('person_contact_by')->unsigned()->index()->nullable()->after('contact_person');
            $table->foreign('person_contact_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropIndex('person_contact_by');
            $table->dropForeign('person_contact_by');
            $table->dropColumn('person_contact_by');
        });
    }
};
