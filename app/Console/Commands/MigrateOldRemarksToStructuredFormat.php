<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateOldRemarksToStructuredFormat extends Command
{
    protected $signature = 'remarks:migrate-test';
    protected $description = 'Migrate old-style remarks to structured format and store in testing_remarks_data_migration';

   public function handle()
{
    $rows = DB::table('calls')
        ->where('remarks', '!=', null)
        ->where('remarks', 'NOT LIKE', '%###SEP###%')
        ->get();

    $this->info("Found " . count($rows) . " rows to process.");

    foreach ($rows as $row) {
        $original = trim($row->remarks);
        if (!$original) continue;

        $plain = strip_tags($original);
        $plain = html_entity_decode($plain);

        $isStructured = preg_match('/1\.\s*(Purpose of Purchase|Car Interested in)/i', $plain);

        if ($isStructured) {

            $normalized = preg_replace('/\s*;\s*/', '###SEP###', $plain);
            $normalized = preg_replace('/(?<=\d\.)\s+/', ' ', $normalized);
            $normalized = preg_replace('/\s{2,}/', ' ', $normalized);

            $normalized = str_replace([
                '1. Car Interested in:',
                '1. Car Interested In:',
                '2. Purpose of Purchase:',
                '3. End User:',
                '4. Destination Country:',
                '5. Planned Units:',
                '6. Experience with UAE Sourcing:',
                '6. Shipping Assistance Needed:',
                '7. Shipping Assistance Required:',
                '8. Payment Method:',
                '9. Previous Purchase History:',
                '10. Purchase Timeline:',
                'General Remark / Additional Notes:',
            ], [
                '1. Car Interested In:',
                '1. Car Interested In:',
                '2. Purpose of Purchase:',
                '3. End User:',
                '4. Destination Country:',
                '5. Planned Units:',
                '6. Experience with UAE Sourcing:',
                '7. Shipping Assistance Required:',
                '7. Shipping Assistance Required:',
                '8. Payment Method:',
                '9. Previous Purchase History:',
                '10. Purchase Timeline:',
                '###SEP###General Remark / Additional Notes:',
            ], $normalized);

            $parts = preg_split('/(?=\d{1,2}\. )/', $normalized);
            $structured = 'Lead Summary - Qualification Notes:';
            foreach ($parts as $part) {
                $structured .= '###SEP###' . trim($part);
            }

        } else {
            $cleaned = preg_replace('/\s+/', ' ', $plain);
            $structured = 'Lead Summary - Qualification Notes:###SEP###General Remark / Additional Notes: ' . trim($cleaned);
        }

        DB::table('calls')
            ->where('id', $row->id)
            ->update(['testing_remarks_data_migration' => $structured]);

        $this->line("Updated ID {$row->id}");
    }

    $this->info("✅ Migration completed with structured + general handling.");
}

}
