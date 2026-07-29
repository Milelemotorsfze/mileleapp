<?php

namespace App\Console\Commands;

use App\Mail\SoExpiredNotification;
use App\Mail\SoGdnReviewReminder;
use App\Models\User;
use App\Services\SalesOrderStockService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProcessSoGdnExpiry extends Command
{
    /**
     * @var string
     */
    protected $signature = 'so:process-gdn-expiry';

    /**
     * @var string
     */
    protected $description = 'Release stock from terminal SOs, remind salespersons in the 6 days before the 30-day GDN deadline, and auto-expire SOs with no GDN.';

    /** Days from SO date after which an unfulfilled SO auto-expires. */
    private const REVIEW_DAYS = 30;

    /** How many days before expiry the daily reminder starts. */
    private const REMINDER_LEAD_DAYS = 6;

    /**
     * Statuses that stop an SO from being processed for reminders/expiry.
     * Rejected is included here (never auto-expire it) but deliberately NOT in
     * RELEASE_STATUSES, because a rejected SO can be re-approved and must keep
     * its vehicle links.
     */
    private const SKIP_FOR_EXPIRY_STATUSES = ['Cancelled', 'Rejected', 'Expired'];

    /**
     * Statuses whose undelivered stock should be released back to Available.
     * Only genuinely terminal states — cancel() and this feature — never Rejected.
     */
    private const RELEASE_STATUSES = ['Cancelled', 'Expired'];

    public function handle(SalesOrderStockService $stock): int
    {
        $today = Carbon::today();

        $reconciled = $this->reconcileTerminalSos();

        $reminded = 0;
        $expired = 0;

        foreach ($this->unfulfilledSos() as $so) {
            try {
                $expiryDate = Carbon::parse($so->so_date)->startOfDay()->addDays(self::REVIEW_DAYS);

                if ($today->greaterThanOrEqualTo($expiryDate)) {
                    // Capture the vehicle list BEFORE releasing (release nulls so_id).
                    $vehicles = $this->undeliveredVehicles($so->id);

                    DB::transaction(function () use ($so, $stock) {
                        $stock->releaseUndeliveredStock((int) $so->id, 'auto_expired_no_gdn');
                        DB::table('so')->where('id', $so->id)->update([
                            'status' => 'Expired',
                            'expired_at' => now(),
                        ]);
                    });

                    $this->notifyExpired($so, $vehicles);
                    $expired++;
                    continue;
                }

                // Not yet due: within the 6-day lead window, send one reminder per day.
                $daysLeft = $today->diffInDays($expiryDate);
                if ($daysLeft <= self::REMINDER_LEAD_DAYS
                    && $so->expiry_last_notified_date !== $today->toDateString()) {
                    if ($this->sendReminder($so, $daysLeft, $expiryDate)) {
                        DB::table('so')->where('id', $so->id)->update([
                            'expiry_last_notified_date' => $today->toDateString(),
                        ]);
                        $reminded++;
                    }
                }
            } catch (\Throwable $e) {
                Log::error('SO GDN expiry processing failed', [
                    'so_id' => $so->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("SO GDN expiry — reminders: {$reminded}, expired: {$expired}, reconciled vehicles: {$reconciled}.");

        return 0;
    }

    /**
     * Release undelivered stock still linked to any terminal SO. Covers manual
     * cancel/reject and legacy rows that were never released.
     */
    private function reconcileTerminalSos(): int
    {
        $stuckIds = DB::table('vehicles')
            ->join('so', 'so.id', '=', 'vehicles.so_id')
            ->whereIn('so.status', self::RELEASE_STATUSES)
            ->whereNull('vehicles.gdn_id')
            // Never release stock that belongs to an SO with a Work Order (mid-logistics).
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('work_orders')
                    ->whereColumn('work_orders.so_number', 'so.so_number');
            })
            ->pluck('vehicles.id');

        if ($stuckIds->isEmpty()) {
            return 0;
        }

        DB::table('vehicles')
            ->whereIn('id', $stuckIds)
            ->update([
                'so_id' => null,
                'reservation_start_date' => null,
                'reservation_end_date' => null,
                'booking_person_id' => null,
            ]);

        Log::info('Reconciled stuck stock from terminal SOs', [
            'count' => $stuckIds->count(),
            'vehicle_ids' => $stuckIds->all(),
        ]);

        return $stuckIds->count();
    }

    /**
     * Active SOs that have linked stock but no GDN issued on any of it.
     */
    private function unfulfilledSos()
    {
        return DB::table('so')
            ->whereNotNull('so.so_date')
            ->where(function ($q) {
                $q->whereNull('so.status')
                    ->orWhereNotIn('so.status', self::SKIP_FOR_EXPIRY_STATUSES);
            })
            ->whereExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('vehicles')
                    ->whereColumn('vehicles.so_id', 'so.id')
                    ->whereNull('vehicles.gdn_id');
            })
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('vehicles')
                    ->whereColumn('vehicles.so_id', 'so.id')
                    ->whereNotNull('vehicles.gdn_id');
            })
            // Skip SOs already in the Work Order / logistics pipeline — expiring them
            // would orphan the Work Order and pull back stock mid-delivery.
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('work_orders')
                    ->whereColumn('work_orders.so_number', 'so.so_number');
            })
            ->select(
                'so.id',
                'so.so_number',
                'so.so_date',
                'so.sales_person_id',
                'so.status',
                'so.expiry_last_notified_date'
            )
            ->get();
    }

    private function undeliveredVehicles($soId)
    {
        return DB::table('vehicles')
            ->leftJoin('varaints', 'varaints.id', '=', 'vehicles.varaints_id')
            ->where('vehicles.so_id', $soId)
            ->whereNull('vehicles.gdn_id')
            ->select('vehicles.vin', 'varaints.name as variant')
            ->get();
    }

    private function sendReminder($so, int $daysLeft, Carbon $expiryDate): bool
    {
        $salesperson = $so->sales_person_id ? User::find($so->sales_person_id) : null;
        if (!$salesperson || !$salesperson->email) {
            Log::warning('SO GDN reminder skipped: no salesperson email', ['so_id' => $so->id]);
            return false;
        }

        Mail::to($salesperson->email)->send(new SoGdnReviewReminder(
            $so->so_number,
            $salesperson->name,
            Carbon::parse($so->so_date),
            $expiryDate,
            $daysLeft,
            $this->undeliveredVehicles($so->id)
        ));

        return true;
    }

    private function notifyExpired($so, $vehicles): void
    {
        $salesperson = $so->sales_person_id ? User::find($so->sales_person_id) : null;
        if (!$salesperson || !$salesperson->email) {
            Log::warning('SO expired notice skipped: no salesperson email', ['so_id' => $so->id]);
            return;
        }

        Mail::to($salesperson->email)->send(new SoExpiredNotification(
            $so->so_number,
            $salesperson->name,
            Carbon::parse($so->so_date),
            $vehicles
        ));
    }
}
