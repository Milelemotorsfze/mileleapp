<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ColorCode;
use App\Models\ModelHasRoles;
use App\Models\Solog;
use App\Models\User;
use App\Models\Varaint;
use App\Models\VehicleApprovalRequests;
use App\Models\Vehicles;
use App\Models\Vehicleslog;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VehiclePendingApprovalRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statuss = "Vendor Confirmed";
        $pendingApprovalVehicleIds =  VehicleApprovalRequests::where('status','Pending')->pluck('vehicle_id');
        $data = Vehicles::whereIn('id', $pendingApprovalVehicleIds)
                ->where('status', '!=', 'cancel')
//            ->where('payment_status', $statuss)
            ->get();
        $pendingVehicleDetailForApprovals = VehicleApprovalRequests::where('status','Pending')
                                            ->groupBy('vehicle_id')->get();
        $pendingVehicleDetailForApprovalCount = $pendingVehicleDetailForApprovals->count();

        $datapending = Vehicles::where('status', '!=', 'cancel')->whereNull('inspection_date')->get();
        $varaint = Varaint::get();
        $sales_persons = ModelHasRoles::get();
        $sales_ids = $sales_persons->pluck('model_id');
        $sales = User::whereIn('id', $sales_ids)->get();
        $exteriorColours = ColorCode::where('belong_to', 'ex')->get();
        $interiorColours = ColorCode::where('belong_to', 'int')->get();

        return view('vehicles.index', compact('data', 'varaint', 'sales', 'datapending'
            ,'exteriorColours','interiorColours','pendingVehicleDetailForApprovalCount'));
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
    public function ApproveOrRejectVehicleDetails(Request $request) {
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);

        DB::beginTransaction();
        $pendingApprovalRequest = VehicleApprovalRequests::find($request->id);
        $pendingApprovalRequest->status = $request->status;
        if($request->status == 'approved') {
            $pendingApprovalRequest->approved_by = Auth::id();
        }
        $pendingApprovalRequest->save();
        $field = $pendingApprovalRequest->field;
        $oldValue = $pendingApprovalRequest->old_value;
        $newValue = $pendingApprovalRequest->new_value;

        $vehicle = Vehicles::find($pendingApprovalRequest->vehicle_id);
        $vehicle->$field = $newValue;
        $vehicle->save();
        if($field == 'inspection_date' || $field == 'varaints_id' || $field == 'engine' || $field == 'ex_colour'
            || $field == 'int_colour' || $field == 'ppmmyyy' || $field == 'reservation_start_date' || $field == 'reservation_end_date')
        {
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Update QC Values';
            $vehicleslog->vehicles_id = $vehicle->id;
            $vehicleslog->field = $field;
            $vehicleslog->old_value = $oldValue;
            $vehicleslog->new_value = $newValue;
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->save();
        }

        if($field == 'so_number' || $field->so_date)
        {
            $solog = new Solog();
            $solog->time = $currentDateTime->toTimeString();
            $solog->date = $currentDateTime->toDateString();
            $solog->status = 'Update Sales Values';
            $solog->so_id = $vehicle->so_id;
            $solog->field = 'so_date';
            $solog->old_value = $oldValue;
            $solog->new_value = $newValue;
            $solog->created_by = auth()->user()->id;
            $solog->save();
        }

        DB::commit();

        return response(true);
    }
    public function update(Request $request, string $id)
    {


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}