<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('master_model_descriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('master_model_descriptions', 'others')) {
                $table->text('others')->nullable()->after('drive_train');
            }
            if (!Schema::hasColumn('master_model_descriptions', 'specialEditions')) {
                $table->text('specialEditions')->nullable()->after('others');
            }
        });
    }

    public function down(): void
    {
        Schema::table('master_model_descriptions', function (Blueprint $table) {
            if (Schema::hasColumn('master_model_descriptions', 'specialEditions')) {
                $table->dropColumn('specialEditions');
            }
            if (Schema::hasColumn('master_model_descriptions', 'others')) {
                $table->dropColumn('others');
            }
        });
    }
};
