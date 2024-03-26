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
        Schema::table('loi_restricted_countries', function (Blueprint $table) {
            $table->integer('max_qty_per_passport')->nullable()->after('country_id');
            $table->integer('min_qty_per_passport')->nullable()->after('country_id');
            $table->boolean('is_only_company_allowed')->nullable()->after('country_id')->default(0);
            $table->boolean('is_loi_restricted')->nullable()->after('country_id')->default(0);
            $table->integer('is_inflate_qty')->nullable()->after('country_id')->default(0);;
            $table->integer('is_longer_lead_time')->nullable()->after('country_id')->default(0);
            $table->bigInteger('master_model_line_id')->unsigned()->index()->nullable()->after('country_id');
            $table->foreign('master_model_line_id')->references('id')->on('master_model_lines');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loi_restricted_countries', function (Blueprint $table) {
            $table->dropColumn('max_qty_per_passport');
            $table->dropColumn('min_qty_per_passport');
            $table->dropColumn('is_only_company_allowed');
            $table->dropColumn('is_loi_restricted');
            $table->dropColumn('is_inflate_qty');
            $table->dropColumn('is_longer_lead_time');
            $table->dropForeign(['master_model_line_id']);
            $table->dropColumn('master_model_line_id');

        });
    }
};
