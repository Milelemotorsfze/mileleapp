<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('gdn', function (Blueprint $table) {
            if (Schema::hasColumn('gdn', 'deleted_at')) {
                $table->dropColumn('deleted_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('gdn', function (Blueprint $table) {
            if (!Schema::hasColumn('gdn', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }
};
