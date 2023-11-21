<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserActivities;
use Yajra\DataTables\DataTables;
use Monarobase\CountryList\CountryListFacade;
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
use App\Models\ColorCode;
use App\Models\Incident;
use App\Models\RoutineInspection;
use App\Models\Inspection;
use Illuminate\Support\Facades\File;
use App\Models\Vehicleslog;
use App\Models\VariantRequest;
use App\Models\IncidentWork;
use App\Models\MasterModelLines;
use App\Models\VariantRequestItems;
use App\Models\Pdi;
use App\Models\ModelSpecification;
use App\Models\ModelSpecificationOption;
use App\Models\VariantItems;
use App\Models\Variantlog;

class InspectionController extends Controller
{
    public function index(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Open Inspection";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        if ($request->ajax()) {
            $status = $request->input('status');
            $searchValue = $request->input('search.value');
            info($status);
            if($status === "Pending")
            {
            $data = Vehicles::select( [
                    'vehicles.id',
                    'warehouse.name as location',
                     DB::raw("DATE_FORMAT(purchasing_order.po_date, '%d-%b-%Y') as po_date"),
                    'vehicles.ppmmyyy',
                    'vehicles.vin',
                    'brands.brand_name',
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
                    'grn.grn_number',
                    DB::raw("DATE_FORMAT(grn.date, '%d-%b-%Y') as date"),
                ])
                ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
                ->leftJoin('warehouse', 'vehicles.latest_location', '=', 'warehouse.id')
                ->leftJoin('grn', 'vehicles.grn_id', '=', 'grn.id')
                ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
                ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
                ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
                ->leftJoin('inspection', 'vehicles.id', '=', 'inspection.vehicle_id')
                ->whereNull('inspection.id')
                ->whereNull('vehicles.inspection_date')
                ->whereNull('vehicles.inspection_date')
                ->whereNull('vehicles.so_id')
                ->whereNull('vehicles.gdn_id')
                ->whereNotNull('vehicles.grn_id');
                $data = $data->groupBy('vehicles.id');
            }
            else if($status === "Incoming")
            {
            $data = Vehicles::select( [
                    'vehicles.id',
                     DB::raw("DATE_FORMAT(purchasing_order.po_date, '%d-%b-%Y') as po_date"),
                    'vehicles.ppmmyyy',
                    'vehicles.vin',
                    'brands.brand_name',
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
                ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
                ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
                ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
                ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
                ->whereNull('vehicles.grn_id');
                $data = $data->groupBy('vehicles.id');
            }
            else if($status === "stock")
            {
                $data = Vehicles::select( [
                    'vehicles.id',
                    'vehicles.inspection_date',
                    'vehicles.grn_remark',
                    'warehouse.name as location',
                     DB::raw("DATE_FORMAT(purchasing_order.po_date, '%d-%b-%Y') as po_date"),
                     DB::raw("DATE_FORMAT(inspection.processing_date, '%d-%b-%Y') as processing_date"),
                    'inspection.process_remarks',
                    'vehicles.ppmmyyy',
                    'vehicles.vin',
                    'brands.brand_name',
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
                    'grn.grn_number',
                    DB::raw("DATE_FORMAT(grn.date, '%d-%b-%Y') as date"),
                ])
                ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
                ->leftJoin('warehouse', 'vehicles.latest_location', '=', 'warehouse.id')
                ->leftJoin('grn', 'vehicles.grn_id', '=', 'grn.id')
                ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
                ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
                ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
                ->leftJoin('inspection', function ($join) {
                    $join->on('vehicles.id', '=', 'inspection.vehicle_id')
                        ->whereRaw('inspection.processing_date = (SELECT MAX(processing_date) FROM inspection WHERE vehicle_id = vehicles.id)');
                })
                ->whereNotNull('vehicles.inspection_date')
                ->whereNull('vehicles.gdn_id')
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('inspection')
                        ->where('inspection.vehicle_id', '=', DB::raw('vehicles.id'))
                        ->where('inspection.status', '=', 'Pending');
                })
                ->whereNull('vehicles.so_id');
                $data = $data->groupBy('vehicles.id');
            }
            else if($status === "Pending PDI")
            {
                $data = Vehicles::select( [
                    'vehicles.id',
                    'vehicles.inspection_date',
                    'vehicles.grn_remark',
                    'warehouse.name as location',
                     DB::raw("DATE_FORMAT(purchasing_order.po_date, '%d-%b-%Y') as po_date"),
                    'vehicles.ppmmyyy',
                    'vehicles.vin',
                    'brands.brand_name',
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
                    'grn.grn_number',
                    'so.so_number',
                    DB::raw("DATE_FORMAT(grn.date, '%d-%b-%Y') as date"),
                    DB::raw("DATE_FORMAT(so.so_date, '%d-%b-%Y') as so_date"),
                ])
                ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
                ->leftJoin('warehouse', 'vehicles.latest_location', '=', 'warehouse.id')
                ->leftJoin('grn', 'vehicles.grn_id', '=', 'grn.id')
                ->leftJoin('so', 'vehicles.so_id', '=', 'so.id')
                ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
                ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
                ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
                ->whereNotNull('vehicles.inspection_date')
                ->whereNotNull('vehicles.so_id')
                ->whereNull('vehicles.gdn_id')
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('inspection')
                        ->whereRaw('inspection.vehicle_id = vehicles.id')
                        ->where('inspection.stage', '=', 'PDI');
                });
                $data = $data->groupBy('vehicles.id');
            }
            elseif($status === "Pending Re Inspection")
            {
            $data = Vehicles::select( [
                    'inspection.id',
                    'warehouse.name as location',
                    'vehicles.ppmmyyy',
                    DB::raw("DATE_FORMAT(inspection.processing_date, '%d-%b-%Y') as processing_date"),
                    'inspection.process_remarks',
                    'inspection.remark',
                    DB::raw("DATE_FORMAT(inspection.created_at, '%d-%b-%Y') as created_ats"),
                    'inspection.stage',
                    'vehicles.vin',
                    DB::raw("DATE_FORMAT(so.so_date, '%d-%b-%Y') as so_date"),
                    'so.so_number',
                    'brands.brand_name',
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
                    'grn.grn_number',
                ])
                ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
                ->leftJoin('warehouse', 'vehicles.latest_location', '=', 'warehouse.id')
                ->leftJoin('grn', 'vehicles.grn_id', '=', 'grn.id')
                ->leftJoin('so', 'vehicles.so_id', '=', 'so.id')
                ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
                ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
                ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
                ->leftJoin('inspection', 'vehicles.id', '=', 'inspection.vehicle_id')
                ->where('inspection.status', 'reinspection');
                $data = $data->groupBy('vehicles.id');
            }
                return DataTables::of($data)
                ->toJson();
        }
        return view('inspection.index');
    }
    public function show($id) 
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Open Pending Inspection for inspect";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $vehicle = Vehicles::find($id);
    if (!$vehicle) {
        abort(404); 
    }
    $brands = Brand::all();
    $countries = CountryListFacade::getList('en');
    $int_colours = ColorCode::where('belong_to', 'int')->get();
    $ext_colours = ColorCode::where('belong_to', 'ex')->get();
    $masterModelLines = MasterModelLines::all();
    return view('inspection.vehicleshow', compact('masterModelLines','countries','brands','ext_colours','int_colours', 'vehicle'));
    }
    public function update(Request $request, $id)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Update the Pending Inspection Submit for Approval";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $vehicle = Vehicles::find($id);
        $inspections = new Inspection();
        $inspections->vehicle_id = $id;
        $inspections->remark = $request->input('remarks');
        $inspections->stage = 'GRN';
        $inspections->created_by = auth()->user()->id;
        $inspections->status = "Pending";
        $inspections->save();
        $inspections_id = $inspections->id;
        $newfeatures = New VehicleApprovalRequests();
        $newfeatures->new_value = $request->input('extra_features');
        $newfeatures->inspection_id = $inspections_id;
        $newfeatures->field = "extra_features";
        $newfeatures->updated_by = auth()->user()->id;
        $newfeatures->vehicle_id = $id;
        $newfeatures->save();
        $selectedSpecifications = json_decode(request('selected_specifications'), true);
        ksort($selectedSpecifications);
        $variant_request = new VariantRequest();
        $variant_request->brands_id = $request->input('brands_id');
        $variant_request->master_model_lines_id = $request->input('master_model_lines_id');
        $variant_request->steering = $request->input('steering');
        $variant_request->fuel_type = $request->input('fuel_type');
        $variant_request->engine = $request->input('engine');
        $variant_request->upholestry = $request->input('upholestry');
        $variant_request->coo = $request->input('coo');
        $variant_request->drive_train = $request->input('drive_train');
        $variant_request->gearbox = $request->input('gearbox');
        $variant_request->my = $request->input('my');
        $variant_request->inspection_id = $inspections_id;
        $variant_request->int_colour = $request->input('int_colour');
        $variant_request->ex_colour = $request->input('ex_colour');
        $variant_request->save(); 
        $variant_requestId = $variant_request->id;
        foreach ($selectedSpecifications as $specificationData) {
        $specification = new VariantRequestItems();
        $specification->variant_request_id = $variant_requestId;
        $specification->model_specification_id = $specificationData['specification_id'];
        $specification->model_specification_options_id = $specificationData['value'];
        $specification->save();
        }
            $extraItems = [
                'sparewheel',
                'jack',
                'wheel',
                'firstaid',
                'floor_mat',
                'service_book',
                'keys',
                'wheelrim',
                'fire_extinguisher',
                'sd_card',
                'ac_system',
                'dash_board',
            ];
            foreach ($extraItems as $itemName) {
                if ($request->has($itemName)) {
                    $itemValue = $request->input($itemName);
                    $quantityFieldName = $itemName . '_qty';
                    $quantityValue = $request->input($quantityFieldName);
                    VehicleExtraItems::create([
                        'item_name' => $itemName,
                        'qty' => $quantityValue, // Store the quantity
                        'vehicle_id' => $vehicle->id,
                    ]);
                }
            }
        $isIncidentChecked = $request->has('enableInputsincludent');
        if ($isIncidentChecked) {
            $canvasImageDataURL = $request->input('canvas_image');
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
                'vehicle_id' => $id,
                'type' => $request->input('incidenttype'),
                'narration' => $request->input('narration'),
                'detail' => $request->input('damageDetails'),
                'driven_by' => $request->input('drivenBy'),
                'responsivity' => $request->input('responsibility'),
                'inspection_id' => $inspections->id,
                'file_path' => $filename,
                'created_by' => Auth::id(),
                'status' => "Pending",
                'reason' => implode(', ', $selectedReasons),
            ];
            Incident::create($incidentData);
        }
        return redirect()->route('inspection.index')->with('success', 'Vehicle Inspection successfully Done.');
    }
    public function reshow($id)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Re-Inspection the Vehicles";
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
    $intColor = ColorCode::find($vehicle->int_colour);
    $intColorall = ColorCode::where('belong_to', 'int')->get();
    $extColor = ColorCode::find($vehicle->ex_colour);
    $extColorall = ColorCode::where('belong_to', 'ex')->get();
    $variant_request = VariantRequest::where('inspection_id', $id)->first();
    $variantRequestItems = VariantRequestItems::where('variant_request_id', $variant_request->id)->get();
    $selectedData = [];
    
    // Collect selected specifications and options
    foreach ($variantRequestItems as $item) {
        $modelSpecification = ModelSpecification::find($item->model_specification_id);
        $modelSpecificationOption = ModelSpecificationOption::find($item->model_specification_options_id);
        if ($modelSpecification && $modelSpecificationOption) {
            $selectedData[] = [
                'specification_id' => $modelSpecification->id,
                'selected_option_id' => $modelSpecificationOption->id,
            ];
        }
    }
    
    // Collect all specifications and options for $model_line_id
    $specifications = ModelSpecification::where('master_model_lines_id', $variant_request->master_model_lines_id)->get();
    $data = [];
    
    foreach ($specifications as $specification) {
        $options = ModelSpecificationOption::where('model_specification_id', $specification->id)->get();
        $selectedOptionId = null;
    
        // Check if the specification is selected
        foreach ($selectedData as $selected) {
            if ($selected['specification_id'] == $specification->id) {
                $selectedOptionId = $selected['selected_option_id'];
                break;
            }
        }
    
        // Add specification and options to $data
        $data[] = [
            'specification' => $specification,
            'options' => $options,
            'selected_option_id' => $selectedOptionId,
        ];
    }
    
    // Now, $data contains all specifications and options with the selected_option_id for those that are selected.    
    $brands = Brand::find($variant_request->brands_id);
    $modal = MasterModelLines::find($variant_request->master_model_lines_id);
    $intColorr = ColorCode::find($variant_request->int_colour);
    $extColorr = ColorCode::find($variant_request->ex_colour);
    $extraItems = DB::table('vehicles_extra_items')
        ->where('vehicle_id', $inspection->vehicle_id)
        ->get(['item_name', 'qty']);
    return view('inspection.reinspection', compact('extColorall','intColorall','intColorr','extColorr','modal','model_lines','data','allBrands','brands','variant_request','Incidentpicturelink','modificationpicturelink','PDIpicturelink', 'secgdnpicturelink', 'gdnpicturelink', 'secgrnpicturelink', 'grnpicturelink', 'extraItems','inspection', 'vehicle', 'variant', 'brand', 'model_line', 'intColor', 'extColor','Incident', 'extra_featuresvalue'));
     }
    public function reupdate(Request $request, $id)
    {
        $this->validate($request, [
            'process_remarks' => 'required|string',
        ]);
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
        $inspection = Inspection::findOrFail($id);
        $inspection->reinspection_remarks = $request->input('process_remarks');
        $inspection->status = "Reinspectionapproval";
        $inspection->reinspection_date = $currentDateTime->toDateString();
        $inspection->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
        $vehicleslog = new Vehicleslog();
        $vehicleslog->time = $currentDateTime->toTimeString();
        $vehicleslog->date = $currentDateTime->toDateString();
        $vehicleslog->status = 'Update Request QC';
        $vehicleslog->vehicles_id = $inspection->vehicle_id;
        $vehicleslog->field = 'Re-Inspection Submit For Approval';
        $vehicleslog->old_value = $inspection->remark;
        $vehicleslog->new_value = $inspection->reinspection_remarks;
        $vehicleslog->created_by = auth()->user()->id;
        $vehicleslog->save();
        $isIncidentChecked = $request->has('enableInputsincludent');
        if ($isIncidentChecked) {
            $canvasImageDataURL = $request->input('canvas_image');
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
            $incidentdata = New Incident();
            $incidentdata->vehicle_id = $inspection->vehicle_id;
            $incidentdata->type = $request->input('incidenttype');
            $incidentdata->narration = $request->input('narration');
            $incidentdata->detail = $request->input('damageDetails');
            $incidentdata->driven_by = $request->input('drivenBy');
            $incidentdata->responsivity = $request->input('responsibility');
            $incidentdata->inspection_id = $inspection->id;
            $incidentdata->file_path = $filename;
            $incidentdata->created_by = Auth::id();
            $incidentdata->status = "Pending";
            $incidentdata->reason = implode(', ', $selectedReasons);
            $incidentdata->save();
        }
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
        $variant_request = VariantRequest::where('inspection_id', $id)->first();
        if ($variant_request) {
            $variant_request->steering = $request->input('steering');
            $variant_request->fuel_type = $request->input('fuel_type');
            $variant_request->engine = $request->input('engine');
            $variant_request->upholestry = $request->input('upholestry');
            $variant_request->gearbox = $request->input('gearbox');
            $variant_request->my = $request->input('my');
            $variant_request->inspection_id = $id;
            $variant_request->int_colour = $request->input('int_colour');
            $variant_request->ex_colour = $request->input('ex_colour');
            $variant_request->save();
        
            $variant_requestId = $variant_request->id;
        
            foreach ($selectedSpecifications as $specificationData) {
                VariantRequestItems::updateOrCreate(
                    [
                        'variant_request_id' => $variant_requestId,
                        'model_specification_id' => $specificationData['specification_id'],
                    ],
                    [
                        'model_specification_options_id' => $specificationData['value'],
                    ]
                );
            }
        }       
        return redirect()->route('inspection.index')->with('success', 'Vehicle Re-Inspection successfully Submit For Approval');
    }
    public function instock($id) 
    {
    $vehicle = Vehicles::find($id);
    if (!$vehicle) {
        abort(404); 
    }
    $variant = Varaint::find($vehicle->varaints_id);
    $brand = Brand::find($variant->brands_id);
    $model_line = MasterModelLines::find($variant->master_model_lines_id);
    $intColor = ColorCode::find($vehicle->int_colour);
    $extColor = ColorCode::find($vehicle->ex_colour);
    return view('inspection.stockvehicleshow', compact('vehicle', 'brand', 'intColor', 'extColor', 'variant', 'model_line'));
    }
    public function routineUpdate(Request $request, $vehicle)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Submit the routine inspection for approval";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $inspection = new Inspection();
        $inspection->status = "Pending";
        $inspection->vehicle_id = $vehicle;
        $inspection->remark = $request->input('remarks');
        $inspection->stage = "Routine";
        $inspection->created_by = Auth::id();
        $inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Battery Inspection";
        $routine_inspection->condition = $request->input('condition_battery');
        $routine_inspection->remarks = $request->input('remarks_battery');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Tyre Pressure Inspection";
        $routine_inspection->condition= $request->input('condition_tyre_pressure');
        $routine_inspection->remarks = $request->input('remarks_tyre_pressure');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Under Hood Inspection";
        $routine_inspection->condition = $request->input('condition_under_hood');
        $routine_inspection->remarks = $request->input('remarks_under_hood');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Starting & Warming";
        $routine_inspection->condition = $request->input('condition_starting');
        $routine_inspection->remarks = $request->input('remarks_starting');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "A/C Operation (Cool & Hot)";
        $routine_inspection->condition = $request->input('condition_ac');
        $routine_inspection->remarks = $request->input('remarks_ac');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Exterior Inspection & Protective Cover Condition";
        $routine_inspection->condition = $request->input('condition_exterior_inspection');
        $routine_inspection->remarks = $request->input('remarks_exterior_inspection');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Interior Inspection & Protective Cover Condition";
        $routine_inspection->condition = $request->input('condition_interior_inspection');
        $routine_inspection->remarks = $request->input('remarks_interior_inspection');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Move the Vehicle";
        $routine_inspection->condition = $request->input('condition_vehicle_move');
        $routine_inspection->remarks = $request->input('remarks_vehicle_move');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Paint (Overall)";
        $routine_inspection->condition = $request->input('condition_paint');
        $routine_inspection->remarks = $request->input('remarks_paint');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Bumper Front";
        $routine_inspection->condition = $request->input('condition_bumper');
        $routine_inspection->remarks = $request->input('remarks_bumper');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Grill";
        $routine_inspection->condition = $request->input('condition_grill');
        $routine_inspection->remarks = $request->input('remarks_grill');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Light Front";
        $routine_inspection->condition = $request->input('condition_light_front');
        $routine_inspection->remarks = $request->input('remarks_light_front');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Hood";
        $routine_inspection->condition = $request->input('condition_hood');
        $routine_inspection->remarks = $request->input('remarks_hood');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Windshield";
        $routine_inspection->condition = $request->input('condition_windshield');
        $routine_inspection->remarks = $request->input('remarks_windshield');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Wipers";
        $routine_inspection->condition = $request->input('condition_wipers');
        $routine_inspection->remarks = $request->input('remarks_wipers');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Fender Front Left";
        $routine_inspection->condition = $request->input('condition_fender_front_left');
        $routine_inspection->remarks = $request->input('remarks_fender_front_left');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Tire / Rim Front Left";
        $routine_inspection->condition = $request->input('condition_tire_rim_front_left');
        $routine_inspection->remarks = $request->input('remarks_tire_rim_front_left');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Side Step Left (if Applicable)";
        $routine_inspection->condition = $request->input('condition_side_step_left');
        $routine_inspection->remarks = $request->input('remarks_side_step_left');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Door Front Left (Check Handles)";
        $routine_inspection->condition = $request->input('condition_door_front_left');
        $routine_inspection->remarks = $request->input('remarks_door_front_left');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Mirror Door Driver";
        $routine_inspection->condition = $request->input('condition_mirror_door_driver');
        $routine_inspection->remarks = $request->input('remarks_mirror_door_driver');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Roof / A-pillars Left";
        $routine_inspection->condition = $request->input('condition_roof');
        $routine_inspection->remarks = $request->input('remarks_roof');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Door Rear Left (Check Handles)";
        $routine_inspection->condition = $request->input('condition_door_rear_left');
        $routine_inspection->remarks = $request->input('remarks_door_rear_left');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Fender Rear Left";
        $routine_inspection->condition = $request->input('condition_fender_rear_left');
        $routine_inspection->remarks = $request->input('remarks_fender_rear_left');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Bed / Box";
        $routine_inspection->condition = $request->input('condition_bed_box');
        $routine_inspection->remarks = $request->input('remarks_bed_box');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Tailgate (Check Handles)";
        $routine_inspection->condition = $request->input('condition_tailgate');
        $routine_inspection->remarks = $request->input('remarks_tailgate');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Lights Rear";
        $routine_inspection->condition = $request->input('condition_light_rear');
        $routine_inspection->remarks = $request->input('remarks_light_rear');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Bumper Rear";
        $routine_inspection->condition = $request->input('condition_bumper_rear');
        $routine_inspection->remarks = $request->input('remarks_bumper_rear');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Underbody Parts (Muffler / Tank)";
        $routine_inspection->condition = $request->input('condition_underbody_parts');
        $routine_inspection->remarks = $request->input('remarks_underbody_parts');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Fender Rear Right";
        $routine_inspection->condition = $request->input('condition_fender_rear_right');
        $routine_inspection->remarks = $request->input('remarks_fender_rear_right');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Tire / Rim Rear Left";
        $routine_inspection->condition = $request->input('condition_tire_rim_rear_left');
        $routine_inspection->remarks = $request->input('remarks_tire_rim_rear_left');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Door Rear Right (Check Handles)";
        $routine_inspection->condition = $request->input('condition_door_rear_right');
        $routine_inspection->remarks = $request->input('remarks_door_rear_right');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Roof / A-pillars Right";
        $routine_inspection->condition = $request->input('condition_pillar_right');
        $routine_inspection->remarks = $request->input('remarks_pillar_right');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Mirror Door Passenger";
        $routine_inspection->condition = $request->input('condition_mirror_door_passenger');
        $routine_inspection->remarks = $request->input('remarks_mirror_door_passenger');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Door Front Right(Check Handles)";
        $routine_inspection->condition = $request->input('condition_door_front_right');
        $routine_inspection->remarks = $request->input('remarks_door_front_right');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Side Steps Right (If applicable)";
        $routine_inspection->condition = $request->input('condition_side_steps_right');
        $routine_inspection->remarks = $request->input('remarks_side_steps_right');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Fender Front Right";
        $routine_inspection->condition = $request->input('condition_fender_front_right');
        $routine_inspection->remarks = $request->input('remarks_fender_front_right');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Tire /  Rim Front Right";
        $routine_inspection->condition = $request->input('condition_tire_rim_front_right');
        $routine_inspection->remarks = $request->input('remarks_tire_rim_front_right');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $routine_inspection = New RoutineInspection();
        $routine_inspection->check_items = "Radio Antenna";
        $routine_inspection->condition = $request->input('condition_radio_antenna');
        $routine_inspection->remarks = $request->input('remarks_radio_antenna');
        $routine_inspection->inspection_id = $inspection->id;
        $routine_inspection->vehicle_id = $vehicle;
        $routine_inspection->save();
        $isIncidentChecked = $request->has('enableInputsincludent');
        if ($isIncidentChecked) {
            $canvasImageDataURL = $request->input('canvas_image');
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
                'vehicle_id' => $vehicle,
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
        }
        return redirect()->route('inspection.index')->with('success', 'Vehicle Routine Inspection Submit For Approval');
    }
    public function getVehicleExtraItems($vehicle_id) {
        $itemsWithQuantities = VehicleExtraItems::where('vehicle_id', $vehicle_id)
            ->select('item_name', 'qty')
            ->get();
            $vehicleDetails = DB::table('vehicles')
            ->join('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
            ->join('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
            ->where('vehicles.id', $vehicle_id)
            ->select('vehicles.vin', 'vehicles.varaints_id', 'master_model_lines.model_line')
            ->first();
        return response()->json([
            'itemsWithQuantities' => $itemsWithQuantities,
            'vehicleDetails' => $vehicleDetails
        ]);
    }
    public function pdiinspection(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Submit the PDI Inspection";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
       $vehicle_id =  $request->input('vehicle_id');
       $inspection = New Inspection();
       $inspection->status = "Pending";
       $inspection->vehicle_id = $vehicle_id;
       $inspection->created_by = Auth::id();
       $inspection->remark = $request->input('pdi_remarks');
       $inspection->stage = "PDI";
       $inspection->save();
       $pdi = New Pdi();
       $pdi->checking_item = "Spare Wheel";
       $pdi->reciving = $request->input('sparewheelr');
       $pdi->vehicle_id = $vehicle_id;
       $pdi->inspection_id = $inspection->id;
       $pdi->status = $request->input('sparewheel');
       $pdi->save();
       $pdi = New Pdi();
       $pdi->checking_item = "Jack";
       $pdi->reciving = $request->input('jackr');
       $pdi->vehicle_id = $vehicle_id;
       $pdi->inspection_id = $inspection->id;
       $pdi->status = $request->input('jack');
       $pdi->save();
       $pdi = New Pdi();
       $pdi->checking_item = "FIRST AID KIT";
       $pdi->reciving = $request->input('firstaidr');
       $pdi->vehicle_id = $vehicle_id;
       $pdi->inspection_id = $inspection->id;
       $pdi->status = $request->input('firstaid');
       $pdi->save();
       $pdi = New Pdi();
       $pdi->checking_item = "FLOOR MAT";
       $pdi->reciving = $request->input('floor_matr');
       $pdi->vehicle_id = $vehicle_id;
       $pdi->inspection_id = $inspection->id;
       $pdi->status = $request->input('floor_mat');
       $pdi->save();
       $pdi = New Pdi();
       $pdi->checking_item = "SERVICE BOOK & MANUAL";
       $pdi->reciving = $request->input('service_bookr');
       $pdi->vehicle_id = $vehicle_id;
       $pdi->inspection_id = $inspection->id;
       $pdi->status = $request->input('service_book');
       $pdi->save();
       $pdi = New Pdi();
       $pdi->checking_item = "KEYS / QTY";
       $pdi->reciving = $request->input('keysr');
       $pdi->vehicle_id = $vehicle_id;
       $pdi->inspection_id = $inspection->id;
       $pdi->status = $request->input('keys');
       $pdi->save();
       $pdi = New Pdi();
       $pdi->checking_item = "EXTERIOR PAINT AND BODY";
       $pdi->vehicle_id = $vehicle_id;
       $pdi->inspection_id = $inspection->id;
       $pdi->status = $request->input('exteriorpaint');
       $pdi->save();
       $pdi = New Pdi();
       $pdi->checking_item = "INTERIOR & UPHOLSTERY";
       $pdi->vehicle_id = $vehicle_id;
       $pdi->inspection_id = $inspection->id;
       $pdi->status = $request->input('interior');
       $pdi->save();
       $pdi = New Pdi();
       $pdi->checking_item = "WHEEL RIM / TYRES";
       $pdi->reciving = $request->input('wheelrimr');
       $pdi->vehicle_id = $vehicle_id;
       $pdi->inspection_id = $inspection->id;
       $pdi->status = $request->input('wheelrim');
       $pdi->save();
       $pdi = New Pdi();
       $pdi->checking_item = "FIRE EXTINGUISHER";
       $pdi->reciving = $request->input('fire_extinguisherr');
       $pdi->vehicle_id = $vehicle_id;
       $pdi->inspection_id = $inspection->id;
       $pdi->status = $request->input('fire_extinguisher');
       $pdi->save();
       $pdi = New Pdi();
       $pdi->checking_item = "SD Card / Remote / H Phones";
       $pdi->reciving = $request->input('sd_cardr');
       $pdi->vehicle_id = $vehicle_id;
       $pdi->inspection_id = $inspection->id;
       $pdi->status = $request->input('sd_card');
       $pdi->save();
       $pdi = New Pdi();
       $pdi->checking_item = "A/C System";
       $pdi->reciving = $request->input('ac_systemr');
       $pdi->vehicle_id = $vehicle_id;
       $pdi->inspection_id = $inspection->id;
       $pdi->status = $request->input('ac_system');
       $pdi->save();
       $pdi = New Pdi();
       $pdi->checking_item = "DASHBOARD / T SCREEN / LCD";
       $pdi->reciving = $request->input('dash_boardr');
       $pdi->vehicle_id = $vehicle_id;
       $pdi->inspection_id = $inspection->id;
       $pdi->status = $request->input('dash_board');
       $pdi->save();
       $pdi = New Pdi();
       $pdi->checking_item = "CAMERA";
       $pdi->vehicle_id = $vehicle_id;
       $pdi->inspection_id = $inspection->id;
       $pdi->status = $request->input('camera');
       $pdi->save();
       $pdi = New Pdi();
       $pdi->checking_item = "STICKER REMOVAL";
       $pdi->vehicle_id = $vehicle_id;
       $pdi->inspection_id = $inspection->id;
       $pdi->status = $request->input('sticker');
       $pdi->save();
       $pdi = New Pdi();
       $pdi->checking_item = "PACKING BOX";
       $pdi->vehicle_id = $vehicle_id;
       $pdi->inspection_id = $inspection->id;
       $pdi->status = $request->input('packingbox');
       $pdi->save();
       $pdi = New Pdi();
       $pdi->checking_item = "PHOTOS 6 Nos";
       $pdi->vehicle_id = $vehicle_id;
       $pdi->inspection_id = $inspection->id;
       $pdi->status = $request->input('photo');
       $pdi->save();
       $pdi = New Pdi();
       $pdi->checking_item = "FUEL / BATTERY";
       $pdi->vehicle_id = $vehicle_id;
       $pdi->inspection_id = $inspection->id;
       $pdi->status = $request->input('fuel');
       $pdi->save();
       $pdi = New Pdi();
       $pdi->checking_item = "UNDER HOOD INSPECTION";
       $pdi->vehicle_id = $vehicle_id;
       $pdi->inspection_id = $inspection->id;
       $pdi->status = $request->input('under_hood');
       $pdi->save();
       $pdi = New Pdi();
       $pdi->checking_item = "OILS AND FLUIDS LEVELS INSPECTION";
       $pdi->vehicle_id = $vehicle_id;
       $pdi->inspection_id = $inspection->id;
       $pdi->status = $request->input('oil');
       $pdi->save();
       $pdi = New Pdi();
       $pdi->checking_item = "ALL FUNCTIONS OPERATIONS AS PER PO";
       $pdi->vehicle_id = $vehicle_id;
       $pdi->inspection_id = $inspection->id;
       $pdi->status = $request->input('funcationpo');
       $pdi->save();
       $pdi = New Pdi();
       $pdi->checking_item = "CLEANING AND WASHING";
       $pdi->vehicle_id = $vehicle_id;
       $pdi->inspection_id = $inspection->id;
       $pdi->status = $request->input('washing');
       $pdi->save();
       $pdi = New Pdi();
       $pdi->checking_item = "OTHER REMARKS";
       $pdi->vehicle_id = $vehicle_id;
       $pdi->inspection_id = $inspection->id;
       $pdi->status = $request->input('otherremarks');
       $pdi->save();
       $isIncidentChecked = $request->has('enableInputsincludent');
       if ($isIncidentChecked) {
           $canvasImageDataURL = $request->input('canvas_image');
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
               'vehicle_id' => $vehicle_id,
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
       }
       return redirect()->route('inspection.index')->with('success', 'Vehicle PDI successfully Submit For Approval');
    }
    public function pdiinspectionf($id) 
    {
        (new UserActivityController)->createActivity('View Edit PDI Report');
    $itemsWithQuantities = VehicleExtraItems::where('vehicle_id', $id)
            ->select('item_name', 'qty')
            ->get();
            $vehicleDetails = DB::table('vehicles')
            ->join('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
            ->join('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
            ->join('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
            ->join('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
            ->join('brands', 'varaints.brands_id', '=', 'brands.id')
            ->where('vehicles.id', $id)
            ->select('vehicles.id', 'vehicles.vin', 'vehicles.varaints_id', 'master_model_lines.model_line', 'int_color.name as int_colour_name','ex_color.name as ex_colour_name', 'brands.brand_name')
            ->first();
    return view('inspection.pdivehicleview', compact('itemsWithQuantities', 'vehicleDetails'));
    }
}