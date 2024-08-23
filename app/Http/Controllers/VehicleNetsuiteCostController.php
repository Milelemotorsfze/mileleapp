<?php

namespace App\Http\Controllers;
use App\Imports\VehicleNetSuiteCostImport;
use App\Models\VehicleNetsuiteCost;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\UserActivities;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class VehicleNetsuiteCostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Open Netsuite Vehicle Cost";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        if ($request->ajax()) {
            $status = $request->input('status');
            $searchValue = $request->input('search.value');
            $data = VehicleNetsuiteCost::select( [
                    'vehicle_netsuite_cost.cost',
                    'vehicles.vin',
                    DB::raw("DATE_FORMAT(vehicle_netsuite_cost.updated_at, '%d-%b-%Y') as last_update"),
                    'vehicle_netsuite_cost.netsuite_link',
                ])
                ->leftJoin('vehicles', 'vehicle_netsuite_cost.vehicles_id', '=', 'vehicles.id');
                $data = $data->groupBy('vehicles.id');
                return DataTables::of($data)
                ->toJson();
        }
        return view('vehicles.vehicle_netsuite_cost');
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
    public function upload(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);
        // Process the uploaded file using the import class
        Excel::import(new VehicleNetSuiteCostImport, $request->file('file'));
        // Redirect back with a success message
        return redirect()->route('vehiclenetsuitecost.index')->with('success', 'Vehicle costs updated successfully!');
    }
}
