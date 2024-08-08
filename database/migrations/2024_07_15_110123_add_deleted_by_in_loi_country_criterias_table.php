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
        Schema::table('loi_country_criterias', function (Blueprint $table) {
            $table->softDeletes()->after('updated_by');
            $table->bigInteger('created_by')->unsigned()->index()->nullable()->after('updated_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->bigInteger('deleted_by')->unsigned()->index()->nullable()->after('updated_by');
            $table->foreign('deleted_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loi_country_criterias', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });
    }
};
