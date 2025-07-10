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
use Illuminate\Support\Facades\Log;

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
                    // 'movement_grns.grn_number',
                    DB::raw("DATE_FORMAT(movements_reference.date, '%d-%b-%Y') as date"),
                ])
                ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
                ->leftJoin('warehouse', 'vehicles.latest_location', '=', 'warehouse.id')
                ->leftJoin('movement_grns', 'vehicles.movement_grn_id', '=', 'movement_grns.id')
                ->leftJoin('movements_reference', 'movement_grns.movement_reference_id', '=', 'movements_reference.id')
                ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
                ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
                ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
                ->leftJoin('inspection', 'vehicles.id', '=', 'inspection.vehicle_id')
                ->whereNull('inspection.id')
                ->whereNull('vehicles.inspection_date')
                ->whereNull('vehicles.gdn_id')
                ->whereNotNull('vehicles.movement_grn_id');
                 
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
                ->whereNull('vehicles.movement_grn_id');
                $data = $data->groupBy('vehicles.id');
            }
            else if($status === "stock")
            {
                $data = Vehicles::select( [
                    'vehicles.id',
                    'vehicles.inspection_date',
                    'vehicles.grn_remark',
                    'vehicles.inspection_status',
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
                    'movement_grns.grn_number',
                ])
                ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
                ->leftJoin('warehouse', 'vehicles.latest_location', '=', 'warehouse.id')
                ->leftJoin('movement_grns', 'vehicles.movement_grn_id', '=', 'movement_grns.id')
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
                ->where(function ($query) {
                    $query->where('inspection_status', '!=', 'Pending')
                        ->orWhereNull('inspection_status');
                })
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
                    'vehicles.inspection_status',
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
                    'movement_grns.grn_number',
                    'so.so_number',
                    DB::raw("DATE_FORMAT(so.so_date, '%d-%b-%Y') as so_date"),
                ])
                ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
                ->leftJoin('warehouse', 'vehicles.latest_location', '=', 'warehouse.id')
                ->leftJoin('movement_grns', 'vehicles.movement_grn_id', '=', 'movement_grns.id')
                ->leftJoin('so', 'vehicles.so_id', '=', 'so.id')
                ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
                ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
                ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
                ->whereNotNull('vehicles.inspection_date')
                ->whereNotNull('vehicles.so_id')
                ->whereNull('pdi_date')
                ->whereNull('vehicles.gdn_id')
                ->where(function ($query) {
                    $query->where('inspection_status', '!=', 'Pending')
                        ->orWhereNull('inspection_status');
                })
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
                    'vehicles.inspection_status',
                    DB::raw("DATE_FORMAT(inspection.processing_date, '%d-%b-%Y') as processing_date"),
                    'inspection.process_remarks',
                    DB::raw("CONVERT(REPLACE(REPLACE(`inspection`.`remark`, '<p>', ''), '</p>', ''), CHAR) as inspectionremark"),
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
                    'movement_grns.grn_number',
                ])
                ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
                ->leftJoin('warehouse', 'vehicles.latest_location', '=', 'warehouse.id')
                ->leftJoin('movement_grns', 'vehicles.movement_grn_id', '=', 'movement_grns.id')
                ->leftJoin('so', 'vehicles.so_id', '=', 'so.id')
                ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
                ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
                ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
                ->leftJoin('inspection', 'vehicles.id', '=', 'inspection.vehicle_id')
                ->where(function ($query) {
                    $query->where('inspection_status', '!=', 'Pending')
                        ->orWhereNull('inspection_status');
                })
                ->where('inspection.status', 'reinspection');
                $data = $data->groupBy('vehicles.id');
            }
            elseif($status === "Spec Re Inspection")
            {
            $data = Vehicles::select( [
                    'vehicles.id',
                    'warehouse.name as location',
                    'vehicles.ppmmyyy',
                    'vehicles.inspection_status',
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
                    'movement_grns.grn_number',
                ])
                ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
                ->leftJoin('warehouse', 'vehicles.latest_location', '=', 'warehouse.id')
                ->leftJoin('movement_grns', 'vehicles.movement_grn_id', '=', 'movement_grns.id')
                ->leftJoin('so', 'vehicles.so_id', '=', 'so.id')
                ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
                ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
                ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
                ->where('vehicles.inspection_status', 'Pending');
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
    $variant = Varaint::find($vehicle->varaints_id);
    $brandname = Brand::find($variant->brands_id);
    $model_line = MasterModelLines::find($variant->master_model_lines_id);
    $masterModelLines = MasterModelLines::all();
    return view('inspection.vehicleshow', compact('masterModelLines','countries','brands','ext_colours','int_colours', 'vehicle', 'brandname', 'model_line'));
    }
    public function update(Request $request, $id)
    {
        try{
    
        DB::beginTransaction();
        $useractivities =  New UserActivities();
        $useractivities->activity = "Update the Pending Inspection Submit for Approval";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $vehicle = Vehicles::find($id);
        $selectedDate = Carbon::createFromFormat('Y-m', $request->input('ppmmyyy'))->format('M-Y');
        $vehicle->ppmmyyy = $selectedDate;
        $vehicle->save();
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

        if (empty($selectedSpecifications)) {
            return redirect()->back()->with('error', 'No specifications selected.');
        }
        ksort($selectedSpecifications);
        $variant_request = new VariantRequest();
        $variant_request->brands_id = $request->input('brands_id');
        $variant_request->master_model_lines_id = $request->input('master_model_lines_id');
        $variant_request->steering = $request->input('steering');
        $variant_request->fuel_type = $request->input('fuel_type');
        $variant_request->engine = $request->input('engine');
        $variant_request->upholestry = $request->input('upholestry');
        $variant_request->drive_train = $request->input('drive_train');
        $variant_request->gearbox = $request->input('gearbox');
        $variant_request->my = $request->input('my');
        $variant_request->inspection_id = $inspections_id;
        $variant_request->int_colour = $request->input('int_colour');
        $variant_request->ex_colour = $request->input('ex_colour');
        $variant_request->save(); 
        $variant_requestId = $variant_request->id;
        foreach ($selectedSpecifications as $specificationData) {
            $existingSpecification = VariantRequestItems::where('variant_request_id', $variant_requestId)
                ->where('model_specification_id', $specificationData['specification_id'])
                ->exists();
            if (!$existingSpecification) {
                $specification = new VariantRequestItems();
                $specification->variant_request_id = $variant_requestId;
                $specification->model_specification_id = $specificationData['specification_id'];
                $specification->model_specification_options_id = $specificationData['value'];
                $specification->save();
            }
        }        
            $extraItems = [
                'packing',
                'warningtriangle',
                'wheel',
                'firstaid',
                'floor_mat',
                'service_book',
                'keys',
                'trunkcover',
                'fire_extinguisher',
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
        DB::commit();
        return redirect()->route('inspection.index')->with('success', 'Vehicle Inspection successfully Done.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Inspection Creation failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Vehicle Inspection Creation failed.');
        }
       
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
       $pdi->checking_item = "Packing Box";
       $pdi->reciving = $request->input('packingr');
       $pdi->vehicle_id = $vehicle_id;
       $pdi->inspection_id = $inspection->id;
       $pdi->status = $request->input('packing');
       $pdi->save();
       $pdi = New Pdi();
       $pdi->checking_item = "Warning Triangle";
       $pdi->reciving = $request->input('warningtriangler');
       $pdi->vehicle_id = $vehicle_id;
       $pdi->inspection_id = $inspection->id;
       $pdi->status = $request->input('warningtriangle');
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
       $pdi->checking_item = "TRUNK COVER";
       $pdi->reciving = $request->input('trunkcoverr');
       $pdi->vehicle_id = $vehicle_id;
       $pdi->inspection_id = $inspection->id;
       $pdi->status = $request->input('trunkcover');
       $pdi->save();
       $pdi = New Pdi();
       $pdi->checking_item = "FIRE EXTINGUISHER";
       $pdi->reciving = $request->input('fire_extinguisherr');
       $pdi->vehicle_id = $vehicle_id;
       $pdi->inspection_id = $inspection->id;
       $pdi->status = $request->input('fire_extinguisher');
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
            ->select('vehicles.id', 'vehicles.vin', 'vehicles.varaints_id', 'master_model_lines.model_line', 
            'int_color.name as int_colour_name','ex_color.name as ex_colour_name', 'brands.brand_name','varaints.model_detail',
            'varaints.name as variant_name', 'varaints.my','varaints.steering','varaints.steering','varaints.seat','varaints.detail',
            'varaints.fuel_type','varaints.gearbox','vehicles.ppmmyyy')
            ->first();
        $variantitems = VariantItems::with(['model_specification', 'model_specification_option'])
                        ->where('varaint_id', $vehicleDetails->varaints_id)->get();
           
    return view('inspection.pdivehicleview', compact('itemsWithQuantities', 'vehicleDetails','variantitems'));
    }
    public function reinspectionspec($id) 
{
    (new UserActivityController)->createActivity('Re Inspection The Spec Update');
    $vehicle = Vehicles::find($id);
    $int_colours = ColorCode::where('belong_to', 'int')->where('id', $vehicle->int_colour)->get();
    $ext_colours = ColorCode::where('belong_to', 'ex')->where('id', $vehicle->ex_colour)->get();
    $variant = Varaint::find($vehicle->varaints_id);
    $brandname = Brand::find($variant->brands_id);
    $model_line = MasterModelLines::find($variant->master_model_lines_id);
    $variantId = DB::table('vehicles')->where('id', $id)->value('varaints_id');
    $masterModelLinesId = DB::table('varaints')->where('id', $variantId)->value('master_model_lines_id');
    $allSpecifications = DB::table('model_specification')->where('master_model_lines_id', $masterModelLinesId)->get()->toArray();
    $existingSpecifications = DB::table('variant_items')
        ->where('varaint_id', $variantId)
        ->pluck('model_specification_id')
        ->toArray();
        $filteredSpecifications = DB::table('model_specification')
        ->where('master_model_lines_id', $masterModelLinesId)
        ->get()
        ->toArray();
        foreach ($filteredSpecifications as $key => $specification) {
            $options = DB::table('model_specification_options')
                ->where('model_specification_id', $specification->id)
                ->get()
                ->toArray();
    
            $filteredSpecifications[$key]->options = $options;
            
        }    
    return view('inspection.reinspectionspec', compact('filteredSpecifications', 'vehicle', 'int_colours', 'ext_colours', 'brandname', 'model_line'));
    }
    public function reupdatespec(Request $request, $id)
    {
        $createnew = false;
        $variant_id = $request->input('variant_id');
        $existingSpecifications = VariantItems::where('varaint_id', $request->input('variant_id'))
            ->pluck('model_specification_options_id', 'model_specification_id')
            ->toArray();
        $incomingSpecifications = [];
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'specification_') === 0) {
                $specificationId = str_replace('specification_', '', $key);
                $incomingSpecifications[$specificationId] = $value;
            }
        }
        if (count(array_diff_assoc($incomingSpecifications, $existingSpecifications)) === 0) {
        //Not Change Variant update the inspection status
        $inspection = New Inspection();
        $inspection->status = "Approved";
        $inspection->vehicle_id = $id;
        $inspection->created_by = Auth::id();
        $inspection->stage = "Re Inspection Spec";
        $inspection->save();
        $vehicle = Vehicles::find($id);
        $vehicle->inspection_status = "Approved";
        $vehicle->save();
        return redirect()->route('inspection.index')->with('success', 'Variant details updated successfully');
        } else {
            // Check if there is a variant that matches all specifications and options
            $matchingVariant = VariantItems::whereIn('model_specification_id', array_keys($incomingSpecifications))
                ->whereIn('model_specification_options_id', array_values($incomingSpecifications))
                ->havingRaw('COUNT(DISTINCT model_specification_id) = ?', [count($incomingSpecifications)])
                ->pluck('varaint_id');
                if ($matchingVariant->isNotEmpty()) { 
                    $matchingVariantId = $matchingVariant->first();
                //Change the variant of the vehicle and change the status of the vehicle
                $inspection = New Inspection();
                $inspection->status = "Approved";
                $inspection->vehicle_id = $id;
                $inspection->created_by = Auth::id();
                $inspection->stage = "Re Inspection Spec";
                $inspection->save();
                $vehicle = Vehicles::find($id);
                $vehicle->inspection_status = "Approved";
                $vehicle->varaints_id = $matchingVariantId;
                Log::info('Variant Change Detected 9. Vehicle varaints_id updated after specification match (reupdatespec)', [
                    'vehicle_id' => $vehicle->id,
                    'old_varaints_id' => $vehicle->getOriginal('varaints_id'),
                    'new_varaints_id' => $vehicle->varaints_id,
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name ?? 'N/A',
                    'source' => 'specification reinspection match',
                    'timestamp' => now()->toDateTimeString()
                ]);
                $vehicle->save();
                return redirect()->route('inspection.index')->with('success', 'Variant details updated successfully');
            } else {
                // dd("de");
                foreach ($incomingSpecifications as $specificationId => $optionId) {
                    if (!array_key_exists($specificationId, $existingSpecifications) || $existingSpecifications[$specificationId] === $optionId) {
                        //Adding more options into current variant
                        VariantItems::create([
                            'varaint_id' => $request->input('variant_id'),
                            'model_specification_id' => $specificationId,
                            'model_specification_options_id' => $optionId,
                        ]);
                    }
                    else{
                        $createnew = true;   
                    }
                }
                if($createnew){
                    $variantsdf = Varaint::find($variant_id);
                    $maxVariant = Varaint::where('brands_id', $variantsdf->brands_id)
                    ->where('master_model_lines_id', $variantsdf->master_model_lines_id)
                    ->where('fuel_type', $variantsdf->fuel_type)
                    ->where('engine', $variantsdf->engine)
                    ->where('steering', $variantsdf->steering)
                    ->orderBy('name', 'desc')
                    ->first();
                    $master_model_lines_id = $variantsdf->master_model_lines_id;
                    $steering = $variantsdf->steering;
                    if($steering == "LHD"){
                        $steeringn = "L";
                    }
                    else{
                        $steeringn = "R";
                    }
                    $engine = $variantsdf->engine;
                    $fuel_type = $variantsdf->fuel_type;
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
                        $f = "P";
                    }
                    else if($fuel_type == "MHEV") 
                    {
                        $f = "M";
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
                    $model_details= $request->input('model_detail');
    if($model_details == null){
    $steering = $variantsdf->steering;
    $master_model_lines_id = $variantsdf->master_model_lines_id;
    $model_line = MasterModelLines::where('id', $master_model_lines_id)->pluck('model_line')->first();
    $engine = $variantsdf->engine;
    $gearbox = $variantsdf->gearbox;
    $fuel_type = $variantsdf->fuel_type;
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
        $f = "P";
    }
    else if($fuel_type == "MHEV") 
    {
        $f = "M";
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
        $steering = $variantsdf->steering;
        $master_model_lines_id = $variantsdf->master_model_lines_id;
        $model_line = MasterModelLines::where('id', $master_model_lines_id)->pluck('model_line')->first();
        $engine = $variantsdf->engine;
        $gearbox = $variantsdf->gearbox;
        $coo = $variantsdf->coo;
        $my = $variantsdf->my;
        $drive_train = $variantsdf->drive_train;
        $upholestry = $variantsdf->upholestry;
        $fuel_type = $variantsdf->fuel_type;
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
            $f = "P";
        }
        else if($fuel_type == "MHEV") 
        {
            $f = "M";
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
                    $variant->brands_id = $variantsdf->brands_id;
                    $variant->master_model_lines_id = $variantsdf->master_model_lines_id;
                    $variant->steering = $variantsdf->steering;
                    $variant->fuel_type = $variantsdf->fuel_type;
                    $variant->engine = $variantsdf->engine;
                    $variant->upholestry = $variantsdf->upholestry;
                    $variant->coo = $variantsdf->coo;
                    $variant->drive_train = $variantsdf->drive_train;
                    $variant->gearbox = $variantsdf->gearbox;
                    $variant->name = $name;
                    $variant->model_detail = $model_details;
                    $variant->detail = $variant_details;
                    $variant->my = $variantsdf->my;
                    $variant->save();
                    $variantIds = $variant->id;
                    foreach ($incomingSpecifications as $specificationId => $optionId) {
                    VariantItems::create([
                        'varaint_id' => $variantIds,
                        'model_specification_id' => $specificationId,
                        'model_specification_options_id' => $optionId,
                    ]);
                }
                    $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                    $currentDateTime = Carbon::now($dubaiTimeZone);
                    $variantlog = new Variantlog();
                    $variantlog->time = $currentDateTime->toTimeString();
                    $variantlog->date = $currentDateTime->toDateString();
                    $variantlog->status = 'New Created';
                    $variantlog->variant_id = $variantIds;
                    $variantlog->created_by = auth()->user()->id;
                    $variantlog->save();
                }
                $inspection = New Inspection();
                $inspection->status = "Approved";
                $inspection->vehicle_id = $id;
                $inspection->created_by = Auth::id();
                $inspection->stage = "Re Inspection Spec";
                $inspection->save();
                $vehicle = Vehicles::find($id);
                $vehicle->inspection_status = "Approved";
                $vehicle->save();
                return redirect()->route('inspection.index')->with('success', 'Variant details updated successfully');
            }
        }
    }
}