<?php

namespace App\Observers;

use App\Models\Vehicles;
use App\Models\WOVehicleDeliveryStatus;
use App\Models\WOVehicleStatus;
use App\Models\Warehouse;
use App\Models\Masters\MasterOfficeLocation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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

        // Handle location synchronization
        if ($vehicle->isDirty('latest_location')) {
            $this->syncLocationToWorkOrders($vehicle);
        }
    }

    /**
     * Sync vehicle location changes to Work Order modules
     */
    private function syncLocationToWorkOrders(Vehicles $vehicle)
    {
        try {
            $oldLocation = $vehicle->getOriginal('latest_location');
            $newLocation = $vehicle->latest_location;
            
            Log::info('Vehicle location update detected', [
                'vehicle_id' => $vehicle->id,
                'vin' => $vehicle->vin,
                'old_location' => $oldLocation,
                'new_location' => $newLocation,
                'updated_by' => auth()->user()->email ?? 'system',
                'timestamp' => now(),
            ]);

            // Get warehouse name for the new location
            $warehouse = Warehouse::find($newLocation);
            if (!$warehouse) {
                Log::warning('Warehouse not found for location ID: ' . $newLocation);
                return;
            }

            // Find corresponding master_office_location
            $masterLocation = $this->findMasterLocationByWarehouseName($warehouse->name);
            
            if (!$masterLocation) {
                Log::warning('Master office location not found for warehouse: ' . $warehouse->name);
                return;
            }

            // Update Work Order Vehicle Status locations
            $this->updateWorkOrderVehicleStatus($vehicle, $masterLocation->id);
            
            // Update Work Order Vehicle Delivery Status locations
            $this->updateWorkOrderDeliveryStatus($vehicle, $masterLocation->id, $warehouse->name);

        } catch (\Exception $e) {
            Log::error('Error syncing vehicle location to work orders', [
                'vehicle_id' => $vehicle->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Find master office location by warehouse name
     */
    private function findMasterLocationByWarehouseName($warehouseName)
    {
        // Direct name match first
        $location = MasterOfficeLocation::where('name', $warehouseName)->first();
        
        if ($location) {
            return $location;
        }

        // Handle common mappings
        $mappings = [
            'Customer' => 'Customer',
            'Yard' => 'Yard', 
            'Showroom' => 'Showroom',
            'Warehouse' => 'Warehouse',
            'Port' => 'Port',
            'Transit' => 'Transit'
        ];

        $mappedName = $mappings[$warehouseName] ?? $warehouseName;
        return MasterOfficeLocation::where('name', 'like', '%' . $mappedName . '%')->first();
    }

    /**
     * Update Work Order Vehicle Status locations
     */
    private function updateWorkOrderVehicleStatus(Vehicles $vehicle, $masterLocationId)
    {
        $woVehicles = $vehicle->woVehicle;
        
        foreach ($woVehicles as $woVehicle) {
            // Update the latest status record
            $latestStatus = $woVehicle->latestModificationStatus;
            if ($latestStatus) {
                $latestStatus->update([
                    'vehicle_available_location' => $masterLocationId,
                    'current_vehicle_location' => 'Updated from vehicle location sync'
                ]);
            }
        }
    }

    /**
     * Update Work Order Vehicle Delivery Status locations and auto-update delivery status
     */
    private function updateWorkOrderDeliveryStatus(Vehicles $vehicle, $masterLocationId, $warehouseName)
    {
        $woVehicles = $vehicle->woVehicle;
        
        foreach ($woVehicles as $woVehicle) {
            // Update the latest delivery status record
            $latestDeliveryStatus = $woVehicle->latestDeliveryStatus;
            if ($latestDeliveryStatus) {
                $updateData = [
                    'location' => $masterLocationId
                ];

                // Auto-update delivery status based on location
                if ($warehouseName === 'Customer') {
                    $updateData['status'] = 'Delivered';
                    $updateData['delivered_at'] = now();
                } elseif (in_array($warehouseName, ['Yard', 'Showroom', 'Warehouse'])) {
                    $updateData['status'] = 'Ready';
                }

                $latestDeliveryStatus->update($updateData);
                
                Log::info('Updated Work Order delivery status', [
                    'wo_vehicle_id' => $woVehicle->id,
                    'vehicle_id' => $vehicle->id,
                    'new_location' => $masterLocationId,
                    'warehouse_name' => $warehouseName,
                    'status_updated' => isset($updateData['status'])
                ]);
            }
        }
    }
}
