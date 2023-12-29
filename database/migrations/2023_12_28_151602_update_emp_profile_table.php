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
        Schema::table('emp_profile', function (Blueprint $table) {
            $table->text('offer_sign')->nullable();
            $table->datetime('offer_signed_at')->nullable();
            $table->bigInteger('offer_letter_hr_id')->unsigned()->index()->nullable();
            $table->foreign('offer_letter_hr_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emp_profile', function (Blueprint $table) {
            $table->dropColumn('offer_sign');
            $table->dropColumn('offer_signed_at');
            $table->dropIndex(['offer_letter_hr_id']);
            $table->dropForeign(['offer_letter_hr_id']);
            $table->dropColumn('offer_letter_hr_id');
        });
    }
};
