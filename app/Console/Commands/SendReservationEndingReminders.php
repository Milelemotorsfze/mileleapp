<?php

namespace App\Console\Commands;

use App\Mail\ReservationEndingReminder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendReservationEndingReminders extends Command
{
    /**
     * @var string
     */
    protected $signature = 'reservations:ending-reminder';

    /**
     * @var string
     */
    protected $description = 'Email reservation salespersons whose PO reservation ends within 4 days and has no Sales Order yet';

    public function handle()
    {
        $today = Carbon::today();
        $threshold = Carbon::today()->addDays(4);

        // Active PO reservations (salesperson set + matching vehicle booking) ending within the next 4 days,
        // not yet moved to a Sales Order and not delivered. One reminder per PO.
        $pos = DB::table('purchasing_order')
            ->join('vehicles', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
            ->whereNotNull('purchasing_order.sales_person_id')
            ->whereColumn('vehicles.booking_person_id', 'purchasing_order.sales_person_id')
            ->whereNull('vehicles.so_id')
            ->whereNull('vehicles.gdn_id')
            ->whereNotNull('vehicles.reservation_end_date')
            ->whereDate('vehicles.reservation_end_date', '>', $today)
            ->whereDate('vehicles.reservation_end_date', '<=', $threshold)
            ->select(
                'purchasing_order.id',
                'purchasing_order.po_number',
                'purchasing_order.sales_person_id',
                DB::raw('MIN(vehicles.reservation_end_date) as reservation_end_date')
            )
            ->groupBy('purchasing_order.id', 'purchasing_order.po_number', 'purchasing_order.sales_person_id')
            ->get();

        $sent = 0;
        foreach ($pos as $po) {
            try {
                $salesperson = User::find($po->sales_person_id);
                if (!$salesperson || !$salesperson->email) {
                    continue;
                }

                // Dates are stored at midnight, so this is an exact whole-day count.
                $daysLeft = $today->diffInDays(Carbon::parse($po->reservation_end_date)->startOfDay());
                if ($daysLeft < 1) {
                    $daysLeft = 1;
                }

                $items = DB::table('purchasing_order_items')
                    ->join('varaints', 'purchasing_order_items.variant_id', '=', 'varaints.id')
                    ->where('purchasing_order_items.purchasing_order_id', $po->id)
                    ->select('varaints.name as variant', 'purchasing_order_items.qty as qty')
                    ->get();

                Mail::to($salesperson->email)->send(
                    new ReservationEndingReminder($po->po_number, $salesperson->name, $items, $daysLeft)
                );
                $sent++;
            } catch (\Throwable $e) {
                Log::error('Failed to send reservation ending reminder', [
                    'purchasing_order_id' => $po->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Reservation ending reminders sent: {$sent}");
        return 0;
    }
}
