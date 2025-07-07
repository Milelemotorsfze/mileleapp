<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('calls', function (Blueprint $table) {
            $table->text('testing_remarks_data_migration')->nullable()->after('remarks');
        });
    }

    public function down(): void
    {
        Schema::table('calls', function (Blueprint $table) {
            $table->dropColumn('testing_remarks_data_migration');
        });
    }
};
