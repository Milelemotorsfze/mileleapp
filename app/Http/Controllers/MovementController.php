<?php

namespace App\Http\Controllers;

use App\Models\Movement;
use App\Models\Vehicles;
use App\Models\Warehouse;
use App\Models\Varaint;
use App\Models\MasterModelLines;
use App\Models\Brand;
use Illuminate\Support\Facades\Auth;
use App\Models\MovementsReference;
use App\Models\Grn;
use App\Models\Gdn;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;

class MovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Movement::get();
        $movementreference = MovementsReference::get();
        $vehicles = Vehicles::whereNotNull('vin')
        ->where('status', '!=', 'cancel')
        ->pluck('vin', 'varaints_id');    
        $warehouses = Warehouse::select('id', 'name')->get();
        return view('movement.index', compact('data', 'vehicles', 'warehouses', 'movementreference'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vehicles = Vehicles::whereNotNull('vin')
        ->where('status', '!=', 'cancel')
        ->whereNull('gdn_id')
        ->where('payment_status', '=', 'Incoming Stock')
        ->pluck('vin');       
    $warehouses = Warehouse::select('id', 'name')->get();
    $movementsReferenceId = MovementsReference::max('id') + 1;
    $lastIdExists = MovementsReference::where('id', $movementsReferenceId - 1)->exists();
    $NextIdExists = MovementsReference::where('id', $movementsReferenceId + 1)->exists();
    return view('movement.create', [
        'movementsReferenceId' => $movementsReferenceId,
        'lastIdExists' => $lastIdExists,
        'NextIdExists' => $NextIdExists,
    ], compact('vehicles', 'warehouses'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
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
        foreach ($vin as $index => $value) {
            if ($from[$index] === $to[$index]) {
                return back()->withErrors("The 'from' and 'to' values cannot be the same.");
            } 
            $vehicle = Vehicles::where('vin', $vin[$index])->first();
            if ($vehicle && !$vehicle->grn_id) {
                $grn = new Grn();
                $grn->date = $date;
                $grn->save();
                $gdnNumber = $grn->id;
                $grn->grn_number = $gdnNumber;
                $grn->save();
                $vehicle->grn_id = $gdnNumber;
                $vehicle->save();$grnNumber;
                $vehicle->save();
            }
            if ($to[$index] === '2') {
                $gdn = new Gdn();
                $gdn->date = $date;
                $gdn->save();
                $gdnNumber = $gdn->id;
                $gdn->gdn_number = $gdnNumber;
                $gdn->save();
                $vehicle->gdn_id = $gdnNumber;
                $vehicle->save();
            }
            $movement = new Movement();
            $movement->vin = $vin[$index];
            $movement->from = $from[$index];
            $movement->to = $to[$index];
            $movement->reference_id = $movementsReferenceId;
            $movement->save();
            $vehicle->latest_location = $to[$index];
            $vehicle->save();
        }
        $data = Movement::get();
        $vehicles = Vehicles::whereNotNull('vin')
        ->where('status', '!=', 'cancel')
        ->pluck('vin', 'varaints_id'); 
        $warehouses = Warehouse::select('id', 'name')->get();
        $movementreference = MovementsReference::get();  
        return view('movement.index', compact('data', 'vehicles', 'warehouses', 'movementreference'));
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
    $brand = Brand::find($vehicle->variant->brands_id)->brand_name;
    $movement = Movement::where('vin', $vin)->pluck('to')->last();
    $warehouseName = Warehouse::where('id', $movement)->pluck('id')->first();
    if (empty($warehouseName)) {
        $warehouseName = 1;
    }
    return response()->json([
        'variant' => $variant,
        'brand' => $brand,
        'movement' => $warehouseName,
        'modelLine' => $modelLine
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
                    $grn->grn_number = $grnNumber;
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
    }