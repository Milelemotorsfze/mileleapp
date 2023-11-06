<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserActivities;
use App\Events\DataUpdatedEvent;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Vehicles;
use App\Models\VehicleExtraItems;
use App\Models\VehicleApprovalRequests;
use App\Models\Varaint;
use Illuminate\Support\Facades\File;
use App\Models\Brand;
use App\Models\VehiclePicture;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Illuminate\Support\Facades\Auth;
use App\Models\ColorCode;
use App\Models\Vehicleslog;
use App\Models\Pdi;
use App\Models\Inspection;
use App\Models\RoutineInspection;
use App\Models\Incident;
use App\Models\MasterModelLines;
use App\Models\IncidentWork;
use App\Models\VariantRequest;

class ApprovalsController extends Controller
{  
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Open The Approval Section";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        if ($request->ajax()) {
            $status = $request->input('status');
            if($status == "reparingapproval"){
                $data = Incident::select( [
                    'incident.id as incidentsnumber',
                    'users.name as created_by_name',
                    'incident.type',
                    'incident.part_po_number',
                    'incident.update_remarks',
                    'incident.narration',
                    'incident.reason',
                    'incident.driven_by',
                    'incident.responsivity',
                    DB::raw("DATE_FORMAT(incident.reinspection_date, '%d-%b-%Y') as reinspection_date"),
                    'vehicles.ppmmyyy',
                    'vehicles.vin',
                    'vehicles.engine',
                    'varaints.name as variant',
                    'varaints.model_detail',
                    'varaints.detail',
                    'varaints.seat',
                    'varaints.upholestry',
                    'varaints.steering',
                    'varaints.my',
                    'varaints.fuel_type',
                    'varaints.gearbox',
                    'master_model_lines.model_line',
                    'int_color.name as interior_color',
                    'ex_color.name as exterior_color',
                    'purchasing_order.po_number',
                ])
                ->leftJoin('vehicles', 'incident.vehicle_id', '=', 'vehicles.id')
                ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
                ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
                ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
                ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                ->leftJoin('inspection', 'incident.inspection_id', '=', 'inspection.id')
                ->leftJoin('users', 'inspection.created_by', '=', 'users.id')
                ->where('incident.status', 'repairingapproval');
                $data = $data->groupBy('vehicles.id');
            }
            else{
            $data = Inspection::select( [
                'inspection.id',
                'users.name as created_by_name',
                    'inspection.vehicle_id',
                    'inspection.reinspection_remarks',
                    'inspection.stage',
                    DB::raw("DATE_FORMAT(inspection.reinspection_date, '%d-%b-%Y') as reinspection_date"),
                    DB::raw("DATE_FORMAT(inspection.processing_date, '%d-%b-%Y') as processing_date_formte"),
                    'inspection.process_remarks',
                    DB::raw('REPLACE(REPLACE(inspection.remark, "<p>", ""), "</p>", "") as remark'),
                    'vehicle_detail_approval_requests.action_at',    
                    'warehouse.name as location',
                    'vehicles.vin',
                    DB::raw("DATE_FORMAT(inspection.created_at, '%d-%b-%Y') as created_at_formte"),  
                    'vehicles.qc_remarks',
                    'brands.brand_name',
                    'varaints.name as variant',
                    'varaints.model_detail',
                    'varaints.detail',
                    'master_model_lines.model_line',
                    'int_color.name as interior_color',
                    'ex_color.name as exterior_color',
                    'purchasing_order.po_number',
                    'grn.grn_number',
                    'so.so_number',
                    DB::raw('(SELECT GROUP_CONCAT(field) FROM vehicle_detail_approval_requests WHERE inspection_id = inspection.id) as changing_fields')
                ])
                ->leftJoin('vehicles', 'inspection.vehicle_id', '=', 'vehicles.id')
                ->leftJoin('vehicle_detail_approval_requests', 'inspection.id', '=', 'vehicle_detail_approval_requests.inspection_id')
                ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
                ->leftJoin('warehouse', 'vehicles.latest_location', '=', 'warehouse.id')
                ->leftJoin('grn', 'vehicles.grn_id', '=', 'grn.id')
                ->leftJoin('so', 'vehicles.so_id', '=', 'so.id')
                ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
                ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
                ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
                ->leftJoin('users', 'inspection.created_by', '=', 'users.id')
                ->where('inspection.status', $status);
                $data = $data->groupBy('vehicles.id');
            }
                return DataTables::of($data)
                ->toJson();
    }
        return view('inspection.approvals');
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
      
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Open the Approval Page For Approval";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $inspection = Inspection::find($id);
    $vehicle = Vehicles::find($inspection->vehicle_id);
    $grnpicturelink = VehiclePicture::where('vehicle_id', $inspection->vehicle_id)->where('category', 'GRN')->pluck('vehicle_picture_link')->first();
    $secgrnpicturelink = VehiclePicture::where('vehicle_id', $inspection->vehicle_id)->where('category', 'GRN-2')->pluck('vehicle_picture_link')->first();
    $gdnpicturelink = VehiclePicture::where('vehicle_id', $inspection->vehicle_id)->where('category', 'GDN')->pluck('vehicle_picture_link')->first();
    $secgdnpicturelink = VehiclePicture::where('vehicle_id', $inspection->vehicle_id)->where('category', 'GDN-2')->pluck('vehicle_picture_link')->first();
    $PDIpicturelink = VehiclePicture::where('vehicle_id', $inspection->vehicle_id)->where('category', 'Incident')->pluck('vehicle_picture_link')->first();
    $modificationpicturelink = VehiclePicture::where('vehicle_id', $inspection->vehicle_id)->where('category', 'Modification')->pluck('vehicle_picture_link')->first();
    $Incidentpicturelink = VehiclePicture::where('vehicle_id', $inspection->vehicle_id)->where('category', 'PDI')->pluck('vehicle_picture_link')->first();
    $enginevalue = VehicleApprovalRequests::where('inspection_id', $id)->where('field', 'engine')->pluck('new_value')->first();
    $vinvalue = VehicleApprovalRequests::where('inspection_id', $id)->where('field', 'vin')->pluck('new_value')->first();
    $int_colourvalue = VehicleApprovalRequests::where('inspection_id', $id)->where('field', 'int_colour')->pluck('new_value')->first();
    $ex_colourevalue = VehicleApprovalRequests::where('inspection_id', $id)->where('field', 'ex_colour')->pluck('new_value')->first();
    $extra_featuresvalue = VehicleApprovalRequests::where('inspection_id', $id)->where('field', 'extra_features')->pluck('new_value')->first();
    $variantChange = VehicleApprovalRequests::where('inspection_id', $id)->where('field', 'Variant Change')->whereIn('status', ['Pending', 'Reinspection'])->pluck('new_value')->first();
    $int_colours = ColorCode::where('belong_to', 'int')->get();
    $ext_colours = ColorCode::where('belong_to', 'ex')->get();
    if($variantChange){
        $changevariant = Varaint::where('id', $variantChange)->first();
    }
    else{
        $changevariant = null;
    }
    $variantnew = VehicleApprovalRequests::where('inspection_id', $id)->where('field', 'New Variant')->where('status', 'Pending')->pluck('new_value')->first();
    if($variantnew){
        $newvariant = VariantRequest::where('id', $variantnew)->first();   
    }
    else{
        $newvariant = null;
    } 
    $Incident = Incident::where('inspection_id', $id)->first();
    $variant = Varaint::find($vehicle->varaints_id);
    $brand = Brand::find($variant->brands_id);
    $model_line = MasterModelLines::find($variant->master_model_lines_id);
    $intColor = ColorCode::find($vehicle->int_colour);
    $extColor = ColorCode::find($vehicle->ex_colour);
    $variantsall = Varaint::where('master_model_lines_id', $variant->master_model_lines_id)->where('brands_id', $variant->brands_id)->get();
    $extraItems = DB::table('vehicles_extra_items')
        ->where('vehicle_id', $inspection->vehicle_id)
        ->get(['item_name', 'qty']);
    return view('inspection.approvalview', compact('variantsall','ext_colours','int_colours','Incidentpicturelink','modificationpicturelink','PDIpicturelink', 'secgdnpicturelink', 'gdnpicturelink', 'secgrnpicturelink', 'grnpicturelink', 'extraItems','newvariant','changevariant', 'inspection', 'vehicle', 'variant', 'brand', 'model_line', 'intColor', 'extColor','Incident', 'enginevalue', 'vinvalue', 'int_colourvalue', 'ex_colourevalue', 'extra_featuresvalue'));
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
    public function updateStatus(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Approved the Inspection";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $inspectionId = $request->input('inspectionId');
        VehicleApprovalRequests::where('inspection_id', $inspectionId)->whereNot('field', 'New Variant')->whereNot('field', 'Variant Change')
            ->update(['status' => 'Approved']);
        return response()->json(['message' => 'Status updated successfully']);
    }
    public function updateinspectionupdates(Request $request) {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Update Inspection Basic Detail";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $inspection_id = $request->input('inspection_id');
        $engine = $request->input('engine');
        $vin = $request->input('vin');
        $int_colour = $request->input('int_colour');
        $ex_colour = $request->input('ex_colour');
        $extra_features = $request->input('extra_features');
        $remarking = $request->input('remark');
        $inspection = Inspection::find($inspection_id);
        $inspection->status = "Pending";
        $inspection->remark = $remarking;
        $inspection->save();
        $vehicle = Vehicles::find($inspection->vehicle_id);
        $commonData = [
            'status' => 'Pending',
            'updated_by' => auth()->user()->id,
            'inspection_id' => $inspection_id,
        ];
        if ($vin !== null) {
            VehicleApprovalRequests::updateOrInsert(
                ['inspection_id' => $inspection_id, 'field' => 'vin'],
                array_merge(['vehicle_id' => $inspection->vehicle_id, 'old_value' => $vehicle->vin, 'new_value' => $vin], $commonData)
            );
        }
        if ($int_colour !== null) {
            VehicleApprovalRequests::updateOrInsert(
                ['inspection_id' => $inspection_id, 'field' => 'int_colour'],
                array_merge(['vehicle_id' => $inspection->vehicle_id, 'old_value' => $vehicle->int_colour, 'new_value' => $int_colour], $commonData)
            );
        }
        if ($engine !== null) {
            VehicleApprovalRequests::updateOrInsert(
                ['inspection_id' => $inspection_id, 'field' => 'engine'],
                array_merge(['vehicle_id' => $inspection->vehicle_id, 'old_value' => $vehicle->engine, 'new_value' => $engine], $commonData)
            );
        }
        if ($ex_colour !== null) {
            VehicleApprovalRequests::updateOrInsert(
                ['inspection_id' => $inspection_id, 'field' => 'ex_colour'],
                array_merge(['vehicle_id' => $inspection->vehicle_id, 'old_value' => $vehicle->ex_colour, 'new_value' => $ex_colour], $commonData)
            );
        }
    
        if ($extra_features !== null) {
            VehicleApprovalRequests::updateOrInsert(
                ['inspection_id' => $inspection_id, 'field' => 'extra_features'],
                array_merge(['vehicle_id' => $inspection->vehicle_id, 'old_value' => $vehicle->extra_features, 'new_value' => $extra_features], $commonData)
            );
        }
        return response()->json(['message' => 'Data saved successfully']);
    }
    public function updateextraitems(Request $request) {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Update Extra Items In Inspection";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $vehicle_id = $request->input('vehicle_id');
        $data = $request->except('_token', 'vehicle_id');
        foreach ($data as $item_name => $itemData) {
            $isChecked = $itemData['checked'];
            $qty = $itemData['qty'];
            if ($isChecked == "true") {
                VehicleExtraItems::updateOrCreate(
                    [
                        'vehicle_id' => $vehicle_id,
                        'item_name' => $item_name,
                    ],
                    [
                        'vehicle_id' => $vehicle_id,
                        'item_name' => $item_name,
                        'qty' => $qty,
                    ]
                );
            } else {
                VehicleExtraItems::where('vehicle_id', $vehicle_id)
                    ->where('item_name', $item_name)
                    ->delete();
            }
        }
    
        return response()->json(['message' => 'Data saved successfully']);
    }    
    public function updateincident(Request $request) 
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Update the Incident";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $canvasImageDataURL = $request->input('canvas_image'); 
        if($canvasImageDataURL != null)
        {
            $canvasImageDataURL = $request->input('canvas_image'); 
            $canvasImageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $canvasImageDataURL));
            $filename = 'canvas_image_' . uniqid() . '.png';
            $directory = public_path('qc');
            File::makeDirectory($directory, $mode = 0777, true, true);
            File::put($directory . '/' . $filename, $canvasImageData); 
        }
        $incidentType = $request->input('incidentType');
        $narration = $request->input('narration');
        $damageDetails = $request->input('damageDetails');
        $drivenBy = $request->input('drivenBy');
        $responsibility = $request->input('responsibility');
        $reasons = $request->input('reasons');
        $id = $request->input('Incidentid');
        $incident = Incident::find($id);
        $incident->type = $request->input('incidentType');
        $incident->narration = $request->input('narration');
        $incident->detail = $request->input('damageDetails');
        $incident->driven_by = $request->input('drivenBy');
        $incident->responsivity = $request->input('responsibility');
        $reasons = $request->input('reasons', []);
        $incident->reason = implode(',', $reasons);
        if($canvasImageDataURL != null)
        {
        $incident->file_path = $filename;
        }
        $incident->save();
        return response()->json(['message' => 'Data saved successfully']);
    }
    public function savevariantsd(Request $request) {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Create New Variant";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $variantType = $request->input('variantType');
        $variantname = $request->input('variantname');
        $modeldetail = $request->input('modeldetail');
        $my = $request->input('my');
        $detail = $request->input('detail');
        $engine = $request->input('engine');
        $fueltype = $request->input('fueltype');
        $gearbox = $request->input('gearbox');
        $steering = $request->input('steering');
        $seat = $request->input('seat');
        $upholestry = $request->input('upholestry');
        $inspection_id = $request->input('inspection_id');
        $remark = $request->input('remark');
        $variant = $request->input('variant');
        $newvariantid = $request->input('newvariantid');
       if($variantType == "new")
       {
        if($newvariantid)
        {
        $variantRequest = VariantRequest::where('id', $newvariantid)->firstOrFail();
        $variantRequest->name = $variantname;
        $variantRequest->model_detail = $modeldetail;
        $variantRequest->my = $my;
        $variantRequest->detail = $detail;
        $variantRequest->engine = $engine;
        $variantRequest->gearbox = $gearbox;
        $variantRequest->steering = $steering;
        $variantRequest->fuel_type = $fueltype;
        $variantRequest->seat = $seat;
        $variantRequest->upholestry = $upholestry;
        $variantRequest->status = "Approval";
        $variantRequest->save();
     }
     else{
        $inspection = Inspection::find($inspection_id);
        $vehicle = Vehicles::find($inspection->vehicle_id);
        $variants = Varaint::find($vehicle->varaints_id);
        $variantRequest = New VariantRequest();
        $variantRequest->name = $variantname;
        $variantRequest->model_detail = $modeldetail;
        $variantRequest->my = $my;
        $variantRequest->detail = $detail;
        $variantRequest->engine = $engine;
        $variantRequest->gearbox = $gearbox;
        $variantRequest->steering = $steering;
        $variantRequest->fuel_type = $fueltype;
        $variantRequest->seat = $seat;
        $variantRequest->brands_id = $variants->brands_id;
        $variantRequest->master_model_lines_id = $variants->master_model_lines_id;
        $variantRequest->upholestry = $upholestry;
        $variantRequest->status = "Approval";
        $variantRequest->save();
     }
        $variant = new Varaint();
        $variant->name = $variantname;
        $variant->model_detail = $modeldetail;
        $variant->master_model_lines_id = $variantRequest->master_model_lines_id;
        $variant->brands_id = $variantRequest->brands_id;
        $variant->my = $my;
        $variant->detail = $detail;
        $variant->engine = $engine;
        $variant->gearbox = $gearbox;
        $variant->fuel_type = $fueltype;
        $variant->steering = $steering;
        $variant->seat = $seat;
        $variant->upholestry = $upholestry;
        $variant->save();
        if($newvariantid)
        {
            $approvalRequest = VehicleApprovalRequests::where('inspection_id', $inspection_id)->where('field', 'New Variant')->firstOrFail();
        }
        else
        {
            $approvalRequest = VehicleApprovalRequests::where('inspection_id', $inspection_id)->where('field', 'Variant Change')->firstOrFail(); 
        }
        info($approvalRequest);
        if($approvalRequest)
        {
            $approvalRequest->field = "Variant Change";
            $approvalRequest->new_value = $variant->id;
            $approvalRequest->save();   
        }
        else{
            $inspection = Inspection::find($inspection_id);
            $vehicle = Vehicles::find($inspection->vehicle_id);
            $changeapprovalRequest =  New VehicleApprovalRequests();
            $changeapprovalRequest->vehicle_id = $vehicle->id;
            $changeapprovalRequest->field = "Variant Change";
            $changeapprovalRequest->old_value = $vehicle->varaints_id;
            $changeapprovalRequest->new_value = $variant->id;
            $changeapprovalRequest->status = "Pending";
            $changeapprovalRequest->updated_by = auth()->user()->id;
            $changeapprovalRequest->inspection_id = $inspection_id;
            $changeapprovalRequest->save();
        }
       }
       else if($variantType == "change"){
        $inspection = Inspection::find($inspection_id);
        $vehicle = Vehicles::find($inspection->vehicle_id);
        $approvalRequest = VehicleApprovalRequests::where('inspection_id', $inspection_id)
        ->where(function($query) {
            $query->where('field', 'New Variant')
                  ->orWhere('field', 'Variant Change');
        })
        ->first();
        if ($approvalRequest) {
            $approvalRequest->update(['new_value' => $variant]); 
        } else {
            VehicleApprovalRequests::create([
                'inspection_id' => $inspection_id,
                'field' => 'Variant Change',
                'vehicle_id' => $vehicle->id,
                'old_value' => $vehicle->varaints_id,
                'new_value' => $variant,
                'status' => 'Pending'  
            ]);
        }   
       }
       else
       {
        $inspection = Inspection::find($inspection_id);
        $approvalRequest = VehicleApprovalRequests::where('inspection_id', $inspection_id)
        ->where(function($query) {
            $query->where('field', 'New Variant')
                  ->orWhere('field', 'Variant Change');
        })
        ->first();
       }
       if ($approvalRequest) {
        $approvalRequest->update(['status' => 'Rejected']); 
    }
        return response()->json(['message' => 'Data saved successfully']);
    }
    public function approveInspection(Request $request) {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Approved The Inspection";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $currentDate = Carbon::now();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
        $inspectionId = $request->input('inspection_id');
        $comments = $request->input('process_remarks');
        $buttonValue = $request->input('buttonValue');
        if($buttonValue == "reinspect"){
            $inspection = Inspection::find($inspectionId);
            $inspection->status = 'reinspection';
            $inspection->process_remarks = $comments;
            $inspection->processing_date = $currentDate;
            $inspection->save();
            VehicleApprovalRequests::where('inspection_id', $inspectionId)
            ->where('status', 'Pending')
            ->update(['status' => 'reinspection']);
            Incident::where('inspection_id', $inspectionId)
            ->where('status', 'Pending')
            ->update([
                'status' => 'reinspection'
            ]);
            return redirect()->route('approvalsinspection.index')->with('success', 'Inspection Re-Inspection successfully Forwarded');
        }
        else{
            $inspection = Inspection::find($inspectionId);
            $inspection->status = 'approved';
            $inspection->process_remarks = $comments;
            $inspection->processing_date = $currentDate;
            $inspection->save();
            VehicleApprovalRequests::where('inspection_id', $inspectionId)
            ->where('status', 'Pending')
            ->update(['status' => 'approved']);
        $incident = Incident::where('inspection_id', $inspectionId)->first();
            if($incident)
            {
            $incident->status = "approved";
            $incident->reported_date = $currentDateTime->toDateString();
            $incident->save();
            }
            $vehicles = Vehicles::find($inspection->vehicle_id);
            if($inspection->stage == "GRN")
            {
            $vehicles->grn_remark = $comments;
            $vehicles->inspection_date = $currentDate;
            }
            elseif($inspection->stage == "PDI")
            {
                $vehicles->pdi_remarks = $comments;
                $vehicles->pdi_date = $currentDate;
            }
            else
            {
                $vehicles->qc_remarks = $comments;
            }
            $vinExists = VehicleApprovalRequests::where('inspection_id', $inspectionId)
            ->where('status', 'approved')
            ->where('field', 'vin')
            ->first();
            if ($vinExists) {
            $vehicles->vin = $vinExists->new_value;
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Approved Updated QC Values';
            $vehicleslog->vehicles_id = $inspection->vehicle_id;
            $vehicleslog->field = 'VIN';
            $vehicleslog->old_value = $vehicles->vin;
            $vehicleslog->new_value = $vinExists->new_value;
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->save();
            } 
            $engineExists = VehicleApprovalRequests::where('inspection_id', $inspectionId)
            ->where('status', 'approved')
            ->where('field', 'engine')
            ->first();
            if ($engineExists) {
            $vehicles->engine = $engineExists->new_value;
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Approved Updated QC Values';
            $vehicleslog->vehicles_id = $inspection->vehicle_id;
            $vehicleslog->field = 'engine';
            $vehicleslog->old_value = $vehicles->engine;
            $vehicleslog->new_value = $engineExists->new_value;
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->save();
            } 
            $intExists = VehicleApprovalRequests::where('inspection_id', $inspectionId)
            ->where('status', 'approved')
            ->where('field', 'int_colour')
            ->first();
            if ($intExists) {
                $vehicles->int_colour = $intExists->new_value;
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Approved Updated QC Values';
                $vehicleslog->vehicles_id = $inspection->vehicle_id;
                $vehicleslog->field = 'int_colour';
                $vehicleslog->old_value = $vehicles->int_colour;
                $vehicleslog->new_value = $intExists->new_value;
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->save();
            } 
            $exExists = VehicleApprovalRequests::where('inspection_id', $inspectionId)
            ->where('status', 'approved')
            ->where('field', 'ex_colour')
            ->first();
            if ($exExists) {
                $vehicles->ex_colour = $exExists->new_value;
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Approved Updated QC Values';
                $vehicleslog->vehicles_id = $inspection->vehicle_id;
                $vehicleslog->field = 'ex_colour';
                $vehicleslog->old_value = $vehicles->ex_colour;
                $vehicleslog->new_value = $exExists->new_value;
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->save();
            } 
            $extra_featuresExists = VehicleApprovalRequests::where('inspection_id', $inspectionId)
            ->where('status', 'approved')
            ->where('field', 'extra_features')
            ->first();
            if ($extra_featuresExists) {
                $vehicles->extra_features = $extra_featuresExists->new_value;
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Approved Updated QC Values';
                $vehicleslog->vehicles_id = $inspection->vehicle_id;
                $vehicleslog->field = 'extra_features';
                $vehicleslog->old_value = $vehicles->extra_features;
                $vehicleslog->new_value = $extra_featuresExists->new_value;
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->save();
            } 
            $VariantExists = VehicleApprovalRequests::where('inspection_id', $inspectionId)
            ->where('status', 'approved')
            ->where('field', 'Variant Change')
            ->first();
            if ($VariantExists) {
                $vehicles->varaints_id = $VariantExists->new_value;
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Approved Updated QC Values';
                $vehicleslog->vehicles_id = $inspection->vehicle_id;
                $vehicleslog->field = 'Variant Change';
                $vehicleslog->old_value = $vehicles->varaints_id;
                $vehicleslog->new_value = $VariantExists->new_value;
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->save();
            }
            $vehicles->save();
            return redirect()->route('approvalsinspection.index')->with('success', 'Inspection Approval successfully Done.');
        }
    }
    public function getRoutineInspectionData($vehicleId)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Open the Routine Inspection";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $routineInspectionData = RoutineInspection::select('check_items', 'spec', 'condition', 'remarks')
            ->where('inspection_id', $vehicleId)
            ->get();
            $inspection = Inspection::find($vehicleId);
            $additionalInfo = Vehicles::select('master_model_lines.model_line', 'vehicles.vin', 'color_codes.name as int_colour', 'color_codes.name as ext_colour', 'warehouse.name as location')
        ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
        ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
        ->leftJoin('color_codes', 'vehicles.int_colour', '=', 'color_codes.id')
        ->leftJoin('color_codes as ext_color', 'vehicles.ex_colour', '=', 'ext_color.id')
        ->leftJoin('warehouse', 'vehicles.latest_location', '=', 'warehouse.id')
        ->where('vehicles.id', $inspection->vehicle_id)
        ->first();
        $incidentData = Incident::select('reason', 'driven_by', 'detail', 'narration', 'type', 'responsivity', 'file_path')
        ->where('inspection_id', $vehicleId)
        ->first();
        info($incidentData);
        return response()->json([
            'routineInspectionData' => $routineInspectionData,
            'additionalInfo' => $additionalInfo,
            'incidentData' => $incidentData,
            'inspection' => $inspection->id,
        ]);
    }
    public function approvalsrotein(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Approved the routain inspection";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $currentDate = Carbon::now();
        $inspectionId = $request->input('inspectionid');
        $inspection = Inspection::find($inspectionId);
            $inspection->status = 'approved';
            $inspection->processing_date = $currentDate;
            $inspection->save();
			$incident = Incident::where('inspection_id', $inspectionId)->where('status', 'Pending')->first();
            if($incident)
            {
            $incident->status = "approved";
            $incident->reported_date = $currentDateTime->toDateString();
            $incident->save();
            }
        return response()->json(['message' => 'Data saved successfully']);
    }
    public function getpdiInspectionData($vehicleId)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Open The PDI Inspection";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $PdiInspectionData = Pdi::select('checking_item', 'reciving', 'status')
                            ->where('inspection_id', $vehicleId)
                            ->get();
        $inspection = Inspection::find($vehicleId);
        $additionalInfo = Vehicles::select('master_model_lines.model_line', 'vehicles.vin', 'color_codes.name as int_colour', 'color_codes.name as ext_colour', 'warehouse.name as location')
        ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
        ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
        ->leftJoin('color_codes', 'vehicles.int_colour', '=', 'color_codes.id')
        ->leftJoin('color_codes as ext_color', 'vehicles.ex_colour', '=', 'ext_color.id')
        ->leftJoin('warehouse', 'vehicles.latest_location', '=', 'warehouse.id')
        ->where('vehicles.id', $inspection->vehicle_id)
        ->first();
        $incidentDetails = Incident::where('inspection_id', $inspection->id)->first();
        $vehicle = Vehicles::find($inspection->vehicle_id);
        $grnpicturelink = VehiclePicture::where('vehicle_id', $inspection->vehicle_id)->where('category', 'GRN')->pluck('vehicle_picture_link')->first();
        $secgrnpicturelink = VehiclePicture::where('vehicle_id', $inspection->vehicle_id)->where('category', 'GRN-2')->pluck('vehicle_picture_link')->first();
        $PDIpicturelink = VehiclePicture::where('vehicle_id', $inspection->vehicle_id)->where('category', 'PDI')->pluck('vehicle_picture_link')->first();
        $modificationpicturelink = VehiclePicture::where('vehicle_id', $inspection->vehicle_id)->where('category', 'Modification')->pluck('vehicle_picture_link')->first();
        $Incidentpicturelink = VehiclePicture::where('vehicle_id', $inspection->vehicle_id)->where('category', 'Incident')->pluck('vehicle_picture_link')->first();
        return response()->json([
            'PdiInspectionData' => $PdiInspectionData,
            'additionalInfo' => $additionalInfo,
            'grnpicturelink' => $grnpicturelink,
            'secgrnpicturelink' => $secgrnpicturelink,
            'PDIpicturelink' => $PDIpicturelink,
            'modificationpicturelink' => $modificationpicturelink,
            'Incidentpicturelink' => $Incidentpicturelink,
            'incidentDetails' => $incidentDetails,
            'remarks' => $inspection,
        ]);
    }
    public function approvalspdi(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Approved The PDI Inspection";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
        $currentDate = Carbon::now();
        $inspectionId = $request->input('inspectionid');
        $remarks = $request->input('remarks');
        $inspection = Inspection::find($inspectionId);
            $inspection->status = 'approved';
            $inspection->processing_date = $currentDate;
            $inspection->process_remarks = $remarks;
            $inspection->save();
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Approved PDI Inspection';
            $vehicleslog->vehicles_id = $inspection->vehicle_id;
            $vehicleslog->field = 'PDI Report';
            $vehicleslog->old_value = '';
            $vehicleslog->new_value = $inspection->id;
            $vehicleslog->created_by = $inspection->created_by;
            $vehicleslog->save();
            $vehicles = Vehicles::find($inspection->vehicle_id);
            $vehicles->pdi_date     = $currentDate;
            $vehicles->pdi_remarks  = $remarks;
            $vehicles->save();
            event(new DataUpdatedEvent(['id' => $inspection->vehicle_id, 'message' => "Data Update"]));
        return response()->json(['message' => 'Data saved successfully']);
    }
    public function approvedincidentsonly(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Approved The PDI Incident Only";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
        $currentDate = Carbon::now();
        $inspectionId = $request->input('inspectionid');
        $remarks = $request->input('remarks');
        $inspection = Inspection::find($inspectionId);
        $inspection->status = 'PDI Incident Approved';
        $inspection->processing_date = $currentDate;
        $inspection->process_remarks = $remarks;
        $inspection->save();
        $incident = Incident::where('inspection_id', $inspectionId)->first();
        $incident->status = 'Approved';
        $incident->reported_date = $currentDate;
        $incident->save();
        return response()->json(['message' => 'Incident Approved successfully']);
    }
    public function inspectionedit($id)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Open the Approval Page For Approval";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $inspection = Inspection::find($id);
    $vehicle = Vehicles::find($inspection->vehicle_id);
    $grnpicturelink = VehiclePicture::where('vehicle_id', $inspection->vehicle_id)->where('category', 'GRN')->pluck('vehicle_picture_link')->first();
    $secgrnpicturelink = VehiclePicture::where('vehicle_id', $inspection->vehicle_id)->where('category', 'GRN-2')->pluck('vehicle_picture_link')->first();
    $gdnpicturelink = VehiclePicture::where('vehicle_id', $inspection->vehicle_id)->where('category', 'GDN')->pluck('vehicle_picture_link')->first();
    $secgdnpicturelink = VehiclePicture::where('vehicle_id', $inspection->vehicle_id)->where('category', 'GDN-2')->pluck('vehicle_picture_link')->first();
    $PDIpicturelink = VehiclePicture::where('vehicle_id', $inspection->vehicle_id)->where('category', 'Incident')->pluck('vehicle_picture_link')->first();
    $modificationpicturelink = VehiclePicture::where('vehicle_id', $inspection->vehicle_id)->where('category', 'Modification')->pluck('vehicle_picture_link')->first();
    $Incidentpicturelink = VehiclePicture::where('vehicle_id', $inspection->vehicle_id)->where('category', 'PDI')->pluck('vehicle_picture_link')->first();
    $enginevalue = VehicleApprovalRequests::where('inspection_id', $id)->where('field', 'engine')->pluck('new_value')->first();
    $vinvalue = VehicleApprovalRequests::where('inspection_id', $id)->where('field', 'vin')->pluck('new_value')->first();
    $int_colourvalue = VehicleApprovalRequests::where('inspection_id', $id)->where('field', 'int_colour')->pluck('new_value')->first();
    $ex_colourevalue = VehicleApprovalRequests::where('inspection_id', $id)->where('field', 'ex_colour')->pluck('new_value')->first();
    $extra_featuresvalue = VehicleApprovalRequests::where('inspection_id', $id)->where('field', 'extra_features')->pluck('new_value')->first();
    $variantChange = VehicleApprovalRequests::where('inspection_id', $id)->where('field', 'Variant Change')->whereIn('status', ['Pending', 'Reinspection'])->pluck('new_value')->first();
    $int_colours = ColorCode::where('belong_to', 'int')->get();
    $ext_colours = ColorCode::where('belong_to', 'ex')->get();
    if($variantChange){
        $changevariant = Varaint::where('id', $variantChange)->first();
    }
    else{
        $changevariant = null;
    }
    $variantnew = VehicleApprovalRequests::where('inspection_id', $id)->where('field', 'New Variant')->where('status', 'Pending')->pluck('new_value')->first();
    if($variantnew){
        $newvariant = VariantRequest::where('id', $variantnew)->first();   
    }
    else{
        $newvariant = null;
    } 
    $Incident = Incident::where('inspection_id', $id)->first();
    $variant = Varaint::find($vehicle->varaints_id);
    $brand = Brand::find($variant->brands_id);
    $model_line = MasterModelLines::find($variant->master_model_lines_id);
    $intColor = ColorCode::find($vehicle->int_colour);
    $extColor = ColorCode::find($vehicle->ex_colour);
    $variantsall = Varaint::where('master_model_lines_id', $variant->master_model_lines_id)->where('brands_id', $variant->brands_id)->get();
    $extraItems = DB::table('vehicles_extra_items')
        ->where('vehicle_id', $inspection->vehicle_id)
        ->get(['item_name', 'qty']);
    return view('inspection.editvehicleshow', compact('variantsall','ext_colours','int_colours','Incidentpicturelink','modificationpicturelink','PDIpicturelink', 'secgdnpicturelink', 'gdnpicturelink', 'secgrnpicturelink', 'grnpicturelink', 'extraItems','newvariant','changevariant', 'inspection', 'vehicle', 'variant', 'brand', 'model_line', 'intColor', 'extColor','Incident', 'enginevalue', 'vinvalue', 'int_colourvalue', 'ex_colourevalue', 'extra_featuresvalue'));
    }
    public function updateRoutineInspection(Request $request)
    {
        $validatedData = $request->validate([
            'updatedData' => 'required|array',
        ]);
        $updatedData = $validatedData['updatedData'];
        $inspectionid = $request->input('inspectionid');
        $incidentData = $request->input('incidentData');
        $inspection = Inspection::where('id', $inspectionid)->first();
        if ($inspection) {
            $inspection->status = "Pending";
            $inspection->save();
        }
        foreach ($updatedData as $data) {
            $routineInspection = RoutineInspection::where('check_items', $data['check_items'])->where('inspection_id', $inspectionid)->first();
            info($routineInspection);
            if ($routineInspection) {
                $routineInspection->condition = $data['condition'];
                $routineInspection->remarks = $data['remarks'];
                $routineInspection->save();
            }
        }
        info($incidentData);
        if (!empty($incidentData)) {
            $incident = Incident::where('inspection_id', $inspectionid)->first();
            if ($incident) {
                $incident->update([
                    'type' => $incidentData['type'],
                    'narration' => $incidentData['narration'],
                    'detail' => $incidentData['detail'],
                    'driven_by' => $incidentData['driven_by'],
                    'responsivity' => $incidentData['responsivity'],
                    'reason' => $incidentData['reason'],
                    'status' => 'Pending',
                ]);
            }
        }        
        return response()->json(['message' => 'Routine inspection data updated successfully']);
    }
    public function updatepdiInspectionedit(Request $request)
    {
        $validatedData = $request->validate([
            'updatedData' => 'required|array',
        ]);
        $updatedData = $validatedData['updatedData'];
        $inspectionid = $request->input('inspectionid');
        $inspection = Inspection::where('id', $inspectionid)->first();
        if ($inspection) {
            $inspection->remark = $updatedData['remarks']['remark'];
            $inspection->status = "Pending";
            $inspection->save();
        }
        foreach ($updatedData['PDIInspectionData'] as $data) {
            $pdiInspection = Pdi::where('checking_item', $data['checking_item'])
                ->where('inspection_id', $inspectionid)
                ->first();
            if ($pdiInspection) {
                $pdiInspection->status = $data['status'];
                $pdiInspection->save();
            }
        }
        if (isset($updatedData['incidentData'])) {
            $incident = Incident::where('inspection_id', $inspectionid)->first();
            if ($incident) {
                $incident->type = $updatedData['incidentData']['type'];
                $incident->narration = $updatedData['incidentData']['narration'];
                $incident->detail = $updatedData['incidentData']['detail'];
                $incident->driven_by = $updatedData['incidentData']['driven_by'];
                $incident->responsivity = $updatedData['incidentData']['responsivity'];
                $incident->reason = $updatedData['incidentData']['reason'];
                $incident->status = "Pending";
                $incident->save();
            }
        }
        return response()->json(['message' => 'Routine inspection data updated successfully']);
    }
}
