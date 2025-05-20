<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Add old_model_description to master_model_descriptions
        Schema::table('master_model_descriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('master_model_descriptions', 'old_model_description')) {
                $table->string('old_model_description')->nullable()->after('id');
            }
        });

        // Add foreign key to varaints table
        Schema::table('varaints', function (Blueprint $table) {
            if (!Schema::hasColumn('varaints', 'master_model_description_id')) {
                $table->unsignedBigInteger('master_model_description_id')->nullable()->after('model_detail');
                $table->foreign('master_model_description_id')
                    ->references('id')
                    ->on('master_model_descriptions')
                    ->onDelete('set null');
                    $table->string('old_model_detail')->nullable()->after('master_model_description_id');
            }
        });
        
        // Add foreign key to w_o_vehicles table
        Schema::table('w_o_vehicles', function (Blueprint $table) {
            if (!Schema::hasColumn('w_o_vehicles', 'master_model_description_id')) {
                $table->unsignedBigInteger('master_model_description_id')->nullable()->after('model_description');
                $table->foreign('master_model_description_id')
                    ->references('id')
                    ->on('master_model_descriptions')
                    ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Rollback for w_o_vehicles
        Schema::table('w_o_vehicles', function (Blueprint $table) {
            if (Schema::hasColumn('w_o_vehicles', 'master_model_description_id')) {
                $table->dropForeign(['master_model_description_id']);
                $table->dropColumn('master_model_description_id');
            }
        });

        // Rollback for varaints
        Schema::table('varaints', function (Blueprint $table) {
            if (Schema::hasColumn('varaints', 'master_model_description_id')) {
                $table->dropForeign(['master_model_description_id']);
                $table->dropColumn('master_model_description_id');
                $table->dropColumn('old_model_detail');
            }
        });

        // Rollback for master_model_descriptions
        Schema::table('master_model_descriptions', function (Blueprint $table) {
            if (Schema::hasColumn('master_model_descriptions', 'old_model_description')) {
                $table->dropColumn('old_model_description');
            }
        });
    }
};
