<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds the AED <-> PHP conversion rate used by the Proforma/Quotation
     * currency converter. Value follows the same convention as the other
     * rate settings: it is the number of AED per 1 PHP (1 PHP = 0.060 AED).
     */
    public function up(): void
    {
        $exists = DB::table('settings')->where('key', 'aed_to_php_convertion_rate')->exists();
        if (! $exists) {
            DB::table('settings')->insert([
                'name' => 'AED-PHP / PHP-AED Convertion rate',
                'key' => 'aed_to_php_convertion_rate',
                'value' => '0.060',
                'created_at' => Carbon::now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->where('key', 'aed_to_php_convertion_rate')->delete();
    }
};
