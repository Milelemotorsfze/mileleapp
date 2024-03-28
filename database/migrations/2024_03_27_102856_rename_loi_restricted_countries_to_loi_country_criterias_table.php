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
        Schema::rename('loi_restricted_countries', 'loi_country_criterias');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('loi_country_criterias','loi_restricted_countries');
    }
};
