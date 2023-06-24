<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\Warehouselog;
use Carbon\Carbon;
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('warehouse.listcreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'string|required|max:255',
        ]);
        $name = $request->input('name');
        $existingWarehouse = Warehouse::where('name', $name)->first();
        if ($existingWarehouse) {
            return redirect()->back()->with('error', 'Warehouse with the same name already exists.');
        }
        $warehouse = new Warehouse();
        $warehouse->name  = $name;
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
        return view('warehouse.list')->with(compact('warehouselist'))->with('success', 'Warehouse added successfully.');
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
        $warehouse = Warehouse::findOrFail($id);
        $warehouselog = Warehouselog::where('warehouse_id', $id)->orderBy('created_at', 'desc')->get();
        return view('warehouse.editlist',compact('warehouse','warehouselog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'string|required|max:255',
        ]);
        $name = $request->input('name');
        $existingColour = Warehouse::where('name', $name)->where('id', '!=', $id)->first();
        if ($existingColour) {
            return redirect()->back()->with('error', 'Warehouse with the same name already exists.');
        }
        $warehouse = Warehouse::findOrFail($id);   
        $oldValues = $warehouse->toArray();
        $warehouse->name  = $name;
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
    return view('warehouse.list', compact('warehouselist'))->with('success', 'Variant added successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
