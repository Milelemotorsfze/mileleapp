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
        // Get the current date/time
        $now = Carbon::now();

        // 1. Clear the PO-side reservation salesperson for Purchase Orders whose reservation has expired.
        //    A PO reservation is identified by purchasing_order.sales_person_id matching the vehicle's
        //    booking_person_id; when those vehicles have passed reservation_end_date the PO reservation is over.
        $expiredPoIds = DB::table('purchasing_order')
            ->join('vehicles', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
            ->whereNotNull('purchasing_order.sales_person_id')
            ->whereColumn('vehicles.booking_person_id', 'purchasing_order.sales_person_id')
            ->whereNotNull('vehicles.reservation_end_date')
            ->where('vehicles.reservation_end_date', '<', $now)
            ->distinct()
            ->pluck('purchasing_order.id');

        if ($expiredPoIds->isNotEmpty()) {
            DB::table('purchasing_order')
                ->whereIn('id', $expiredPoIds)
                ->update(['sales_person_id' => null]);
        }

        // 2. Clear expired reservation fields on vehicles. These columns are shared with the booking
        //    module, so this also finalises expired bookings (existing behaviour, unchanged).
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
