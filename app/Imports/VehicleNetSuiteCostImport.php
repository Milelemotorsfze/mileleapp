<?php

namespace App\Imports;

use App\Models\Vehicles;
use App\Models\VehicleNetsuiteCost;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VehicleNetSuiteCostImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
{
    $vehicle = Vehicles::where('vin', $row['vin'])->first();

    if ($vehicle) {
        $vehicleId = $vehicle->id;
        $userId = Auth::id();
        $vin = $row['vin'];
        
        // Dynamically create the netsuite_link
        $netsuiteLink = "https://5676368.app.netsuite.com/app/common/search/searchresults.nl?searchtype=Transaction&BRX_CUSTRECORD_ADVS_VEH_VINtype=IS&BRX_CUSTRECORD_ADVS_VEH_VIN={$vin}&detail=BRX_CUSTRECORD_ADVS_VEH_VIN&detailname={$vin}";

        $existingRecord = VehicleNetsuiteCost::where('vehicles_id', $vehicleId)->first();
        if ($existingRecord) {
            $existingRecord->update([
                'cost' => $row['cost'],
                'netsuite_link' => $netsuiteLink,
                'created_by' => $userId,
            ]);
        } else {
            return new VehicleNetsuiteCost([
                'vehicles_id' => $vehicleId,
                'cost' => $row['cost'],
                'netsuite_link' => $netsuiteLink,
                'created_by' => $userId,
            ]);
        }
    }
}
}
