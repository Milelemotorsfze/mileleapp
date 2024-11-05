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
        Schema::table('calls_requirement', function (Blueprint $table) {
            $table->string('trim')->nullable();
            $table->string('variant')->nullable();
            $table->string('qty')->nullable();
            $table->string('asking_price')->nullable();
            $table->string('offer_price')->nullable();
            $table->bigInteger('countries_id')->unsigned()->index()->nullable();
            $table->foreign('countries_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('calls_requirement', function (Blueprint $table) {
        $table->dropForeign(['countries_id']); // Drop the foreign key
        $table->dropColumn(['trim', 'variant', 'qty', 'countries_id', 'asking_price', 'asking_price']); // Drop the columns
    });
}
};
