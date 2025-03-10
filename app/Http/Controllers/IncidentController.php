<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Vehicles;
use App\Models\VehicleExtraItems;
use App\Models\VehicleApprovalRequests;
use App\Models\Varaint;
use App\Models\UserActivities;
use Illuminate\Support\Facades\Auth;
use App\Models\Brand;
use App\Models\VehiclePicture;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use App\Models\IncidentWork;
use App\Models\ColorCode;
use App\Models\Incident;
use App\Models\Inspection;
use Illuminate\Support\Facades\File;
use App\Models\Vehicleslog;
use App\Models\Pdi;
use App\Models\MasterModelLines;

class IncidentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "View Incident Information";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        if ($request->ajax()) {
            $status = $request->input('status');
            $searchValue = $request->input('search.value');
            if($status === "Repaired")
            {
            $data = Incident::select( [
                    'incident.id as incidentsnumber',
                    'incident.type',
                    'incident.part_po_number',
                    'incident.update_remarks',
                    'incident.narration',
                    'incident.reason',
                    'incident.driven_by',
                    'incident.responsivity',
                    DB::raw("DATE_FORMAT(inspection.created_at, '%d-%b-%Y') as created_at_repaired"),
                    'inspection.remark',
                    'warehouse.name as location',
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
                ->leftJoin('warehouse', 'vehicles.latest_location', '=', 'warehouse.id')
                ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
                ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
                ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                ->leftJoin('inspection', 'incident.inspection_id', '=', 'inspection.id')
                ->where('incident.status', 'Repaired');
                $data = $data->groupBy('vehicles.id');
            } 
            if($status === "Pending")
            {
            $data = Incident::select( [
                    'incident.id as incidentsnumber',
                    'incident.type',
                    'incident.part_po_number',
                    'incident.vehicle_status',
                    'incident.status',
                    'incident.update_remarks',
                    'incident.narration',
                    'incident.reason',
                    'incident.driven_by',
                    'incident.responsivity',
                    DB::raw("CONCAT(DATEDIFF(NOW(), incident.reported_date), ' days') as aging"),
                    DB::raw("DATE_FORMAT(inspection.created_at, '%d-%b-%Y') as created_at_pending"),
                    'inspection.remark',
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
                ->where('incident.status', 'approved')
                ->orWhere('incident.status', 'Re Work');
                $data = $data->groupBy('vehicles.id');
            } 
            if($status === "vehicles_repaired_confirmed")
            {
            $data = Incident::select( [
                    'incident.id as incidentsnumber',
                    'incident.type',
                    'incident.narration',
                    'incident.reason',
                    'incident.driven_by',
                    'incident.responsivity',
                    DB::raw("DATE_FORMAT(inspection.created_at, '%d-%b-%Y') as created_at"),
                    'inspection.remark',
                    'warehouse.name as location',
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
                ->leftJoin('warehouse', 'vehicles.latest_location', '=', 'warehouse.id')
                ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
                ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
                ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                ->leftJoin('inspection', 'incident.inspection_id', '=', 'inspection.id')
                ->where('incident.status', 'Repaired Completed');
                $data = $data->groupBy('vehicles.id');
            } 
                return DataTables::of($data)
                ->toJson();
        }
        return view('inspection.incidentview');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Create New Incident Page Opening";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $vehicle = Vehicles::whereNotNull('vin')->get();
        return view('inspection.createincident', compact('vehicle')); 
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
    public function updatestatus(Request $request)
{
    $useractivities =  New UserActivities();
        $useractivities->activity = "Update the Status of Part Procurmenet into Incident";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $currentDate = Carbon::now();
    $IncidentId = $request->input('IncidentId');
    $part_po_number = $request->input('part_po_number');
    $update_remarks = $request->input('update_remarks');
    $vehicle_status = $request->input('vehicle_status');
    $incidents = Incident::find($IncidentId);
    $incidents->part_po_number = $part_po_number;
    $incidents->vehicle_status = $vehicle_status;
    $incidents->update_remarks = $update_remarks;
    if($vehicle_status == "Work Completed")
    {
        $incidents->status = "Repaired";
        $incidents->repaired_date = $currentDate;
    }
    $incidents->save();
    return response()->json(['message' => 'Links saved successfully']);
}
public function showre($id)
{
    $useractivities =  New UserActivities();
        $useractivities->activity = "View the Re-inspection Report Create";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $Incident = Incident::findOrFail($id);
    $inspection = Inspection::findOrFail($Incident->inspection_id);
    $vehicle = Vehicles::findOrFail($Incident->vehicle_id);
    $variant = Varaint::find($vehicle->varaints_id);
    $brand = Brand::find($variant->brands_id);
    $model_line = MasterModelLines::find($variant->master_model_lines_id);
    $intColor = ColorCode::find($vehicle->int_colour);
    $extColor = ColorCode::find($vehicle->ex_colour);
    if ($inspection->stage === "PDI") {
        $PdiInspectionData = Pdi::select('checking_item', 'reciving', 'status')
            ->where('inspection_id', $inspection->id)
            ->get();
    } else {
        $PdiInspectionData = null; // Set to null if not "PDI" stage
    }
    return view('inspection.incidentinspection', compact('PdiInspectionData','vehicle', 'brand', 'intColor', 'extColor', 'variant', 'model_line', 'Incident', 'inspection'));
}
public function reinspectionsforapp(Request $request)
{
    $useractivities =  New UserActivities();
        $useractivities->activity = "Submit the Re-inspection report for approval";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $currentDate = Carbon::now();
    $Incidentid = $request->input('Incidentid');
    foreach ($request->input('work') as $key => $work) {
        $incidentWork = new IncidentWork();
        $incidentWork->works = $work;
        $incidentWork->status = $request->input('status')[$key];
        $incidentWork->remarks = $request->input('remarks')[$key] ?? null;
        $incidentWork->incident_id = $Incidentid;
        $incidentWork->save();
    }
    $incidents = Incident::findOrFail($Incidentid);
    $incidents->status = "repairingapproval";
    $incidents->reinspection_date = $currentDate;
    $incidents->save();
    return redirect()->route('incident.index')->with('success', 'Re - Inspection successfully Done');
}
public function getIncidentWorks($incidentId)
{
    $incidents = IncidentWork::where('incident_id', $incidentId)->get();
    if ($incidents->isEmpty()) {
        return response()->json(['error' => 'Incident not found'], 404);
    }
    $data = [];
    foreach ($incidents as $incident) {
        $data[] = [
            'works' => $incident->works,
            'status' => $incident->status,
            'remarks' => $incident->remarks,
        ];
    }
    return response()->json($data);
}
public function approvals(Request $request)
{
    $useractivities =  New UserActivities();
        $useractivities->activity = "Approved the Incident Inspection";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $incidents = $request->input('incidentId');
    $incidentsapprove = Incident::findOrFail($incidents);
    $incidentsapprove->status = "Repaired Completed";
    $incidentsapprove->save();
    return response()->json(['message' => 'Approved Repaired Inspection']);
}

    public function updatevehicledetails(Request $request)
         {
             try {
                 $selectedVIN = $request->input('vin');
 
                 $vehicle = Vehicles::where('vin', $selectedVIN)->first();
 
                 if (!$vehicle) {
                     return response()->json(['error' => 'Vehicle not found'], 404);
                 }
 
                 $variant = Varaint::find($vehicle->varaints_id); 
 
                 $interiorColor = $vehicle->int_colour ? ColorCode::find($vehicle->int_colour) : null;
                 $exteriorColor = $vehicle->ex_colour ? ColorCode::find($vehicle->ex_colour) : null;
 
                 $brand = $variant ? Brand::find($variant->brands_id) : null;
                 $modelLine = $variant ? MasterModelLines::find($variant->master_model_lines_id) : null;
 
                 $vehicleDetails = [
                     'brand' => $brand ? $brand->brand_name : 'N/A',
                     'modelLine' => $modelLine ? $modelLine->model_line : 'N/A',
                     'interiorColor' => $interiorColor ? $interiorColor->name : 'N/A',
                     'exteriorColor' => $exteriorColor ? $exteriorColor->name : 'N/A',
                     'variant' => $variant ? $variant->name : 'N/A',
                     'detail' => $variant ? $variant->detail : 'N/A',
                     'name' => $variant ? $variant->name : 'N/A',
                     'my' => $variant ? $variant->my : 'N/A',
                     'modeldetail' => $variant ? $variant->model_detail : 'N/A',
                     'steering' => $variant ? $variant->steering : 'N/A',
                     'seat' => $variant ? $variant->seat : 'N/A',
                     'fuel_type' => $variant ? $variant->fuel_type : 'N/A',
                     'gearbox' => $variant ? $variant->gearbox : 'N/A',
                     'py' => $vehicle ? $vehicle->ppmmyyy : 'N/A',
                     'interiorColorName' => $interiorColor ? $interiorColor->name : 'N/A',
                     'exteriorColorName' => $exteriorColor ? $exteriorColor->name : 'N/A',
                 ];
 
                 return response()->json($vehicleDetails);
             } catch (\Exception $e) {
                 \Log::error('Error fetching vehicle details: ' . $e->getMessage());
                 return response()->json(['error' => 'Internal Server Error'], 500);
             }
         }
 
    public function createincidents(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Create Incident";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $canvasImageDataURL = $request->input('canvas_image');
        $vin = $request->input('vin');
        $vehicle = Vehicles::where('vin', $vin)->first();
        $inspection = New Inspection();
        $inspection->status = "Pending";
        $inspection->vehicle_id =  $vehicle->id;
        $inspection->created_by =  Auth::id();
        $inspection->remark =  $request->input('remarks');
        $inspection->stage =  "Incident";
        $inspection->save();
        $canvasImageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $canvasImageDataURL));
        $filename = 'canvas_image_' . uniqid() . '.png';
        $directory = public_path('qc');
        File::makeDirectory($directory, $mode = 0777, true, true);
        File::put($directory . '/' . $filename, $canvasImageData);
    $reasons = [
        'overspeed',
        'weather',
        'vehicle_defects',
        'negligence',
        'sudden_halt',
        'road_defects',
        'fatigue',
        'no_safety_distance',
        'using_gsm',
        'overtaking',
        'wrong_action',
    ];
    $selectedReasons = [];
    foreach ($reasons as $reason) {
        if ($request->has($reason)) {
            $selectedReasons[] = $reason;
        }
    }
        $incidentData = [
            'vehicle_id' => $vehicle->id,
            'type' => $request->input('incidenttype'),
            'narration' => $request->input('narration'),
            'detail' => $request->input('damageDetails'),
            'driven_by' => $request->input('drivenBy'),
            'responsivity' => $request->input('responsibility'),
            'inspection_id' => $inspection->id,
            'file_path' => $filename,
            'created_by' => Auth::id(),
            'status' => "Pending",
            'reason' => implode(', ', $selectedReasons),
        ];
        Incident::create($incidentData);
        return redirect()->route('incident.index')->with('success', 'Incident Submit For Approval successfully');
    }
    public function reinspectionsforre(Request $request)
    {
    $useractivities =  New UserActivities();
        $useractivities->activity = "Submit the Re-inspection report for approval";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $currentDate = Carbon::now();
    $Incidentid = $request->input('Incidentid');
    foreach ($request->input('work') as $key => $work) {
        $incidentWork = new IncidentWork();
        $incidentWork->works = $work;
        $incidentWork->status = $request->input('status')[$key];
        $incidentWork->remarks = $request->input('remarks')[$key] ?? null;
        $incidentWork->incident_id = $Incidentid;
        $incidentWork->save();
    }
    $incidents = Incident::findOrFail($Incidentid);
    $incidents->status = "Re Work";
    $incidents->reinspection_date = $currentDate;
    $incidents->vehicle_status = "Re Work";
    $incidents->save();
    return response()->json(['message' => 'Re Work Update successfully']);
    }
    public function getPdiInspection($incidentId)
    {
        (new UserActivityController)->createActivity('Open PDI Inspection');
        $Incident = Incident::findOrFail($incidentId);
        $inspection = Pdi::where('inspection_id', $Incident->inspection_id)->get();
        if (!$inspection) {
            return response()->json(['message' => 'PDI inspection not found'], 404);
        }
        return response()->json($inspection);
    }
    public function getIncidentDetails($incidentId)
    {
        $incident = Incident::findOrFail($incidentId);
        return response()->json($incident);
    }
    public function reinspectionsforrem(Request $request)
    {
    $useractivities =  New UserActivities();
        $useractivities->activity = "Re Work By Manager Incident Inspection";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $Incidentid = $request->input('incidentId'); // Get the incident ID from the request
        // $remarks = $request->input('remarks'); // Get the remarks from the request
        $currentDate = Carbon::now();
        $incidents = Incident::findOrFail($Incidentid);
        $incidents->status = "Re Work";
        $incidents->vehicle_status = "Re Work";
        $incidents->vehicle_status = "Re Work";
        $incidents->save();
        return response()->json(['message' => 'Re Work Update successfully']);
    }
    }
