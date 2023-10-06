<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Vehicles;
use App\Models\VehicleExtraItems;
use App\Models\VehicleApprovalRequests;
use App\Models\Varaint;
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
use App\Models\MasterModelLines;

class IncidentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $status = $request->input('status');
            $searchValue = $request->input('search.value');
            info($status);
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
                ->where('incident.status', 'approved');
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
    info($incidents->status);
    $incidents->save();
    return response()->json(['message' => 'Links saved successfully']);
}
public function showre($id)
{
    $Incident = Incident::findOrFail($id);
    $inspection = Inspection::findOrFail($Incident->inspection_id);
    $vehicle = Vehicles::findOrFail($Incident->vehicle_id);
    $variant = Varaint::find($vehicle->varaints_id);
    $brand = Brand::find($variant->brands_id);
    $model_line = MasterModelLines::find($variant->master_model_lines_id);
    $intColor = ColorCode::find($vehicle->int_colour);
    $extColor = ColorCode::find($vehicle->ex_colour);
    return view('inspection.incidentinspection', compact('vehicle', 'brand', 'intColor', 'extColor', 'variant', 'model_line', 'Incident', 'inspection'));
}
public function reinspectionsforapp(Request $request)
{
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
    $incidents = $request->input('incidentId');
    $incidentsapprove = Incident::findOrFail($incidents);
    $incidentsapprove->status = "Repaired Completed";
    $incidentsapprove->save();
    return response()->json(['message' => 'Approved Repaired Inspection']);
}
public function updatevehicledetails(Request $request)
    {
        $selectedVIN = $request->input('vin');
        $vehicle = Vehicles::where('vin', $selectedVIN)->first(); // Use first() to fetch a single result
        
        if ($vehicle) {
            $variant = Varaint::find($vehicle->varaints_id);
            $interiorColor = ColorCode::find($vehicle->int_colour);
            $exteriorColor = ColorCode::find($vehicle->ex_colour);
            $brand = Brand::find($variant->brand_id);
            $modelLine = MasterModelLines::find($variant->master_model_lines_id);
            $interiorColorName = $interiorColor ? $interiorColor->pluck('name') : null;
            $exteriorColorName = $exteriorColor ? $exteriorColor->pluck('name') : null;
            $brandName = $brand ? $brand->pluck('brand_name') : null;
            $modelLineName = $modelLine ? $modelLine->pluck('model_line') : null;
            info($modelLineName);
            $vehicleDetails = [
                'brand' => $brandName,
                'modelLine' => $modelLineName,
                'interiorColor' => $interiorColorName,
                'exteriorColor' => $exteriorColorName,
                'variant' => $variant,
                'vehicle' => $vehicle,
            ];
            return response()->json($vehicleDetails);
        } else {
            return response()->json(['error' => 'Vehicle not found']);
        }        
    }
}
