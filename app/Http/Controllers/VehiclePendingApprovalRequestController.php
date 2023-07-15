<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ColorCode;
use App\Models\ModelHasRoles;
use App\Models\User;
use App\Models\Varaint;
use App\Models\VehicleApprovalRequests;
use App\Models\Vehicles;
use Illuminate\Http\Request;

class VehiclePendingApprovalRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statuss = "Vendor Confirmed";
        $pendingApprovalVehicleIds =  VehicleApprovalRequests::pluck('vehicle_id');
        $data = Vehicles::whereIn('id', $pendingApprovalVehicleIds)
        ->where('status', '!=', 'cancel')
//            ->where('payment_status', $statuss)
            ->get();

        $pendingVehicleDetailForApprovals = VehicleApprovalRequests::groupBy('vehicle_id')->count();
        $datapending = Vehicles::where('status', '!=', 'cancel')->whereNull('inspection_date')->get();
        $varaint = Varaint::get();
        $sales_persons = ModelHasRoles::get();
        $sales_ids = $sales_persons->pluck('model_id');
        $sales = User::whereIn('id', $sales_ids)->get();
        $exteriorColours = ColorCode::where('belong_to', 'ex')->get();
        $interiorColours = ColorCode::where('belong_to', 'int')->get();

        return view('vehicles.index', compact('data', 'varaint', 'sales', 'datapending'
            ,'exteriorColours','interiorColours','pendingVehicleDetailForApprovals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
