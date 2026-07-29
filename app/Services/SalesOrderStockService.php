<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalesOrderStockService
{
    /**
     * Release the undelivered stock linked to a Sales Order back to Available.
     *
     * Only vehicles that have NOT yet been delivered (gdn_id IS NULL) are released,
     * so delivered units keep their SO link for record. Clearing so_id (and the
     * reservation/booking fields) is what makes the derived stock status fall back
     * from 'Sold' to 'Available Stock'.
     *
     * @return int number of vehicles released
     */
    public function releaseUndeliveredStock(int $soId, string $reason = 'so_released'): int
    {
        $vehicleIds = DB::table('vehicles')
            ->where('so_id', $soId)
            ->whereNull('gdn_id')
            ->pluck('id');

        if ($vehicleIds->isEmpty()) {
            return 0;
        }

        DB::table('vehicles')
            ->whereIn('id', $vehicleIds)
            ->update([
                'so_id' => null,
                'reservation_start_date' => null,
                'reservation_end_date' => null,
                'booking_person_id' => null,
            ]);

        Log::info('SO stock released back to Available', [
            'so_id' => $soId,
            'reason' => $reason,
            'vehicle_ids' => $vehicleIds->all(),
            'count' => $vehicleIds->count(),
        ]);

        return $vehicleIds->count();
    }
}
