<?php

namespace App\Observers;

use App\Models\Vehicles;
use Illuminate\Support\Facades\Log;

class VehicleObserver
{
    public function updating(Vehicles $vehicle)
    {
        if ($vehicle->isDirty('so_id')) {
            Log::info('so_id update detected on vehicle (central log)', [
                'vehicle_id' => $vehicle->id,
                'old_so_id' => $vehicle->getOriginal('so_id'),
                'new_so_id' => $vehicle->so_id,
                'updated_by' => auth()->user()->email ?? 'system',
                'timestamp' => now(),
            ]);
        }

        if ($vehicle->isDirty('varaints_id')) {
            Log::info('varaints_id update detected on vehicle (variant change)', [
                'vehicle_id' => $vehicle->id,
                'old_varaints_id' => $vehicle->getOriginal('varaints_id'),
                'new_varaints_id' => $vehicle->varaints_id,
                'updated_by' => auth()->user()->email ?? 'system',
                'timestamp' => now(),
            ]);
        }
    }
}
