<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserActivities;
use App\Events\DataUpdatedEvent;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PDICompletionNotification;
use App\Models\Vehicles;
use Illuminate\Support\Facades\Validator;
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
use App\Models\MovementsReference;
use App\Models\VariantRequest;
use App\Models\VariantRequestItems;
use App\Models\ModelSpecification;
use App\Models\ModelSpecificationOption;
use App\Models\VariantItems;
use App\Models\Variantlog;
use App\Models\VehicleVariantHistories;
use App\Models\Grn;
use App\Models\Gdn;
use App\Models\MovementGrn;
use App\Models\PurchasingOrder;
use App\Mail\QCUpdateNotification;
use App\Models\DepartmentNotifications;
use App\Models\Dnaccess;
use Illuminate\Support\Facades\Log;

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
                    'inspection.approval_remarks',
                    DB::raw("DATE_FORMAT(inspection.approval_date, '%d-%b-%Y') as approval_date"),
                    DB::raw("DATE_FORMAT(inspection.reinspection_date, '%d-%b-%Y') as reinspection_date"),
                    DB::raw("DATE_FORMAT(inspection.processing_date, '%d-%b-%Y') as processing_date_formte"),
                    'inspection.process_remarks',
                    DB::raw('REPLACE(REPLACE(inspection.remark, "<p>", ""), "</p>", "") as remark'),
                    'vehicle_detail_approval_requests.action_at',    
                    'warehouse.name as location',
                    'vehicles.inspection_status',
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
                    'movement_grns.grn_number',
                    'so.so_number',
                    DB::raw('(SELECT GROUP_CONCAT(field) FROM vehicle_detail_approval_requests WHERE inspection_id = inspection.id) as changing_fields')
                ])
                ->leftJoin('vehicles', 'inspection.vehicle_id', '=', 'vehicles.id')
                ->leftJoin('vehicle_detail_approval_requests', 'inspection.id', '=', 'vehicle_detail_approval_requests.inspection_id')
                ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
                ->leftJoin('warehouse', 'vehicles.latest_location', '=', 'warehouse.id')
                ->leftJoin('movement_grns', 'vehicles.movement_grn_id', '=', 'movement_grns.id')
                ->leftJoin('so', 'vehicles.so_id', '=', 'so.id')
                ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
                ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
                ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
                ->leftJoin('users', 'inspection.created_by', '=', 'users.id')
                ->where('inspection.status', $status);
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
    $extra_featuresvalue = VehicleApprovalRequests::where('inspection_id', $id)->where('field', 'extra_features')->pluck('new_value')->first();
    $Incident = Incident::where('inspection_id', $id)->first();
    $variant = Varaint::find($vehicle->varaints_id);
    $brand = Brand::find($variant->brands_id);
    $allBrands = Brand::all();
    $model_line = MasterModelLines::find($variant->master_model_lines_id);
    $model_lines = MasterModelLines::all();
    $vehiclecolour = ColorCode::find($vehicle->int_colour);
    $intmaster = ColorCode::where('belong_to', 'int')->get();
    $extvehicle = ColorCode::find($vehicle->ex_colour);
    $extmaster = ColorCode::where('belong_to', 'ex')->get();
    $variant_request = VariantRequest::where('inspection_id', $id)->first();
  
    $variantRequestItems = VariantRequestItems::where('variant_request_id', $variant_request->id)->get();
    $data = [];
    foreach ($variantRequestItems as $item) {
        $modelSpecification = ModelSpecification::find($item->model_specification_id);
        $modelSpecificationOption = ModelSpecificationOption::find($item->model_specification_options_id);
        if ($modelSpecification && $modelSpecificationOption) {
            $data[] = [
                'specification_id' => $modelSpecification->id,
                'label' => $modelSpecification->name,
                'options' => ModelSpecificationOption::where('model_specification_id', $modelSpecification->id)->pluck('name', 'id')->toArray(),
                'selected' => $modelSpecificationOption->name,
            ];
        }
    }
   
    $brands = Brand::find($variant_request->brands_id);

    $modal = MasterModelLines::find($variant_request->master_model_lines_id);
    $intrequest = ColorCode::find($variant_request->int_colour);
    $extrequest = ColorCode::find($variant_request->ex_colour);
    $extraItems = DB::table('vehicles_extra_items')
        ->where('vehicle_id', $inspection->vehicle_id)
        ->get(['item_name', 'qty']);
      
    return view('inspection.approvalview', compact('extmaster','intmaster','intrequest','extrequest','modal','model_lines','data',
    'allBrands','brands','variant_request','Incidentpicturelink','modificationpicturelink','PDIpicturelink',
     'secgdnpicturelink', 'gdnpicturelink', 'secgrnpicturelink', 'grnpicturelink', 'extraItems','inspection', 
     'vehicle', 'variant', 'brand', 'model_line', 'vehiclecolour', 'extvehicle','Incident', 'extra_featuresvalue'));
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
            $qty = $isChecked == "true" ? $itemData['qty'] : null;
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
    public function savevariantsd(Request $request) 
    {
        info("variant update");
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
        $isVariantNameExist = Varaint::where('name', $variantname)->first();
        if ($isVariantNameExist) {
            return response()->json(['error' => 'Variant with the same Name (' . $variantname . ') already exists'], 422);

        }
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
        $oldVariantData = $variant->exists ? $variant->toArray() : [];
        $newVariantData = [
            'name' => $variantname,
            'model_detail' => $modeldetail,
            'my' => $my,
            'detail' => $detail,
            'engine' => $engine,
            'gearbox' => $gearbox,
            'fuel_type' => $fueltype,
            'steering' => $steering,
            'seat' => $seat,
            'upholestry' => $upholestry,
        ];

        $diffs = [];
        foreach ($newVariantData as $key => $newVal) {
            $oldVal = $oldVariantData[$key] ?? null;
            if ((string) $oldVal !== (string) $newVal) {
                $diffs[$key] = [
                    'old' => $oldVal,
                    'new' => $newVal
                ];
            }
        }

        if (!empty($diffs)) {
            Log::info('Variant Change Detected 1. Vehicle varaints_id updated (savevariantsd)', [
                'action' => 'Variant Save',
                'variant_id' => $variant->id,
                'changes' => $diffs,
                'vehicle_id' => $vehicle->id ?? null,
                'old_varaints_id' => $vehicle->varaints_id ?? null,
                'inspection_id' => $inspection_id,
                'approval_request_id' => $approvalRequest->id ?? null,
                'is_new_variant' => $newvariantid ? false : true,
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name ?? 'N/A',
                'source' => 'savevariantsd',
                'timestamp' => now()->toDateTimeString(),
            ]);
        }

        $variant->save();
        if($newvariantid)
        {
            $approvalRequest = VehicleApprovalRequests::where('inspection_id', $inspection_id)->where('field', 'New Variant')->firstOrFail();
        }
        else
        {
            $approvalRequest = VehicleApprovalRequests::where('inspection_id', $inspection_id)->where('field', 'Variant Change')->firstOrFail(); 
        }
        if($approvalRequest)
        {
            $approvalRequest->field = "Variant Change";
            $approvalRequest->new_value = $variant->id;
            $approvalRequest->save();  
            Log::info('Variant Change Detected 2. ApprovalRequest updated with new variant ID (savevariantsd)', [
                'source' => 'savevariantsd',
                'action' => 'Update ApprovalRequest',
                'approval_request_id' => $approvalRequest->id,
                'new_variant_id' => $variant->id,
                'inspection_id' => $inspection_id,
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name ?? 'N/A',
                'timestamp' => now()->toDateTimeString()
            ]); 
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

            Log::info('Variant Change Detected 3. New Variant Change ApprovalRequest created (savevariantsd)', [
                'source' => 'savevariantsd',
                'action' => 'Create ApprovalRequest',
                'approval_request_id' => $changeapprovalRequest->id ?? null,
                'vehicle_id' => $vehicle->id,
                'old_varaints_id' => $vehicle->varaints_id,
                'new_variant_id' => $variant->id,
                'inspection_id' => $inspection_id,
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name ?? 'N/A',
                'timestamp' => now()->toDateTimeString()
            ]);
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

            Log::info('Variant Change Detected 4. Variant Change Request updated in change mode (savevariantsd)', [
                'source' => 'savevariantsd',
                'action' => 'Change Variant',
                'approval_request_id' => $approvalRequest->id,
                'new_variant_id' => $variant,
                'vehicle_id' => $vehicle->id,
                'old_varaints_id' => $vehicle->varaints_id,
                'inspection_id' => $inspection_id,
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name ?? 'N/A',
                'timestamp' => now()->toDateTimeString()
            ]);
        } else {
            VehicleApprovalRequests::create([
                'inspection_id' => $inspection_id,
                'field' => 'Variant Change',
                'vehicle_id' => $vehicle->id,
                'old_value' => $vehicle->varaints_id,
                'new_value' => $variant,
                'status' => 'Pending'  
            ]);
            Log::info('Variant Change Detected 5. Variant Change Request created in change mode (savevariantsd)', [
                'source' => 'savevariantsd',
                'action' => 'Change Variant - New Request',
                'vehicle_id' => $vehicle->id,
                'old_varaints_id' => $vehicle->varaints_id,
                'new_variant_id' => $variant,
                'inspection_id' => $inspection_id,
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name ?? 'N/A',
                'timestamp' => now()->toDateTimeString()
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

        Log::info('Variant Change Detected 6. ApprovalRequest marked as Rejected (savevariantsd)', [
            'source' => 'savevariantsd',
            'action' => 'Reject ApprovalRequest',
            'approval_request_id' => $approvalRequest->id,
            'vehicle_id' => $approvalRequest->vehicle_id ?? null,
            'inspection_id' => $inspection_id,
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name ?? 'N/A',
            'timestamp' => now()->toDateTimeString()
        ]);
    }
        return response()->json(['message' => 'Data saved successfully']);
    }
    public function approveInspection(Request $request) {

        $currentDate = Carbon::now();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
        $inspectionId = $request->input('inspection_id');
        $comments = $request->input('process_remarks');
        $buttonValue = $request->input('buttonValue');
        if($buttonValue == "reinspect"){
            $useractivities =  New UserActivities();
            $useractivities->activity = "Re Inspection The Inspection";
            $useractivities->users_id = Auth::id();
            $useractivities->save();
            $inspection = Inspection::find($inspectionId);
            $inspection->status = 'reinspection';
            $inspection->process_remarks = $comments;
            $inspection->processing_date = $currentDate;
            $inspection->approval_remarks = $comments;
            $inspection->approval_date = $currentDate;
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
        

            $useractivities =  New UserActivities();
            $useractivities->activity = "Approved The Inspection";
            $useractivities->users_id = Auth::id();
            $useractivities->save();
            $inspection = Inspection::find($inspectionId);
            $inspection->status = 'approved';
            $inspection->process_remarks = $comments;
            $inspection->processing_date = $currentDate;
            $inspection->save();
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
            // need to update inspection status
            $vehicles->inspection_status = 'Approved';
            $vehicles->grn_remark = $comments;
            $vehicles->inspection_date = $currentDate;
            }
            elseif($inspection->stage == "PDI")
            {
                $vehicles->pdi_remarks = $comments;
                $vehicles->pdi_date = $currentDate;
                
                // Send PDI completion notification to sales person
                $this->sendPDICompletionNotification($vehicles, $currentDate);
            }
            else
            {
                $vehicles->qc_remarks = $comments;
            }
            $vehicles->ex_colour = $request->input('ex_colour');
            $vehicles->int_colour = $request->input('int_colour');
            $vehicles->save();
            $selectedSpecifications = [];
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'specification_') !== false) {
                    $specificationId = substr($key, strlen('specification_'));
                    $selectedSpecifications[] = [
                        'specification_id' => $specificationId,
                        'value' => $value,
                    ];
                }
            }
            $variantoption = $request->input('variantoption');
            if($variantoption == 'updateexisting')
            {
            $existingvehiclevariant = Varaint::find($vehicles->varaints_id);
            foreach ($selectedSpecifications as $specificationData) {
                // Check if a record with the same variant_id and model_specification_id exists
                $specification = VariantItems::where('varaint_id', $existingvehiclevariant->id)
                                             ->where('model_specification_id', $specificationData['specification_id'])
                                             ->first();
                
                if ($specification) {
                    // Update the existing record
                    $specification->model_specification_options_id = $specificationData['value'];
                    $specification->save();
                } else {
                    // Insert a new record if it does not exist
                    VariantItems::create([
                        'varaint_id' => $existingvehiclevariant->id,
                        'model_specification_id' => $specificationData['specification_id'],
                        'model_specification_options_id' => $specificationData['value'],
                    ]);
                }
            }
            }
            else
            {
        $existingVariantop = Varaint::where('brands_id', $request->input('brands_id'))
        ->where('master_model_lines_id', $request->input('master_model_lines_id'))
        ->where('fuel_type', $request->input('fuel_type'))
        ->where('engine', $request->input('engine'))
        ->where('coo', $request->input('coo'))
        ->where('my', $request->input('my'))
        ->where('drive_train', $request->input('drive_train'))
        ->where('gearbox', $request->input('gearbox'))
        ->where('steering', $request->input('steering'))
        ->where('upholestry', $request->input('upholestry'))
        ->whereExists(function ($query) use ($selectedSpecifications) {
            $query->select(DB::raw(1))
                ->from('variant_items')
                ->whereColumn('variant_items.varaint_id', 'varaints.id')
                ->where(function ($query) use ($selectedSpecifications) {
                    foreach ($selectedSpecifications as $specificationData) {
                        $query->orWhere(function ($q) use ($specificationData) {
                            $q->where('variant_items.model_specification_id', $specificationData['specification_id'])
                            ->where('variant_items.model_specification_options_id', $specificationData['value']);
                        });
                    }
                });
        })
        ->first();
        if ($existingVariantop) {
            $existingVariantId = $existingVariantop->id;
            $vehiclese = Vehicles::where('varaints_id', $existingVariantId)->where('id', $inspection->vehicle_id)->first();
            if ($vehiclese) {
                return redirect()->route('approvalsinspection.index')->with('success', 'Inspection Approval successfully Done.');
            }
            else 
            {
                $vehicle = Vehicles::where('id', $inspection->vehicle_id)->first();
                $oldVariantName = Varaint::find($vehicle->varaints_id)->name;
                $newVariantName = Varaint::find($existingVariantId)->name;
                if($vehicle->varaints_id != $existingVariantId)
                {
                    $newvariantstorehistory = New VehicleVariantHistories ();
                    $newvariantstorehistory->varaints_old = $vehicle->varaints_id;
                    $newvariantstorehistory->varaints_new = $existingVariantId;
                    $newvariantstorehistory->vehicles_id = $inspection->vehicle_id;
                    $newvariantstorehistory->save();
                }
                Vehicles::where('id', $inspection->vehicle_id)->update(['varaints_id' => $existingVariantId]);
                Log::info('Variant Change Detected 7. Vehicle Variant ID updated (approveInspection)', [
                    'source' => 'variant decision block',
                    'vehicle_id' => $inspection->vehicle_id,
                    'old_varaints_id' => $vehicle->varaints_id,
                    'new_varaints_id' => $existingVariantId,
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name ?? 'N/A',
                    'timestamp' => now()->toDateTimeString()
                ]);
                $updatedVehicle = Vehicles::find($inspection->vehicle_id);
                $vehicleslog = new Vehicleslog();
                $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                $currentDateTime = Carbon::now($dubaiTimeZone);
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Approved Updated QC Values';
                $vehicleslog->vehicles_id = $inspection->vehicle_id;
                $vehicleslog->field = 'Variant Change';
                $vehicleslog->old_value = $oldVariantName;
                $vehicleslog->new_value = $newVariantName;
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->save();
                $purchasingOrder = PurchasingOrder::where('id', $vehicles->purchasing_order_id)->first();
                $orderUrl = url('/purchasing-order/' . $purchasingOrder->id);
                $vehiclesVIN = $vehicles->vin;
                $variant = $vehicle->variant;
                $brandName = $variant->brand->brand_name;
                $modelLine = $variant->master_model_lines->model_line;
                if($purchasingOrder->is_demand_planning_po == 1)
                {
                    $recipients = ['team.dp@milele.com'];
                    try {
                        Mail::to($recipients)->send(new QCUpdateNotification(
                        $purchasingOrder->po_number,
                        $purchasingOrder->pl_number,
                        $vehiclesVIN,
                        $brandName,
                        $modelLine,
                        $oldVariantName,
                        $newVariantName,
                        $orderUrl
                        ));
                    } catch (\Exception $e) {
                        \Log::error($e);
                    }

                }
                else
                {
                    $recipients = ['abdul@milele.com'];
                    try {
                        Mail::to($recipients)->send(new QCUpdateNotification(
                        $purchasingOrder->po_number,
                        $purchasingOrder->pl_number,
                        $vehiclesVIN,
                        $brandName,
                        $modelLine,
                        $oldVariantName,
                        $newVariantName,
                        $orderUrl
                        ));
                    } catch (\Exception $e) {
                        \Log::error($e);
                    }

                }
                $detailText = "PO Number: " . $purchasingOrder->po_number . "\n" .
                    "PFI Number: " . $purchasingOrder->pl_number . "\n" .
                    "Stage: " . "QC Inspection Variant Change\n" .
                    "Old Variant: " . $oldVariantName . "\n" .
                    "New Variant: " . $newVariantName . "\n" .
                    "Order URL: " . $orderUrl;
                    $notification = New DepartmentNotifications();
                    $notification->module = 'QC / Inspection';
                    $notification->type = 'Information';
                    $notification->detail = $detailText;
                    $notification->save();
                    if($purchasingOrder->is_demand_planning_po == 1)
                {
                    $dnaccess = New Dnaccess();
                    $dnaccess->master_departments_id = 4; 
                    $dnaccess->department_notifications_id = $notification->id;
                    $dnaccess->save();
                } 
                else
                {
                    $dnaccess = New Dnaccess();
                    $dnaccess->master_departments_id = 15; 
                    $dnaccess->department_notifications_id = $notification->id;
                    $dnaccess->save();
                }
            }
        }
        else
        {
            $existingspecifications = Varaint::where('brands_id', $request->input('brands_id'))
            ->where('master_model_lines_id', $request->input('master_model_lines_id'))
            ->where('coo', $request->input('coo'))
            ->where('my', $request->input('my'))
            ->where('engine', $request->input('engine'))
            ->where('drive_train', $request->input('drive_train'))
            ->where('gearbox', $request->input('gearbox'))
            ->where('upholestry', $request->input('upholestry'))
            ->whereExists(function ($query) use ($selectedSpecifications) {
                $query->select(DB::raw(1))
                    ->from('variant_items')
                    ->whereColumn('variant_items.varaint_id', 'varaints.id')
                    ->where(function ($query) use ($selectedSpecifications) {
                        foreach ($selectedSpecifications as $specificationData) {
                            $query->orWhere(function ($q) use ($specificationData) {
                                $q->where('variant_items.model_specification_id', $specificationData['specification_id'])
                                  ->where('variant_items.model_specification_options_id', $specificationData['value']);
                            });
                        }
                    });
            })
            ->orderBy('created_at', 'desc')
            ->first();
        if ($existingspecifications) {
            $steering = $request->input('steering');
            if($steering == "LHD"){
                $steeringn = "L";
            }
            else{
                $steeringn = "R";
            }
            $master_model_lines_id = $request->input('master_model_lines_id');
            $engine = $request->input('engine');
            $fuel_type = $request->input('fuel_type');
            if($fuel_type == "Petrol")
            {
                $f = "P";
            }
            else if($fuel_type == "Diesel") 
            {
                $f = "D";
            }
            else if($fuel_type == "PHEV") 
            {
                $f = "PH";
            }
            else if($fuel_type == "MHEV") 
            {
                $f = "MH";
            }
            else
            {
                $f = "E";
            }
            $model_line = MasterModelLines::where('id', $master_model_lines_id)->pluck('model_line')->first();
            $existingName = $existingspecifications->name;
            $parts = explode('_', $existingName);
            
            if (count($parts) > 1) {
                $lastNumber = end($parts);
            
                if (is_numeric($lastNumber)) {
                    $namepart = $steeringn . $model_line . $engine . $f;
                    $newNumber = (int)$lastNumber + 1;
                    $name = $namepart . '_' . $newNumber;  // Use $namepart directly
                } else {
                    $NewexistingName = substr($existingName, 0, -1);
                    $parts = explode('_', $NewexistingName);
            
                    if (count($parts) > 1) {
                        $lastNumber = end($parts);
            
                        if (is_numeric($lastNumber)) {
                            $namepart =  $steeringn . $model_line . $engine . $f;
                            $newNumber = (int)$lastNumber + 1;
                            $name = $namepart . '_' . $newNumber;  // Use $namepart directly
                        } 
                    }
                }
            }        
             else {
                    $name = $existingName . '_1';
            }
        }
        else{
        $maxVariant = Varaint::where('brands_id', $request->input('brands_id'))
        ->where('master_model_lines_id', $request->input('master_model_lines_id'))
        ->where('fuel_type', $request->input('fuel_type'))
        ->where('engine', $request->input('engine'))
        ->where('steering', $request->input('steering'))
        ->orderBy('name', 'desc')
        ->first();
        $master_model_lines_id = $request->input('master_model_lines_id');
        $steering = $request->input('steering');
        if($steering == "LHD"){
            $steeringn = "L";
        }
        else{
            $steeringn = "R";
        }
        $engine = $request->input('engine');
        $fuel_type = $request->input('fuel_type');
        if($fuel_type == "Petrol")
        {
            $f = "P";
        }
        else if($fuel_type == "Diesel") 
        {
            $f = "D";
        }
        else if($fuel_type == "PHEV") 
        {
            $f = "PH";
        }
        else if($fuel_type == "MHEV") 
        {
            $f = "MH";
        }
        else
        {
            $f = "E";
        }
        $model_line = MasterModelLines::where('id', $master_model_lines_id)->pluck('model_line')->first();
        if ($maxVariant) {
        $existingName = $maxVariant->name;
        $parts = explode('_', $existingName);
        if (count($parts) > 1) {
            $lastNumber = end($parts);
            if (is_numeric($lastNumber)) {
                $newNumber = (int)$lastNumber + 1;
                array_pop($parts);
                $name = implode('_', $parts) . '_' . $newNumber;
            } else {
                $NewexistingName = substr($existingName, 0, -1);
                $parts = explode('_', $NewexistingName);
                if (count($parts) > 1) {
                    $lastNumber = end($parts);
                    if (is_numeric($lastNumber)) {
                        $newNumber = (int)$lastNumber + 1;
                        array_pop($parts);
                        $name = implode('_', $parts) . '_' . $newNumber;
                    } 
                }
            }
        } else {
                $name = $existingName . '_1';
        }
        } 
        else {
                $name = $steeringn . $model_line . $engine . $f . '_1';
        }
    }
        (new UserActivityController)->createActivity('Creating New Variant');
        $model_details= $request->input('model_detail');
        if($model_details == null){
        $steering = $request->input('steering');
        $master_model_lines_id = $request->input('master_model_lines_id');
        $model_line = MasterModelLines::where('id', $master_model_lines_id)->pluck('model_line')->first();
        $engine = $request->input('engine');
        $gearbox = $request->input('gearbox');
        $fuel_type = $request->input('fuel_type');
        if($fuel_type == "Petrol")
        {
            $f = "P";
        }
        else if($fuel_type == "Diesel") 
        {
            $f = "D";
        }
        else if($fuel_type == "PHEV") 
        {
            $f = "PH";
        }
        else if($fuel_type == "MHEV") 
        {
            $f = "MH";
        }
        else
        {
            $f = "E";
        }
        if($gearbox == "Auto")
        {
            $gearbox = "AT";
        }
        if($gearbox == "Manual")
        {
            $gearbox = "MT";
        }
        $model_details = $steering . ' ' . $model_line . ' ' . $engine . ' ' . $f . ' ' . $gearbox;
        }
        $variant_details= $request->input('variant');
        if($variant_details == null)
        {
            $steering = $request->input('steering');
            $master_model_lines_id = $request->input('master_model_lines_id');
            $model_line = MasterModelLines::where('id', $master_model_lines_id)->pluck('model_line')->first();
            $engine = $request->input('engine');
            $gearbox = $request->input('gearbox');
            $coo = $request->input('coo');
            $my = $request->input('my');
            $drive_train = $request->input('drive_train');
            $upholestry = $request->input('upholestry');
            $fuel_type = $request->input('fuel_type');
            if($fuel_type == "Petrol")
            {
                $f = "P";
            }
            else if($fuel_type == "Diesel") 
            {
                $f = "D";
            }
            else if($fuel_type == "PHEV") 
            {
                $f = "PH";
            }
            else if($fuel_type == "MHEV") 
            {
                $f = "MH";
            }
            else
            {
                $f = "E";
            }
            $variant_details = $my . ',' . $steering . ',' . $model_line . ',' . $engine . ',' . $gearbox . ',' . $fuel_type . ',' . $gearbox . ',' . $coo . ',' . $drive_train . ',' . $upholestry;
        }

         $isVariantNameExist = Varaint::where('name', $name)->first();
        if($isVariantNameExist) {
            return redirect()->back()->with('error', 'Variant with the same Name( '. $name.' )already exists');
        }
        $variant = new Varaint();
        $variant->brands_id = $request->input('brands_id');
        $variant->master_model_lines_id = $request->input('master_model_lines_id');
        $variant->steering = $request->input('steering');
        $variant->fuel_type = $request->input('fuel_type');
        $variant->engine = $request->input('engine');
        $variant->upholestry = $request->input('upholestry');
        $variant->coo = $request->input('coo');
        $variant->drive_train = $request->input('drive_train');
        $variant->gearbox = $request->input('gearbox');
        $variant->name = $name;
        $variant->model_detail = $model_details;
        $variant->detail = $variant_details;
        $variant->my = $request->input('my');
        $variant->save();
        $variantId = $variant->id;
        foreach ($selectedSpecifications as $specificationData) {
            $specification = new VariantItems();
            $specification->varaint_id = $variantId;
            $specification->model_specification_id = $specificationData['specification_id'];
            $specification->model_specification_options_id = $specificationData['value'];
            $specification->save();
        }
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
        $variantlog = new Variantlog();
        $variantlog->time = $currentDateTime->toTimeString();
        $variantlog->date = $currentDateTime->toDateString();
        $variantlog->status = 'New Created';
        $variantlog->variant_id = $variantId;
        $variantlog->created_by = auth()->user()->id;
        $variantlog->save();
        $vehicle = Vehicles::where('id', $inspection->vehicle_id)->first();
        $oldVariantName = Varaint::where('id', $vehicle->varaints_id)->pluck('name')->first();
        $newVariantName = Varaint::where('id', $variantId)->pluck('name')->first();
        if($vehicle->varaints_id != $variantId)
        {
            $newvariantstorehistory = New VehicleVariantHistories ();
            $newvariantstorehistory->varaints_old = $vehicle->varaints_id;
            $newvariantstorehistory->varaints_new = $variantId;
            $newvariantstorehistory->vehicles_id = $inspection->vehicle_id;
            $newvariantstorehistory->save();
        }
                Vehicles::where('id', $inspection->vehicle_id)
                ->update(['varaints_id' => $variantId]);
                Log::info('Variant Change Detected 8. Vehicle varaints_id updated (approveInspection)', [
                    'source' => 'variant update block (approval/inspection)',
                    'vehicle_id' => $inspection->vehicle_id,
                    'old_varaints_id' => $vehicle->varaints_id,
                    'new_varaints_id' => $variantId,
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name ?? 'N/A',
                    'timestamp' => now()->toDateTimeString()
                ]);
                $vehicleslog = new Vehicleslog();
                $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                $currentDateTime = Carbon::now($dubaiTimeZone);
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Approved Updated QC Values';
                $vehicleslog->vehicles_id = $inspection->vehicle_id;
                $vehicleslog->field = 'Variant Change';
                $vehicleslog->old_value = $oldVariantName;
                $vehicleslog->new_value = $newVariantName;
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->save();
                $vehicles = Vehicles::where('id', $inspection->vehicle_id)->first();
                $purchasingOrder = PurchasingOrder::where('id', $vehicles->purchasing_order_id)->first();
                $orderUrl = url('/purchasing-order/' . $purchasingOrder->id);
                $vehiclesVIN = $vehicles->vin;
                $variant = $vehicle->variant;
                $brandName = $variant->brand->brand_name;
                $modelLine = $variant->master_model_lines->model_line;
                if($purchasingOrder->is_demand_planning_po == 1)
                {
                    $recipients = ['team.dp@milele.com'];
                    try {
                        Mail::to($recipients)->send(new QCUpdateNotification(
                            $purchasingOrder->po_number,
                            $purchasingOrder->pl_number,
                            $vehiclesVIN,
                            $brandName,
                            $modelLine,
                            $oldVariantName,
                            $newVariantName,
                            $orderUrl
                        ));
                    } catch (\Exception $e) {
                        \Log::error($e);
                    }

                }
                else
                {
                    $recipients = ['abdul@milele.com'];
                    try {
                        Mail::to($recipients)->send(new QCUpdateNotification(
                        $purchasingOrder->po_number,
                        $purchasingOrder->pl_number,
                        $vehiclesVIN,
                        $brandName,
                        $modelLine,
                        $oldVariantName,
                        $newVariantName,
                        $orderUrl
                    ));
                    } catch (\Exception $e) {
                        \Log::error($e);
                    }

                }
            $detailText = "PO Number: " . $purchasingOrder->po_number . "\n" .
            "PFI Number: " . $purchasingOrder->pl_number . "\n" .
            "Stage: " . "QC Inspection Done\n" .
            "Old Variant: " . $oldVariantName . " Vehicles\n" .
            "New Variant: " . $newVariantName . " Vehicles\n" .
            "Order URL: " . $orderUrl;
            $notification = New DepartmentNotifications();
            $notification->module = 'QC / Inspection';
            $notification->type = 'Information';
            $notification->detail = $detailText;
            $notification->save();
            if($purchasingOrder->is_demand_planning_po == 1)
                    {
                        $dnaccess = New Dnaccess();
                        $dnaccess->master_departments_id = 4; 
                        $dnaccess->department_notifications_id = $notification->id;
                        $dnaccess->save();
                    } 
                    else
                    {
                        $dnaccess = New Dnaccess();
                        $dnaccess->master_departments_id = 15; 
                        $dnaccess->department_notifications_id = $notification->id;
                        $dnaccess->save();
                    }
            }
        }  
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
            $additionalInfo = Vehicles::select('master_model_lines.model_line', 'vehicles.vin', 'color_codes.name as int_colour', 'ext_color.name as ext_colour', 'warehouse.name as location')
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
        return response()->json([
            'routineInspectionData' => $routineInspectionData,
            'additionalInfo' => $additionalInfo,
            'incidentData' => $incidentData,
            'inspection' => $inspection->id,
        ]);
    }
    public function approvalsrotein(Request $request)
    {
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
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
        $additionalInfo = Vehicles::select('master_model_lines.model_line', 'vehicles.vin', 'color_codes.name as int_colour', 'ext_color.name as ext_colour', 'warehouse.name as location')
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
            
            // Send PDI completion notification to sales person
            $this->sendPDICompletionNotification($vehicles, $currentDate);
            
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
            if ($routineInspection) {
                $routineInspection->condition = $data['condition'];
                $routineInspection->remarks = $data['remarks'];
                $routineInspection->save();
            }
        }
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
    public function getincidentInspectionData($vehicleId)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Open The PDI Inspection";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $inspection = Inspection::find($vehicleId);
        $additionalInfo = Vehicles::select('master_model_lines.model_line', 'vehicles.vin', 'color_codes.name as int_colour', 'ext_color.name as ext_colour', 'warehouse.name as location')
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
    public function addingnetsuitegrn(Request $request)
{
    $useractivities = new UserActivities();
    $useractivities->activity = "Open The Netsuite GRN";
    $useractivities->users_id = Auth::id();
    $useractivities->save();

    if ($request->ajax()) {
        $status = $request->input('status');
        if ($status == "pending") {
            $data = Vehicles::select([
                'vehicles.id',
                    'brands.brand_name',
                    DB::raw("DATE_FORMAT(vehicles.inspection_date, '%d-%b-%Y') as inspectiondate"),
                    'vehicles.vin',
                    'varaints.name as variant',
                    'varaints.model_detail',
                    'varaints.detail',
                    'master_model_lines.model_line',
                    'int_color.name as interior_color',
                    'ex_color.name as exterior_color',
                    'purchasing_order.po_number',
                    'vehicle_variant_histories.varaints_old',
                    'vehicle_variant_histories.varaints_new',
                    DB::raw("DATE_FORMAT(movements_reference.date, '%d-%b-%Y') as grn_date"),
                ])
                ->leftJoin('vehicle_variant_histories', 'vehicle_variant_histories.vehicles_id', '=', 'vehicles.id')
                ->leftJoin('movement_grns', 'vehicles.movement_grn_id', '=', 'movement_grns.id')
                ->leftJoin('movements_reference', 'movement_grns.movement_reference_id', '=', 'movements_reference.id')
                ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
                ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
                ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
                ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
                ->whereNotNull('vehicles.inspection_date')
                ->whereNotNull('vehicles.vin')
                ->whereNotNull('vehicles.movement_grn_id')
                ->whereNull('movement_grns.grn_number')
                ->groupBy('vehicles.id');
        } else {
            $data = Vehicles::select([
                'vehicles.id',
                'brands.brand_name',
                DB::raw("DATE_FORMAT(vehicles.inspection_date, '%d-%b-%Y') as inspectiondate"),
                'vehicles.vin',
                'varaints.name as variant',
                'varaints.model_detail',
                'varaints.detail',
                'master_model_lines.model_line',
                'int_color.name as interior_color',
                'ex_color.name as exterior_color',
                'purchasing_order.po_number',
                'vehicle_variant_histories.varaints_old',
                'vehicle_variant_histories.varaints_new',
                'movement_grns.grn_number',
                DB::raw("DATE_FORMAT(movements_reference.date, '%d-%b-%Y') as grn_date"),
            ])
            ->leftJoin('vehicle_variant_histories', 'vehicle_variant_histories.vehicles_id', '=', 'vehicles.id')
            ->leftJoin('movement_grns', 'vehicles.movement_grn_id', '=', 'movement_grns.id')
            ->leftJoin('movements_reference', 'movement_grns.movement_reference_id', '=', 'movements_reference.id')
            ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
            ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
            ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
            ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
            ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
            ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
            ->whereNotNull('vehicles.inspection_date')
            ->whereNotNull('vehicles.vin')
            ->whereNotNull('movement_grns.grn_number')
            ->groupBy('vehicles.id');
        }

        return DataTables::of($data)->toJson();
    }

    return view('inspection.grnview');
}
public function submitGrn(Request $request)
    {
        // info($request->all());
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:vehicles,id',
            'grn' => 'required|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        // Retrieve the vehicle by ID
        $vehicle = Vehicles::find($request->vehicle_id);
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle not found',
            ], 404);
        }
        // get po corresponding to the vin and update GRN number to the particular 
        // Retrieve the GRN record by grn_id in the vehicle record
        // take data from Movement GRN table to update the GRN Numebr
        // $grnRecord = Grn::find($vehicle->grn_id);
        $grnRecord = MovementGrn::find($vehicle->movement_grn_id);
        if (!$grnRecord) {
            return response()->json([
                'success' => false,
                'message' => 'GRN record not found',
            ], 404);
        }

        // Update the GRN record with the new GRN number
        $grnRecord->grn_number = $request->grn;
        $grnRecord->save();

        return response()->json([
            'success' => true,
            'message' => 'Netsuite GRN updated successfully',
        ]);

    }
// public function addGrn(Request $request)
// {
//     // Validate the request data
//     $validator = Validator::make($request->all(), [
//         'vehicle_id' => 'required|exists:vehicles,id',
//         'grn' => 'required|string|max:255',
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'success' => false,
//             'errors' => $validator->errors(),
//         ], 422);
//     }

//     // Retrieve the vehicle by ID
//     $vehicle = Vehicles::find($request->vehicle_id);
    
//     if (!$vehicle) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Vehicle not found',
//         ], 404);
//     }
//     $oldgrn = MovementGrn::where('id', $vehicle->movement_grn_id)->first();
//     // Create new GRN record
//     $grnRecord = new Grn();
//     $grnRecord->grn_number = $request->grn;
//     $grnRecord->date = $oldgrn->date;
//     $grnRecord->save();

//     // Associate the GRN with the vehicle
//     $vehicle->grn_id = $grnRecord->id;
//     $vehicle->save();

//     return response()->json([
//         'success' => true,
//         'message' => 'Netsuite GRN added successfully',
//     ]);
// }

public function addGrn(Request $request)
{
    $validator = Validator::make($request->all(), [
        'vehicle_id' => 'required|exists:vehicles,id',
        'grn' => 'required|string|max:255',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors(),
        ], 422);
    }

    $vehicle = Vehicles::find($request->vehicle_id);

    if (!$vehicle) {
        return response()->json([
            'success' => false,
            'message' => 'Vehicle not found',
        ], 404);
    }

    $oldgrn = MovementGrn::where('id', $vehicle->movement_grn_id)->first();

    $grnRecord = new Grn();
    $grnRecord->grn_number = $request->grn;
    $grnRecord->date = $oldgrn ? $oldgrn->date : null;
    $grnRecord->save();

    $vehicle->grn_id = $grnRecord->id;
    $vehicle->save();

    // Also update the Movement GRN record so all existing logic and views (which use movement_grns.grn_number)
    // reflect the newly added Netsuite GRN number.
    if ($vehicle->movement_grn_id) {
        $movementGrn = MovementGrn::find($vehicle->movement_grn_id);
        if ($movementGrn) {
            $movementGrn->grn_number = $request->grn;
            $movementGrn->save();
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Netsuite GRN added successfully',
    ]);
}
public function addingnetsuitegdn(Request $request)
{
    $useractivities = new UserActivities();
    $useractivities->activity = "Open The Netsuite GDN";
    $useractivities->users_id = Auth::id();
    $useractivities->save();
    if ($request->ajax()) {
        $status = $request->input('status');
        if ($status == "pending") {
            $data = Vehicles::select([
                    'vehicles.id',
                    'brands.brand_name',
                    DB::raw("DATE_FORMAT(gdn.date, '%d-%b-%Y') as gdndate"),
                    'vehicles.vin',
                    'varaints.name as variant',
                    'varaints.model_detail',
                    'varaints.detail',
                    'master_model_lines.model_line',
                    'int_color.name as interior_color',
                    'ex_color.name as exterior_color',
                    'purchasing_order.po_number',
                    'so.so_number'
                ])
                ->leftJoin('vehicle_variant_histories', 'vehicle_variant_histories.vehicles_id', '=', 'vehicles.id')
                ->leftJoin('gdn', 'gdn.id', '=', 'vehicles.gdn_id')
                ->leftJoin('so', 'so.id', '=', 'vehicles.so_id')
                ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
                ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
                ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
                ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
                ->whereNotNull('vehicles.vin')
                ->whereNotNull('vehicles.so_id')
                ->whereNotNull('vehicles.gdn_id')
                ->whereNull('gdn.gdn_number')
                ->groupBy('vehicles.id');
        } else {
            $data = Vehicles::select([
                'vehicles.id',
                    'brands.brand_name',
                    DB::raw("DATE_FORMAT(gdn.date, '%d-%b-%Y') as gdndateauto"),
                    'vehicles.vin',
                    'varaints.name as variant',
                    'varaints.model_detail',
                    'varaints.detail',
                    'master_model_lines.model_line',
                    'int_color.name as interior_color',
                    'ex_color.name as exterior_color',
                    'purchasing_order.po_number',
                    'so.so_number',
                    'gdn.gdn_number'
            ])
            ->leftJoin('vehicle_variant_histories', 'vehicle_variant_histories.vehicles_id', '=', 'vehicles.id')
                ->leftJoin('gdn', 'gdn.id', '=', 'vehicles.gdn_id')
                ->leftJoin('so', 'so.id', '=', 'vehicles.so_id')
                ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
                ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
                ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
                ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
                ->whereNotNull('vehicles.so_id')
                ->whereNotNull('vehicles.gdn_id')
            ->whereNotNull('vehicles.vin')
            ->whereNotNull('gdn.gdn_number')
            ->groupBy('vehicles.id');
        }

        return DataTables::of($data)->toJson();
    }

    return view('inspection.gdnview');
}
public function submitGdn(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:vehicles,id',
            'gdn' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        // Retrieve the vehicle by ID
        $vehicle = Vehicles::find($request->vehicle_id);
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle not found',
            ], 404);
        }
        // Retrieve the GRN record by grn_id in the vehicle record
        $gdnRecord = Gdn::find($vehicle->gdn_id);
        if (!$gdnRecord) {
            return response()->json([
                'success' => false,
                'message' => 'GRN record not found',
            ], 404);
        }
        // Update the GRN record with the new GRN number
        $gdnRecord->gdn_number = $request->gdn;
        $gdnRecord->save();
        return response()->json([
            'success' => true,
            'message' => 'Netsuite GRN updated successfully',
        ]);
    }
    public function addGdn(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:vehicles,id',
            'gdn' => 'required|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
    
        // Retrieve the vehicle by ID
        $vehicle = Vehicles::find($request->vehicle_id);
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle not found',
            ], 404);
        }
        $oldgdn = Gdn::where('id', $vehicle->gdn_id)->first();
        // Create new GRN record
        $gdnRecord = new Gdn();
        $gdnRecord->gdn_number = $request->gdn;
        $gdnRecord->date = $oldgdn->date;
        $gdnRecord->save();
        
        // Get Customer warehouse ID for location update
        $customerWarehouse = \App\Models\Warehouse::where('name', 'Customer')->first();
        $customerWarehouseId = $customerWarehouse ? $customerWarehouse->id : null;
        
        // Associate the GRN with the vehicle and update location to Customer
        $vehicle->gdn_id = $gdnRecord->id;
        if ($customerWarehouseId) {
            $vehicle->latest_location = $customerWarehouseId;
        }
        $vehicle->save();
        return response()->json([
            'success' => true,
            'message' => 'Netsuite GRN added successfully',
        ]);
    }

    /**
     * Send PDI completion notification to sales person
     */
    private function sendPDICompletionNotification($vehicle, $pdiDate)
    {
        try {
            // Load vehicle with relationships for complete data
            $vehicle->load(['so.salesperson', 'variant.brand', 'exterior', 'interior', 'warehouse', 'so']);
            
            // Get sales person from SO
            $salesPerson = $vehicle->so && $vehicle->so->salesperson ? $vehicle->so->salesperson : null;
            
            if ($salesPerson && $salesPerson->email) {
                Mail::to($salesPerson->email)->send(new PDICompletionNotification($vehicle, $salesPerson, $pdiDate));
                
                // Log successful notification
                \Log::info('PDI completion notification sent successfully', [
                    'vehicle_id' => $vehicle->id,
                    'vin' => $vehicle->vin,
                    'sales_person_email' => $salesPerson->email,
                    'pdi_date' => $pdiDate
                ]);
            } else {
                \Log::warning('PDI completion notification skipped - no sales person email found', [
                    'vehicle_id' => $vehicle->id,
                    'vin' => $vehicle->vin,
                    'so_id' => $vehicle->so_id
                ]);
            }
        } catch (\Exception $e) {
            // Log error but don't break the main flow
            \Log::error('Failed to send PDI completion notification', [
                'vehicle_id' => $vehicle->id,
                'vin' => $vehicle->vin,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
