<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('calls', function (Blueprint $table) {
        $table->dropForeign(['brand_id']);
        $table->dropForeign(['model_line_id']);
    });
    Schema::table('calls', function (Blueprint $table) {
        $table->dropIndex(['brand_id']);
        $table->dropIndex(['model_line_id']);
    });
    Schema::table('calls', function (Blueprint $table) {
        $table->dropColumn('brand_id');
        $table->dropColumn('model_line_id');
    });
}
public function down()
{
    Schema::table('calls', function (Blueprint $table) {
        $table->string('brand_id');
        $table->string('model_line_id');
    });
    Schema::table('calls', function (Blueprint $table) {
        $table->index('brand_id');
        $table->index('model_line_id');
    });
    Schema::table('calls', function (Blueprint $table) {
        $table->foreign('brand_id')->references('id')->on('brands');
        $table->foreign('model_line_id')->references('id')->on('master_model_lines');
    });
}
};
