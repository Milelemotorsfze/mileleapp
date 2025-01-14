<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_domains', function (Blueprint $table) {
            $table->renameColumn('system_requirement', 'company_domain_code');
            $table->string('company_domain_code', 6)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('company_domains', function (Blueprint $table) {
            $table->renameColumn('company_domain_code', 'system_requirement');
            $table->string('system_requirement')->change();
        });
    }
};

