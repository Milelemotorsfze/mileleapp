<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterShippingPorts;
use App\Models\UserActivities;
use App\Models\Country;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class PortsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)        
    {
        $useractivities = new UserActivities();
        $useractivities->activity = "View the Master Ports";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        if ($request->ajax()) { 
            $status = $request->input('status');
            $searchValue = $request->input('search.value');
    
            $data = MasterShippingPorts::select([
                'master_shipping_ports.id',
                'master_shipping_ports.name',
                'countries.name as countryname',
                DB::raw("DATE_FORMAT(DATE(master_shipping_ports.created_at), '%d-%b-%Y') as created_at")
            ])
            ->leftJoin('countries', 'countries.id', '=', 'master_shipping_ports.country_id');
    
            return DataTables::of($data)
                ->editColumn('created_at', function ($row) {
                    // Format the created_at column to retrieve date only
                    return Carbon::parse($row->created_at)->format('d-M-Y');
                })                
                ->toJson();
        }
        return view('ports.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $useractivities = new UserActivities();
        $useractivities->activity = "Create New Port Page Open";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $countries = Country::all();
        return view('ports.create', ['countries' => $countries]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Log the user activity
    $useractivities = new UserActivities();
    $useractivities->activity = "Store New Port";
    $useractivities->users_id = Auth::id(); // Assuming you are using Laravel's default authentication
    $useractivities->save();
    // Get port name from the request
    $portName = $request->input('port_name');
    // Get country IDs from the request; assuming the name attribute of the select is 'country[]'
    $countries = $request->input('country');

    // Check if countries is an array and not empty
    if (is_array($countries) && count($countries) > 0) {
        foreach ($countries as $countryId) {
            // Create a new MasterShippingPorts object for each country
            $porting = new MasterShippingPorts();
            $porting->name = $portName;
            $porting->country_id = $countryId;
            $porting->save();
        }
    }
    // Redirect to the ports index route with a success message
    return redirect()->route('ports.index')->with('success', 'Port has been successfully added!');
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
       $countries = Country::all();
       $ports =  MasterShippingPorts::find($id);
       return view('ports.edit', compact('ports','countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'port_name' => 'required|string|max:255',
            'country' => 'required|exists:countries,id',
        ]);
        $port = MasterShippingPorts::findOrFail($id);
        $port->name = $request->port_name;
        $port->country_id = $request->country;
        $port->save();
        return redirect()->route('ports.index')->with('success', 'Port updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
