<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Events\DataUpdatedEvent;
use Carbon\Carbon;
use App\Models\Vehicles;
use App\Models\Warehouselog;
use App\Models\Remarks;
use App\Models\Vehicleslog;
use Carbon\CarbonTimeZone;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $warehouselist = Warehouse::orderBy('id','DESC')->get();
        return view('warehouse.list', compact('warehouselist'));
    }
    public function create()
    {
        return view('warehouse.listcreate');
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'string|required|max:255',
            'status' => 'required|boolean',
        ]);
        $name = $request->input('name');
        $existingWarehouse = Warehouse::where('name', $name)->first();
        if ($existingWarehouse) {
            return redirect()->back()->with('error', 'Warehouse with the same name already exists.');
        }
        $warehouse = new Warehouse();
        $warehouse->name  = $name;
        $warehouse->status = $request->input('status', 1); 
        $warehouse->created_by = auth()->user()->id;
        $warehouse->save();
        $warehouseId = $warehouse->id;
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
        $warehouselog = new Warehouselog();
        $warehouselog->time = $currentDateTime->toTimeString();
        $warehouselog->date = $currentDateTime->toDateString();
        $warehouselog->status = 'New Created';
        $warehouselog->warehouse_id = $warehouseId;
        $warehouselog->created_by = auth()->user()->id;
        $warehouselog->save();
        $warehouselist = Warehouse::orderBy('id','DESC')->get();
        // return view('warehouse.list')->with(compact('warehouselist'))->with('success', 'Warehouse added successfully.');
        return redirect()->route('warehouse.index')->with('success', 'Warehouse created successfully.');
    }
    public function show(string $id)
    {
    }
    public function edit(string $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouselog = Warehouselog::where('warehouse_id', $id)->orderBy('created_at', 'desc')->get();
        $usedByVehicles = Vehicles::where('latest_location', $id)
        ->whereNotNull('vin')
        ->whereNull('gdn_id')
        ->exists();

        return view('warehouse.editlist',compact('warehouse','warehouselog', 'usedByVehicles'));
    }
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'string|required|max:255',
            'status' => 'required|boolean',
        ]);
        $name = $request->input('name');
        $existingColour = Warehouse::where('name', $name)->where('id', '!=', $id)->first();
        if ($existingColour) {
            return redirect()->back()->with('error', 'Warehouse with the same name already exists.');
        }
        $warehouse = Warehouse::findOrFail($id);   
        $oldValues = $warehouse->toArray();
        $warehouse->name  = $name;
        $warehouse->status = $request->input('status');
        $changes = [];
        foreach ($oldValues as $field => $oldValue) {
            if ($field !== 'created_at' && $field !== 'updated_at') {
                $newValue = $warehouse->$field;
                if ($oldValue != $newValue) {
                    $changes[$field] = [
                        'old_value' => $oldValue,
                        'new_value' => $newValue,
                    ];
                }
            }
        }
        if (!empty($changes)) {
        $warehouse->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
        foreach ($changes as $field => $change) {
        $colorlog = new Warehouselog();
        $colorlog->time = $currentDateTime->toTimeString();
        $colorlog->date = $currentDateTime->toDateString();
        $colorlog->status = 'Update Values';
        $colorlog->warehouse_id = $id;
        $colorlog->field = $field;
        $colorlog->old_value = $change['old_value'];
        $colorlog->new_value = $change['new_value'];
        $colorlog->created_by = auth()->user()->id;
        $colorlog->save();
        }
    }
    $warehouselist = Warehouse::orderBy('id','DESC')->get();
    // return view('warehouse.list', compact('warehouselist'))->with('success', 'Variant added successfully.');
    return redirect()->route('warehouse.index')->with('success', 'Warehouse updated successfully.');
    }
    public function destroy(string $id)
    {
        //
    }
    public function updatewarehouseremarks(Request $request) {
        $request->validate([
            'id' => 'required',
            'remarks' => 'required',
        ]);
        $id = $request->input('id');
        $remarks = $request->input('remarks');
        $vehicle_remarks = New Remarks();
        $vehicle_remarks->remarks = $remarks;
        $vehicle_remarks->vehicles_id = $id;
        $now = Carbon::now();
        $vehicle_remarks->time = $now->format('H:i:s');
        $vehicle_remarks->date = $now->toDateString();
        $vehicle_remarks->department = "warehouse";
        $vehicle_remarks->created_by = auth()->user()->id;
        $vehicle_remarks->save();
        $vehicleslog = new Vehicleslog();
        $vehicleslog->time = $now->format('H:i:s');
        $vehicleslog->date = $now->toDateString();
        $vehicleslog->status = 'Adding New Remarks';
        $vehicleslog->vehicles_id = $id;
        $vehicleslog->field = "Warehouse Remarks";
        $vehicleslog->old_value = "";
        $vehicleslog->new_value = $remarks;
        $vehicleslog->created_by = auth()->user()->id;
        $vehicleslog->save();
        event(new DataUpdatedEvent(['id' => $id, 'message' => "Data Update"]));
        return redirect()->back()->with('success', 'Remarks updated successfully');
    }
}
