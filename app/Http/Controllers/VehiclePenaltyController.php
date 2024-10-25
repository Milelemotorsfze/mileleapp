<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WOVehicles;
use Carbon\Carbon;

class VehiclePenaltyController extends Controller
{
    public function store(Request $request) {
        dd('hi');
    }
    public function getVehiclePenaltyReport() {
        $today = Carbon::today();
    
        // Get all `vehicles` where the declaration date is 29 days ago or earlier
        $vehicles = WOVehicles::whereHas('woBoe', function ($query) use ($today) {
                // Filter by declaration_date from the related WOBOE records
                $query->where('declaration_date', '<=', $today->subDays(29));
            })
            ->with(['woBoe', 'woBoe.workOrder.salesPerson'])  // Eager load the related WOBOE and WorkOrder relationships
            ->get();
    
        // Filter out vehicles with 'Delivered' status in PHP (since it's an appended attribute)
        $datas = $vehicles->filter(function ($vehicle) {
            return $vehicle->delivery_status !== 'Delivered';  // Only keep vehicles with non-delivered status
        });

        return view('work_order.penalty.index', compact('datas'));
    }    
}
