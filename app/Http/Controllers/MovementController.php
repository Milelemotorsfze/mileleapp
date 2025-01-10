<?php

namespace App\Http\Controllers;

use App\Models\Movement;
use Illuminate\Support\Facades\Mail;
use App\Models\Vehicles;
use App\Models\Warehouse;
use App\Models\Varaint;
use App\Models\MasterModelLines;
use App\Models\Vehicleslog;
use App\Mail\GRNEmailNotification;
use App\Models\Brand;
use Illuminate\Support\Facades\Auth;
use App\Models\MovementsReference;
use App\Models\Grn;
use App\Models\So;
use App\Models\Gdn;
use App\Models\DepartmentNotifications;
use App\Models\VinChange;
use Carbon\CarbonTimeZone;
use Carbon\Carbon;
use App\Models\PurchasingOrder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Facades\DataTables; 

class MovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Builder $builder)
{
    $movementreference = MovementsReference::get();
    $vehicles = Vehicles::whereNotNull('vin')
        ->where('status', '!=', 'cancel')
        ->pluck('vin', 'varaints_id');
    $warehouses = Warehouse::select('id', 'name')->get();
    if ($request->ajax()) {
        $movementsQuery = Movement::query();
        foreach ($request->input('columns') as $column) {
            $searchValue = $column['search']['value'];
            $columnName = $column['name'];
    
            if ($columnName === 'date' && $searchValue !== null) {
                $movementsQuery->orWhereHas('Movementrefernce', function ($query) use ($searchValue) {
                    $query->where('date', 'like', '%' . $searchValue . '%');
                });
            } elseif ($columnName === 'model_detail' && $searchValue !== null) {
                $movementsQuery->orWhereHas('vehicle.variant', function ($query) use ($searchValue) {
                    $query->where('model_detail', 'like', '%' . $searchValue . '%');
                });
            } elseif ($columnName === 'from_name' && $searchValue !== null) {
                $movementsQuery->orWhereHas('fromWarehouse', function ($query) use ($searchValue) {
                    $query->where('name', 'like', '%' . $searchValue . '%');
                });
            } elseif ($columnName === 'to_name' && $searchValue !== null) {
                $movementsQuery->orWhereHas('toWarehouse', function ($query) use ($searchValue) {
                    $query->where('name', 'like', '%' . $searchValue . '%');
                });
            } elseif ($columnName === 'so_number' && $searchValue !== null) {
                $movementsQuery->orWhereHas('vehicle.so', function ($query) use ($searchValue) {
                    $query->where('so_number', 'like', '%' . $searchValue . '%');
                });
            } elseif ($columnName === 'po_number' && $searchValue !== null) {
                $movementsQuery->orWhereHas('vehicle.purchasingOrder', function ($query) use ($searchValue) {
                    $query->where('po_number', 'like', '%' . $searchValue . '%');
                });
            }
            elseif ($columnName === 'remarks' && $searchValue !== null) {
                $movementsQuery->orWhere('remarks', 'like', '%' . $searchValue . '%');
            } elseif ($columnName === 'custom_inspection_number' && $searchValue !== null) {
                $movementsQuery->orWhereHas('vehicle', function ($query) use ($searchValue) {
                    $query->where('custom_inspection_number', 'like', '%' . $searchValue . '%');
                });
            } elseif ($columnName === 'created_at' && $searchValue !== null) {
                $movementsQuery->orWhereHas('Movementrefernce', function ($query) use ($searchValue) {
                    $query->where('created_at', 'like', '%' . $searchValue . '%');
                });
            }
        }
        return DataTables::of($movementsQuery)
            ->addColumn('date', function ($movement) {
                return date('d-M-Y', strtotime($movement->Movementrefernce->date));
            })
            ->addColumn('created_at', function ($movement) {
                return date('d-M-Y', strtotime($movement->Movementrefernce->created_at));
            })
            ->addColumn('model_detail', function ($movement) {
                return $movement->vehicle->variant->model_detail ?? '';
            })
            ->addColumn('from_name', function ($movement) {
                return optional($movement->fromWarehouse)->name;
            })
            ->addColumn('to_name', function ($movement) {
                return optional($movement->toWarehouse)->name;
            })
            ->addColumn('so_number', function ($movement) {
                return $movement->vehicle->so->so_number ?? '';
            })
            ->addColumn('po_number', function ($movement) {
                return $movement->vehicle->purchasingOrder->po_number ?? '';
            })
            ->addColumn('custom_inspection_number', function ($movement) {
                return $movement->vehicle->custom_inspection_number ?? '';
            })
            ->addColumn('remarks', function ($movement) {
                return $movement->remarks ?? '';
            })
            ->toJson();
    }

    return view('movement.index', compact('vehicles', 'warehouses', 'movementreference'));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $warehouses = Warehouse::select('id', 'name')->orderBy('name', 'asc')->get();
        $movementsReferenceId = MovementsReference::max('id') + 1;
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('grn-movement');
        if($hasPermission)
        {
            $vehicles = Vehicles::whereNotNull('vin')
            ->where('status', '!=', 'cancel')
            ->where('vin', '!=', '')
            ->whereNull('grn_id')
            ->whereNotNull('inspection_date')
            ->where(function ($query) {
                $query->where('latest_location', '!=', '2')
                      ->orWhereNull('latest_location');
            })
            ->where('status', '=', 'Approved')
            ->pluck('vin');
    $purchasing_order = PurchasingOrder::where('status', 'Approved')
            ->whereHas('vehicles', function ($query) {
            $query->whereNull('grn_id')
            ->whereNotNull('vin')
            ->whereNotNull('inspection_date')
            ->where('status', 'Approved');
            })
    ->get();
    $po = PurchasingOrder::where('status', 'Approved')
    ->whereDoesntHave('vehicles', function ($query) {
        $query->whereNull('grn_id')
        ->where('status', 'Approved');
    })
    ->pluck('po_number');
    $so_number = So::whereDoesntHave('vehicles', function ($query) {
        $query->whereNull('grn_id')
        ->whereNotNull('vin')
                ->where('status', 'Approved');
    })
    ->pluck('so_number');
    $so = So::whereHas('vehicles', function ($query) {
        $query->whereNull('grn_id')
              ->where('status', 'Approved');
    })
    ->get();     
        }
        else
        {
        $vehicles = Vehicles::whereNotNull('vin')
        ->where('status', '!=', 'cancel')
        ->where('vin', '!=', '')
        ->whereNull('gdn_id')
        ->whereNotNull('inspection_date')
        ->where(function ($query) {
            $query->where('latest_location', '!=', '2')
                  ->orWhereNull('latest_location');
        })
        ->where('status', '=', 'Approved')
        ->pluck('vin'); 
        $purchasing_order = PurchasingOrder::where('status', 'Approved')
    ->whereHas('vehicles', function ($query) {
        $query->whereNull('gdn_id')
        ->whereNotNull('vin')
              ->where('status', 'Approved');
    })
    ->get();
    $po = PurchasingOrder::where('status', 'Approved')
    ->whereDoesntHave('vehicles', function ($query) {
        $query->whereNotNull('gdn_id')
        ->where('status', 'Approved');
    })
    ->pluck('po_number');
    $so_number = So::whereDoesntHave('vehicles', function ($query) {
        $query->whereNotNull('gdn_id')
        ->whereNotNull('vin')
                ->where('status', 'Approved');
    })
    ->pluck('so_number');
    $so = So::whereHas('vehicles', function ($query) {
        $query->whereNull('gdn_id')
              ->where('status', 'Approved');
    })
    ->get();
        }      
    $lastIdExists = MovementsReference::where('id', $movementsReferenceId - 1)->exists();
    $NextIdExists = MovementsReference::where('id', $movementsReferenceId + 1)->exists();
    return view('movement.create', [
        'movementsReferenceId' => $movementsReferenceId,
        'lastIdExists' => $lastIdExists,
        'NextIdExists' => $NextIdExists,
    ], compact('vehicles', 'warehouses','purchasing_order', 'so', 'po', 'so_number'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'vin' => 'required',
        ]);
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
        $vin = $request->input('vin');
        $from = $request->input('from');
        $to = $request->input('to');
        $date = $request->input('date');
        $createdBy = $request->user()->id;
        $movementsReference = new MovementsReference();
        $movementsReference->date = $date;
        $movementsReference->created_by = $createdBy;
        $movementsReference->save();
        $movementsReferenceId = $movementsReference->id;
        $grnVins = [];
        $gdnVins = [];
        $otherVins = [];
        foreach ($vin as $index => $value) {
            if (array_key_exists($index, $from) && array_key_exists($index, $to)) {
            $vehicle = Vehicles::where('vin', $vin[$index])->first();
            $ownershipType = is_array($request->input('ownership_type')) 
                ? $request->input('ownership_type')[$index] 
                : $request->input('ownership_type');
            $vehicle->ownership_type = $ownershipType;
            $vehicle->save();
            if ($vehicle) {
                if (($from[$index] === '1' && $to[$index] !== '3')) {
                    $grnVins[] = $vin[$index];
                } elseif ($to[$index] === '2') {
                    $gdnVins[] = $vin[$index];
                } else {
                    $otherVins[] = $vin[$index];
                }
            }
        }
        }
        if (!empty($grnVins)) {
            $grn = new Grn();
            $grn->date = $date;
            $grn->save();
            $grnNumber = $grn->id;
            $grn->save();
            Vehicles::whereIn('vin', $grnVins)->update(['grn_id' => $grnNumber]);
            $vehicleId = Vehicles::whereIn('vin', $grnVins)->pluck('id');
            $vehicleslog = new Vehicleslog();
                            $vehicleslog->time = $currentDateTime->toTimeString();
                            $vehicleslog->date = $currentDateTime->toDateString();
                            $vehicleslog->status = 'GRN Done';
                            $vehicleslog->vehicles_id = $vehicleId;
                            $vehicleslog->field = "GRN";
                            $vehicleslog->old_value = "";
                            $vehicleslog->new_value = "Vehicle Recived GRN Done";
                            $vehicleslog->created_by = auth()->user()->id;
                            $vehicleslog->save();
                            $vehicleCount = count($grnVins);
                            $grnDate = Carbon::parse($grn->date)->format('d M Y');
                            $groupedVehicles = Vehicles::whereIn('vin', $grnVins)->with([
                                'variant.master_model_lines.brand',
                                'variant.brand',
                                'interior',
                                'exterior'
                            ])->get()->groupBy('purchasing_order_id');
                            foreach ($groupedVehicles as $purchasingOrderId => $vehicles) {
                                $purchasingOrder = PurchasingOrder::find($purchasingOrderId);
                                if($purchasingOrder->is_demand_planning_po == 1)
                                {
                                $recipients = ['team.dp@milele.com'];
                                }
                                else
                                {
                                $recipients = ['abdul@milele.com'];   
                                }
                                $orderUrl = url('/purchasing-order/' . $purchasingOrderId);
                                $vehicleDetails = $vehicles->map(function ($vehicle) use ($grnDate, $grnNumber) {
                                    return [
                                        'vin' => $vehicle->vin,
                                        'grn' => $grnNumber,
                                        'grn_date' => $grnDate,
                                        'brand' => $vehicle->variant->brand->brand_name ?? '',
                                        'model_line' => $vehicle->variant->master_model_lines->model_line ?? '',
                                        'variant' => $vehicle->variant->name ?? '',
                                        'int_colour' => $vehicle->interior->name ?? '',
                                        'ext_colour' => $vehicle->exterior->name ?? '',
                                    ];
                                });
                                Mail::to($recipients)->send(new GRNEmailNotification($purchasingOrder->po_number, $purchasingOrder->pl_number, $orderUrl, $vehicleCount, $grnDate, $vehicleDetails));
                                $detailText = "PO Number: " . $purchasingOrder->po_number . "\n" .
                                "PFI Number: " . $purchasingOrder->pl_number . "\n" .
                                "Stage: " . "Goods Received Note\n" .
                                "Number of Units: " . $vehicleCount . "\n" .
                                "GRN Date: " . $grnDate . " Vehicles\n" .
                                "Order URL: " . $orderUrl;
                          $notification = New DepartmentNotifications();
                          $notification->module = 'Logistics';
                          $notification->type = 'Information';
                          $notification->detail = $detailText;
                          $notification->save();
                            }
        }
        if (!empty($gdnVins)) {
            $gdn = new Gdn();
            $gdn->date = $date;
            $gdn->save();
            Vehicles::whereIn('vin', $gdnVins)->update(['gdn_id' => $gdn->id]);
            $vehicleId = Vehicles::whereIn('vin', $gdnVins)->pluck('id');
            $vehicleslog = new Vehicleslog();
                            $vehicleslog->time = $currentDateTime->toTimeString();
                            $vehicleslog->date = $currentDateTime->toDateString();
                            $vehicleslog->status = 'GRN Done';
                            $vehicleslog->vehicles_id = $vehicleId;
                            $vehicleslog->field = "GDN";
                            $vehicleslog->old_value = "";
                            $vehicleslog->new_value = "Vehicle Delivered to the Client";
                            $vehicleslog->created_by = auth()->user()->id;
                            $vehicleslog->save();
        }
        $newvin = $request->input('newvin');
        $vin = $request->input('vin');
        foreach ($vin as $index => $value) {
            if (array_key_exists($index, $from) && array_key_exists($index, $to)) {    
            $movement = new Movement();
            $movement->vin = $vin[$index];
            $movement->from = $from[$index];
            $movement->to = $to[$index];
            $movement->reference_id = $movementsReferenceId;
            if (isset($newvin[$index]) && $newvin[$index] !== null && $newvin[$index] !== '') {
                $movement->vin = $newvin[$index];
            }
            $movement->save();
            Vehicles::where('vin', $vin[$index])->update(['latest_location' => $to[$index]]);
        }
    }
    if($newvin){
    foreach ($newvin as $index => $value) {
        if ($value !== null && $value !== '' && isset($vin[$index])) {
            $vehicle = Vehicles::where('vin', $vin[$index])->first();
            $movements = Movement::where('vin', $value)->first();
            if ($vehicle) {
                $vinchange = New VinChange();
                $vinchange->old_vin = $vehicle->vin;
                $vinchange->new_vin = $value;
                $vinchange->vehicles_id = $vehicle->id;
                $vinchange->movements_id = $movements->id;
                $vinchange->created_by = auth()->user()->id;
                $vinchange->save();
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'VIN Change';
                $vehicleslog->vehicles_id = $vehicle->id;
                $vehicleslog->field = "VIN Change";
                $vehicleslog->old_value = $vehicle->vin;
                $vehicleslog->new_value = $value;
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->save();
                $updatevin = Vehicles::find($vehicle->id);
                $updatevin->vin = $value;
                $updatevin->save();
            }
        }
    }
}    
return redirect()->back()->with('success', 'Transition has been successfully Saved!');
    }    
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
    $lastIdDetails = MovementsReference::find($id);
    $previousId = MovementsReference::where('id', '<', $id)->max('id');
    $nextId = MovementsReference::where('id', '>', $id)->min('id');
    $movement = Movement::where('reference_id', $id)->get();
    $movementref = MovementsReference::where('id', $id)->first();
    return view('movement.view', [
           'currentId' => $id,
           'previousId' => $previousId,
           'nextId' => $nextId
       ], compact('movement', 'movementref'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movement $movement)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movement $movement)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movement $movement)
    {
        //
    }
    public function lastReference($currentId)
    {
    $lastIdDetails = MovementsReference::find($currentId);
    $previousId = MovementsReference::where('id', '<', $currentId)->max('id');
    $nextId = MovementsReference::where('id', '>', $currentId)->min('id');
    $movement = Movement::where('reference_id', $currentId)->get();
    $movementref = MovementsReference::where('id', $currentId)->first();
    return view('movement.view', [
           'currentId' => $currentId,
           'previousId' => $previousId,
           'nextId' => $nextId
       ], compact('movement', 'movementref'));
    }
    public function vehiclesdetails(Request $request)
    {
    $vin = $request->input('vin');
    $vehicle = Vehicles::where('vin', $vin)->first();
    $variant = Varaint::find($vehicle->varaints_id)->name;
    $modelLine = MasterModelLines::find($vehicle->variant->master_model_lines_id)->model_line;
    $po_number = PurchasingOrder::find($vehicle->purchasing_order_id)->po_number;
    $so_number = $vehicle->so_id ? So::find($vehicle->so_id)->so_number : '';
    $brand = Brand::find($vehicle->variant->brands_id)->brand_name;
    $ownership_type = $vehicle->ownership_type;
    $movement = Movement::where('vin', $vin)->pluck('to')->last();
    $warehouseName = Warehouse::where('id', $movement)->pluck('id')->first();
    if (empty($warehouseName)) {
        if($vehicle->latest_location){
        $warehouseName = Warehouse::where('id', $vehicle->latest_location)->pluck('id')->first();
        }
        else{
           $warehouseName = 1;
        }
        }
    return response()->json([
        'variant' => $variant,
        'brand' => $brand,
        'ownership_type' => $ownership_type,
        'movement' => $warehouseName,
        'po_number' => $po_number,
        'so_number' => $so_number,
        'modelLine' => $modelLine
    ]);
    }
    public function vehiclesdetailsaspo(Request $request)
    {
    $selectedPo = $request->input('po');
    $po_id = PurchasingOrder::where('po_number', $selectedPo)
    ->pluck('id');
    $vehiclesWithSelectedPo = Vehicles::where('purchasing_order_id', $po_id)->where('gdn_id', null)->pluck('vin');
    $so_ids = Vehicles::where('purchasing_order_id', $po_id)->whereNull('gdn_id')->pluck('so_id');
    $so_numbers = So::whereIn('id', $so_ids)->pluck('so_number');
    return response()->json([
        'vin_list' => $vehiclesWithSelectedPo,
        'so_number' => $so_numbers,
    ]);
    }
    public function vehiclesdetailsasso(Request $request)
    {
    $selectedSo = $request->input('so');
    $so_id = So::where('so_number', $selectedSo)
    ->pluck('id');
    $vehiclesWithSelectedSo = Vehicles::where('so_id', $so_id)->where('gdn_id', null)->pluck('vin');
    $purchasing_order_id = Vehicles::where('so_id', $so_id)->whereNull('gdn_id')->pluck('purchasing_order_id');
    $po_numbers = PurchasingOrder::whereIn('id', $purchasing_order_id)->pluck('po_number');
    return response()->json([
        'vin_list' => $vehiclesWithSelectedSo,
        'po_number' => $po_numbers,
    ]);
    }
    
    public function grnlist(){
        return view('movement.grnlist');   
    }
    public function grnsimplefile()
{
    $filePath = storage_path('app/public/sample/gdnlist.xlsx'); // Path to the Excel file
    if (file_exists($filePath)) {
        // Generate a response with appropriate headers
        return Response::download($filePath, 'gdnlist.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    } else {
        return redirect()->back()->with('error', 'The requested file does not exist.');
    }
}
public function grnfilepost(Request $request)
{
    if ($request->hasFile('file') && $request->file('file')->isValid()) {
        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        if (!in_array($extension, ['xls', 'xlsx'])) {
            return back()->with('error', 'Invalid file format. Only Excel files (XLS or XLSX) are allowed.');
        }
        $rows = Excel::toArray([], $file, null, \Maatwebsite\Excel\Excel::XLSX)[0];
        $headers = array_shift($rows);
        $existingVins = [];
        $missingVins = [];
        foreach ($rows as $row) {
            $vin = $row[0];
            $grnDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[1])->format('Y-m-d');
            $grnNumber = $row[2];
            $grnNumber = $row[2];
            $vehicle = Vehicles::where('vin', $vin)->first();
            if ($vehicle) {
                $grn = grn::find($vehicle->grn_id);
                if ($grn) {
                    $grn->date = $grnDate;
                    $grn->save();
                    $existingVins[] = $vin;
                }
            } else {
                $missingVins[] = $vin;
            }
        }
        if (!empty($missingVins)) {
            $missingVinsData = [['VIN']];
            foreach ($missingVins as $vin) {
                $missingVinsData[] = [$vin];
            }
            $missingVinsFilePath = storage_path('app/public/missing_vins.xlsx');
            Excel::store($missingVinsData, 'missing_vins.xlsx');
            return response()->download($missingVinsFilePath)->deleteFileAfterSend(true);
        }
        return back()->with('success', 'GRN information updated successfully.');
    }
    return back()->with('error', 'No valid file found.');
    }
    public function getVehiclesDataformovement(Request $request)
    {
        $selectedPOId = $request->input('po_id');
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('grn-movement');
        if($hasPermission)
        {
            $vehicles = Vehicles::where('purchasing_order_id', $selectedPOId)
            ->whereNotNull('vin')
            ->where('status', '!=', 'cancel')
            ->whereNull('grn_id')
            ->whereNotNull('inspection_date')
            ->where('status', '=', 'Approved')
            ->pluck('id');
        }
        else
        {
        $vehicles = Vehicles::where('purchasing_order_id', $selectedPOId)
            ->whereNotNull('vin')
            ->where('status', '!=', 'cancel')
            ->whereNull('gdn_id')
            ->whereNotNull('inspection_date')
            ->where('status', '=', 'Approved')
            ->pluck('id');
        }
            $vehicleDetails = [];
            foreach($vehicles  as $key =>  $vehicle) {
                $data = Vehicles::find($vehicle);
                $vehicleDetails[$key]['vin'] = $data->vin;
                $vehicle = Vehicles::where('vin', $data->vin)->first();
                $variant = Varaint::find($vehicle->varaints_id)->name;
                $po_number = PurchasingOrder::find($vehicle->purchasing_order_id)->po_number;
                $so_number = $vehicle->so_id ? So::find($vehicle->so_id)->so_number : '';
                $modelLine = MasterModelLines::find($vehicle->variant->master_model_lines_id)->model_line;
                $brand = Brand::find($vehicle->variant->brands_id)->brand_name;
                $ownership_type = $vehicle->ownership_type;
                $movement = Movement::where('vin', $data->vin)->pluck('to')->last();
                $warehouseName = Warehouse::where('id', $movement)->pluck('id')->first();
                $warehouseNames = Warehouse::where('id', $movement)->pluck('name')->first();
                if (empty($warehouseName)) {
                 if($vehicle->latest_location){
                 $warehouseName = Warehouse::where('id', $vehicle->latest_location)->pluck('id')->first();
                 }
                 else{
                    $warehouseName = 1;
                 }
                 }
                 if (empty($warehouseNames)) {
                 if($vehicle->latest_location)
                 {
                    $warehouseNames = Warehouse::where('id', $vehicle->latest_location)->pluck('name')->first();
                 }
                else
                {
                    $warehouseNames = "Supplier";
                }
                }
                 $vehicleDetails[$key]['variant'] = $variant;
                 $vehicleDetails[$key]['modelLine'] = $modelLine;
                 $vehicleDetails[$key]['brand'] = $brand;
                 $vehicleDetails[$key]['ownership_type'] = $ownership_type;
                 $vehicleDetails[$key]['warehouseName'] = $warehouseName;
                 $vehicleDetails[$key]['warehouseNames'] = $warehouseNames;
                 $vehicleDetails[$key]['po_number'] = $po_number;
                 $vehicleDetails[$key]['so_number'] = $so_number;
            }
         return response()->json($vehicleDetails);
    }
    public function getVehiclesDataformovementso(Request $request)
    {
        $selectedSOId = $request->input('so_id');
        $vehicles = Vehicles::where('so_id', $selectedSOId)
            ->whereNotNull('vin')
            ->where('status', '!=', 'cancel')
            ->whereNull('gdn_id')
            ->whereNotNull('inspection_date')
            ->where('status', '=', 'Approved')
            ->pluck('id');
            $vehicleDetails = [];
            foreach($vehicles  as $key =>  $vehicle) {
                $data = Vehicles::find($vehicle);
                $vehicleDetails[$key]['vin'] = $data->vin;
                $vehicle = Vehicles::where('vin', $data->vin)->first();
                $variant = Varaint::find($vehicle->varaints_id)->name;
                $po_number = PurchasingOrder::find($vehicle->purchasing_order_id)->po_number;
                $so_number = $vehicle->so_id ? So::find($vehicle->so_id)->so_number : '';
                $modelLine = MasterModelLines::find($vehicle->variant->master_model_lines_id)->model_line;
                $ownership_type = $vehicle->ownership_type;
                $brand = Brand::find($vehicle->variant->brands_id)->brand_name;
                $movement = Movement::where('vin', $data->vin)->pluck('to')->last();
                $warehouseName = Warehouse::where('id', $movement)->pluck('id')->first();
                $warehouseNames = Warehouse::where('id', $movement)->pluck('name')->first();
                if (empty($warehouseName)) {
                    if($vehicle->latest_location){
                    $warehouseName = Warehouse::where('id', $vehicle->latest_location)->pluck('id')->first();
                    }
                    else{
                       $warehouseName = 1;
                    }
                    }
                    if (empty($warehouseNames)) {
                    if($vehicle->latest_location)
                    {
                       $warehouseNames = Warehouse::where('id', $vehicle->latest_location)->pluck('name')->first();
                    }
                   else
                   {
                       $warehouseNames = "Supplier";
                   }
                   }
                 $vehicleDetails[$key]['variant'] = $variant;
                 $vehicleDetails[$key]['ownership_type'] = $ownership_type;
                 $vehicleDetails[$key]['modelLine'] = $modelLine;
                 $vehicleDetails[$key]['brand'] = $brand;
                 $vehicleDetails[$key]['warehouseName'] = $warehouseName;
                 $vehicleDetails[$key]['warehouseNames'] = $warehouseNames;
                 $vehicleDetails[$key]['po_number'] = $po_number;
                 $vehicleDetails[$key]['so_number'] = $so_number;
            }
         return response()->json($vehicleDetails);
    }
    public function revise(Request $request, $id)
{
    $movementlast = Movement::findOrFail($id);
    $vehicle = Vehicles::where('vin', $movementlast->vin)->first(); 
    $revisedmovement = new Movement();
    $revisedmovement->from = $movementlast->to;
    $revisedmovement->to = $movementlast->from;
    $revisedmovement->vin = $movementlast->vin;
    $revisedmovement->reference_id = $movementlast->reference_id;
    $revisedmovement->remarks = 'Revised Movement';
    $revisedmovement->save();
    if ($movementlast->from === 1) {
        if ($vehicle) {
            $vehicle->grn_id = null;
            $vehicle->save();
        }
    } else if ($movementlast->to === 2) {
        if ($vehicle) {
            $vehicle->gdn_id = null;
            $vehicle->save();
        }
    }
    return redirect()->route('movement.index')->with('success', 'Movement has been revised successfully.');
}
public function uploadVinFile(Request $request)
{
    if ($request->hasFile('vin_file')) {
        $file = $request->file('vin_file');
        $vinData = [];

        // Process CSV file
        if (($handle = fopen($file, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $vinData[] = [
                    'vin' => $data[0], // VIN number
                    'to' => $data[1],   // Warehouse name (from file, you need to add this column)
                    'ownership_type' => $data[2]
                ];
            }
            fclose($handle);
        }

        // Same permission check logic as before
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('grn-movement');
        $vinNumbers = array_column($vinData, 'vin');

        // Retrieve vehicles based on permissions
        $query = Vehicles::whereIn('vin', $vinNumbers)
            ->whereNotNull('vin')
            ->whereNotNull('inspection_date')
            ->where('status', '!=', 'cancel')
            ->whereNull($hasPermission ? 'grn_id' : 'gdn_id')
            ->where('status', '=', 'Approved');

        $vehicles = $query->get()->keyBy('vin'); // Retrieve vehicles and key them by VIN

        if ($vehicles->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No matching VINs found']);
        }

        // Prepare vehicle details in the same order as vinData
        $vehicleDetails = [];
        foreach ($vinData as $entry) {
            $vin = $entry['vin'];
            $toWarehouse = $entry['to'];
            $ownership_type = $entry['ownership_type'];
            if (isset($vehicles[$vin])) {
                $vehicle = $vehicles[$vin];
                $variant = Varaint::find($vehicle->varaints_id)->name;
                $po_number = PurchasingOrder::find($vehicle->purchasing_order_id)->po_number;
                $so = $vehicle->so_id ? So::find($vehicle->so_id) : null;
                $so_number = $so ? $so->so_number : '';
                $modelLine = MasterModelLines::find($vehicle->variant->master_model_lines_id)->model_line;
                $brand = Brand::find($vehicle->variant->brands_id)->brand_name;
                $movement = Movement::where('vin', $vin)->pluck('to')->last();
                $warehouseName = Warehouse::where('id', $movement)->pluck('id')->first();
                $warehouseNames = Warehouse::where('id', $movement)->pluck('name')->first();
                // Default to Supplier if no warehouse name found
                $warehouseName = $warehouseName ?: ($vehicle->latest_location ? Warehouse::where('id', $vehicle->latest_location)->pluck('id')->first() : 1);
                $warehouseNames = $warehouseNames ?: ($vehicle->latest_location ? Warehouse::where('id', $vehicle->latest_location)->pluck('name')->first() : "Supplier");
                // Match the 'to' warehouse from the CSV and set as default
                $matchedWarehouse = Warehouse::where('name', $toWarehouse)->first();
                $vehicleDetails[] = [
                    'vin' => $vin,
                    'ownership_type' => $ownership_type,
                    'variant' => $variant,
                    'modelLine' => $modelLine,
                    'brand' => $brand,
                    'warehouseName' => $warehouseName,
                    'warehouseNames' => $warehouseNames,
                    'po_number' => $po_number,
                    'so_number' => $so_number,
                    'matchedWarehouseId' => $matchedWarehouse ? $matchedWarehouse->id : null // Store the matched warehouse ID
                ];
            }
        }

        return response()->json(['success' => true, 'vehicleDetails' => $vehicleDetails]);
    } else {
        return response()->json(['success' => false, 'message' => 'No file uploaded']);
    }
}
    }