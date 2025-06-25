<?php

namespace App\Http\Controllers;

use App\Events\DataUpdatedEvent;
use App\Http\Controllers\Controller;
use App\Models\AvailableColour;
use App\Models\ColorCode;
use App\Models\ModelHasRoles;
use App\Models\So;
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
                ->paginate(100);
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

        $field = $pendingApprovalRequest->field;
        $oldValue = $pendingApprovalRequest->old_value;
        $newValue = $pendingApprovalRequest->new_value;

        $vehicle = Vehicles::find($pendingApprovalRequest->vehicle_id);

        if($field == 'inspection_date' || $field == 'varaints_id' || $field == 'engine' || $field == 'ex_colour' || $field == 'qc_remarks'
            || $field == 'pdi_remarks' ||  $field == 'grn_remark'|| $field == 'extra_features' || $field == 'int_colour' ||
            $field == 'ppmmyyy' || $field == 'reservation_start_date' || $field == 'reservation_end_date' || $field == 'netsuit_grn_date'
            || $field == 'netsuit_grn_number' || $field == 'vin')
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
        if($field == 'ex_colour'){
            $isPriceAvailable = AvailableColour::where('varaint_id', $vehicle->varaints_id)
                ->where('int_colour', $vehicle->int_colour)
                ->where('ext_colour', $pendingApprovalRequest->new_value)
                ->first();
            if($isPriceAvailable) {
                $vehicle->price = $isPriceAvailable->price;
            }else {
                $vehicle->price = null;
            }
        }
        if($field == 'int_colour' ){

            $isPriceAvailable = AvailableColour::where('varaint_id', $vehicle->varaints_id)
                ->where('int_colour', $pendingApprovalRequest->new_value)
                ->where('ext_colour', $vehicle->ex_colour)
                ->first();
            if($isPriceAvailable) {
                $vehicle->price = $isPriceAvailable->price;
            }else {
                $vehicle->price = null;
            }
        }
        if($field == 'varaints_id' ){
            $isPriceAvailable = AvailableColour::where('varaint_id', $pendingApprovalRequest->new_value)
                ->where('int_colour', $vehicle->int_colour)
                ->where('ext_colour', $vehicle->ex_colour)
                ->first();
            if($isPriceAvailable) {
                $vehicle->price = $isPriceAvailable->price;
            }else {
                $vehicle->price = null;
            }
        }

        if( $field == 'so_date')
        {

            $existingSo = So::where('so_date', $oldValue)->first();
            if($existingSo) {

                $existingSo->sales_person_id  = $pendingApprovalRequest->updated_by;
                $existingSo->so_date = $newValue;
                $existingSo->save();
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
            }else{
                if($vehicle->so_id){
                    $so = So::find($vehicle->so_id);
                    $so->so_date = $newValue;
                    $so->save();
                }
            }

        }else if($field == 'so_number') {
            $existingSo = So::where('so_number', $newValue)
                ->where(function ($query) {
                $query->where('status', '!=', 'Cancelled')
                    ->orWhereNull('status');
            })->first();

            if($existingSo) {
                if($existingSo->so_number != $newValue)
                {
                    $solog = new Solog();
                    $solog->time = $currentDateTime->toTimeString();
                    $solog->date = $currentDateTime->toDateString();
                    $solog->status = 'Update Sales Values';
                    $solog->so_id = $existingSo->id;
                    $solog->field = 'so_number';
                    $solog->old_value = $existingSo->so_number;
                    $solog->new_value = $newValue;
                    $solog->created_by = auth()->user()->id;
                    $solog->save();

                    $existingSo->so_number = $newValue;
                    $existingSo->sales_person_id  = $pendingApprovalRequest->updated_by;
                    $existingSo->save();
                }
            }else{
                $so = new So();
                $so->so_number = $newValue;
//                $so->so_date = $request->so_dates[$key];
                $so->sales_person_id = $pendingApprovalRequest->updated_by;
                $so->save();
                $soID = $so->id;
                $vehicle->so_id = $soID;

                // Save log in Solog

                $colorlog = new Solog();
                $colorlog->time = $currentDateTime->toTimeString();
                $colorlog->date = $currentDateTime->toDateString();
                $colorlog->status = 'New Created';
                $colorlog->so_id = $soID;
                $colorlog->field = 'so_number';
                $colorlog->new_value = $so->so_number;
                $colorlog->created_by = auth()->user()->id;
                $colorlog->save();

            }
        }else{
            $vehicle->$field = $newValue;
            event(new DataUpdatedEvent(['id' => $vehicle->id, 'message' => "Data Update"]));
        }

        $vehicle->save();
        $pendingApprovalRequest->status = $request->status;
        if($request->status == 'approved') {
            $pendingApprovalRequest->approved_by = Auth::id();
        }
        $pendingApprovalRequest->save();
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
