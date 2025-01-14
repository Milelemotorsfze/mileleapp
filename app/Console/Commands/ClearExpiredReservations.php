<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClearExpiredReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:clear-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear expired reservations and reset fields to null';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get the current date
        $now = Carbon::now();

        // Update rows where reservation_end_date is passed
        DB::table('vehicles')
            ->where('reservation_end_date', '<', $now)
            ->update([
                'reservation_start_date' => null,
                'reservation_end_date' => null,
                'booking_person_id' => null,
            ]);

        $this->info('Expired reservations cleared successfully.');
        return 0;
    }
}
