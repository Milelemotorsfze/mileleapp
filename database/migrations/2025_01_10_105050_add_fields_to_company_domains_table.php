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
        Schema::table('company_domains', function (Blueprint $table) {
            $table->string('domain_name')->unique();
            $table->string('assigned_company');
            $table->string('domain_registrar');
            $table->string('email_server')->nullable();
            $table->string('company_domain_code')->unique();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_domains', function (Blueprint $table) {
            $table->dropColumn([
                'domain_name',
                'assigned_company',
                'domain_registrar',
                'email_server',
                'company_domain_code',
                'created_by',
                'updated_by',
            ]);
        });
    }
};
