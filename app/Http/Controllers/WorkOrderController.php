<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\Customer;
use App\Models\Clients;
use App\Models\Vehicles;
use App\Models\Masters\MasterAirlines;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
class WorkOrderController extends Controller
{
    public function workOrderCreate($type) {
        // $type = 'export_exw';
        // $customers = Customer::orderBy('name','ASC')->get();
        // $clients = Clients::orderBy('name','ASC')->get();

        $dpCustomers = Customer::select(DB::raw('name as customer_name'), DB::raw('NULL as customer_email'), DB::raw('NULL as customer_company_number'), DB::raw('address as customer_address'))->distinct();
        $clients = Clients::select(DB::raw('name as customer_name'), DB::raw('email as customer_email'),DB::raw('phone as customer_company_number'), DB::raw('NULL as customer_address'))->distinct();
        $workOrders = WorkOrder::select('customer_name', 'customer_email', 'customer_company_number', 'customer_address')->distinct();
        
        $customers = $dpCustomers->union($clients)->union($workOrders)->get();
        $customers = $customers->unique('customer_name');
       // Combine the queries ensuring each select has the same number of columns
// Select and transform data from the Customer table
// $dpCustomers = Customer::select(
//     DB::raw('name as customer_name'), 
//     DB::raw('NULL as customer_email'), 
//     DB::raw('NULL as customer_company_number'), 
//     DB::raw('address as customer_address'),
//     DB::raw('(IF(address IS NOT NULL, 1, 0)) as score')
// )->distinct();

// // Select and transform data from the Clients table
// $clients = Clients::select(
//     DB::raw('name as customer_name'), 
//     DB::raw('email as customer_email'), 
//     DB::raw('phone as customer_company_number'), 
//     DB::raw('NULL as customer_address'),
//     DB::raw('(IF(email IS NOT NULL, 1, 0) + IF(phone IS NOT NULL, 1, 0)) as score')
// )->distinct();

// // Select data from the WorkOrder table
// $workOrders = WorkOrder::select(
//     'customer_name', 
//     'customer_email', 
//     'customer_company_number', 
//     'customer_address',
//     DB::raw('(IF(customer_email IS NOT NULL, 1, 0) + IF(customer_company_number IS NOT NULL, 1, 0) + IF(customer_address IS NOT NULL, 1, 0)) as score')
// )->distinct();

// // Combine the results
// $combinedResults = $dpCustomers->union($clients)->union($workOrders)->get();

// // Sort by score in descending order, then by customer_name in ascending order
// $customers = $combinedResults->sortByDesc('score')->unique('customer_name')->values()->sortBy('customer_name');

// // Convert collection to array or use directly if needed
// $customersArray = $customers->toArray();

// // Select distinct customer names with the highest scores
// $uniqueCustomers = new Collection();
// $customers->each(function ($item) use ($uniqueCustomers) {
//     if (!$uniqueCustomers->contains('customer_name', $item->customer_name)) {
//         $uniqueCustomers->push($item);
//     }
// });
// dd($customers);
// ->union($workOrders)
        $airlines = MasterAirlines::orderBy('name','ASC')->get();
        $vins = Vehicles::orderBy('vin','ASC')->whereNotNull('vin')->with('variant.master_model_lines.brand','interior','exterior','warehouseLocation','document')->get()->unique('vin');
        return view('work_order.export_exw.create',compact('type','customers','airlines','vins'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        dd($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkOrder $workOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkOrder $workOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkOrder $workOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkOrder $workOrder)
    {
        //
    }
}
