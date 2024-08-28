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
        Schema::table('pfi_items', function (Blueprint $table) {
            $table->bigInteger('parent_pfi_item_id')->unsigned()->index()->nullable()->after('pfi_id');
            $table->foreign('parent_pfi_item_id')->references('id')->on('pfi_items');
            $table->boolean('is_parent')->default(0)->after('pfi_id');
            $table->string('code')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pfi_items', function (Blueprint $table) {
            $table->dropForeign(['parent_pfi_item_id']);
            $table->dropColumn('parent_pfi_item_id');
            $table->dropColumn('is_parent')->default(0);
            $table->dropColumn('code');
        });
    }
};
