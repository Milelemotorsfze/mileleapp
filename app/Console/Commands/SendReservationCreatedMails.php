<?php

namespace App\Console\Commands;

use App\Mail\ReservationCreatedNotification;
use App\Models\PurchasingOrder;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendReservationCreatedMails extends Command
{
    /**
     * @var string
     */
    protected $signature = 'reservations:send-created-mails';

    /**
     * @var string
     */
    protected $description = 'Send the "reservation created" email (queued at PO creation) the next morning';

    public function handle()
    {
        // POs created with a reservation salesperson whose "created" email hasn't been sent yet.
        $pos = PurchasingOrder::where('reservation_created_mail_pending', true)->get();

        $sent = 0;
        foreach ($pos as $po) {
            try {
                // Only send if the reservation is still active (not cancelled/expired in the meantime).
                if ($po->sales_person_id) {
                    $salesperson = User::find($po->sales_person_id);
                    if ($salesperson && $salesperson->email) {
                        $items = DB::table('purchasing_order_items')
                            ->join('varaints', 'purchasing_order_items.variant_id', '=', 'varaints.id')
                            ->where('purchasing_order_items.purchasing_order_id', $po->id)
                            ->select('varaints.name as variant', 'purchasing_order_items.qty as qty')
                            ->get();

                        Mail::to($salesperson->email)->send(
                            new ReservationCreatedNotification($po->po_number, $salesperson->name, $items)
                        );
                        $sent++;
                    }
                }

                // Clear the flag whether or not a mail went out, so it is processed only once.
                $po->reservation_created_mail_pending = false;
                $po->save();
            } catch (\Throwable $e) {
                Log::error('Failed to send reservation created email (morning batch)', [
                    'purchasing_order_id' => $po->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Reservation created emails sent: {$sent}");
        return 0;
    }
}
