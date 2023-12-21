<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use App\Models\Supplier;
use App\Http\Controllers\UserActivityController;
use App\Models\ShippingDocuments;
use App\Models\ShippingCertification;
use App\Models\OtherLogisticsCharges;
use App\Models\ShippingMedium;
use App\Models\UserActivities;
use App\Models\ShippingRate;
use App\Models\MasterShippingPorts;
use Illuminate\Http\Request;
use Psy\Util\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Builder;

class ShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $useractivities = new UserActivities();
        $useractivities->activity = "View the Shipping Addons";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        if ($request->ajax()) {
            $status = $request->input('status');
            $searchValue = $request->input('search.value');
            if ($status === "Shipping") {
                $data = ShippingMedium::select([
                    'shipping_medium.id',
                    'shipping_medium.name',
                    'shipping_medium.code',
                    'shipping_medium.description',
                    'shipping_medium.created_at',
                ]);
                $data = $data->groupBy('shipping_medium.id');
            }
            else if ($status === "Shipping_document") {
                $data = ShippingDocuments::select([
                    'shipping_documents.id',
                    'shipping_documents.name',
                    'shipping_documents.description',
                    'shipping_documents.price',
                    'shipping_documents.created_by',
                    'shipping_documents.created_at',
                ]);
                $data = $data->groupBy('shipping_documents.id');
            }
            else if ($status === "certification") {
                $data = ShippingCertification::select([
                    'shipping_certification.id',
                    'shipping_certification.name',
                    'shipping_certification.description',
                    'shipping_certification.price',
                    'shipping_certification.created_by',
                    'shipping_certification.created_at',
                ]);
                $data = $data->groupBy('shipping_certification.id');
            }
            else if ($status === "others") {
                $data = OtherLogisticsCharges::select([
                    'other_logistics_charges.id',
                    'other_logistics_charges.name',
                    'other_logistics_charges.description',
                    'other_logistics_charges.price',
                    'other_logistics_charges.created_by',
                    'other_logistics_charges.created_at',
                ]);
                $data = $data->groupBy('other_logistics_charges.id');
            }
            else {
                $data = null;
            }
            if ($data) {
                return DataTables::of($data)->toJson();
            }
        }
        return view('logistics.shipping');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Open Create New Shipping & Certifications";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        return view('logistics.createshipping');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Create New Lead";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $category = $request->input('category');
        $addon_name = $request->input('addon_name');
        $price = $request->input('price');
        $description = $request->input('description');
        if($category == "Shipping"){
        $shipping = New ShippingMedium;
        $shipping->name = $addon_name;
        $shipping->description = $description;
        $latestCode = ShippingMedium::withTrashed()->orderBy('id', 'desc')->first();
        $code = $this->generateUUID($latestCode, $category);
        $shipping->code = $code;
        $shipping->save();
        }
        else if($category == "Shipping Documents"){
            $shippingdoc = New ShippingDocuments;
            $shippingdoc->name = $addon_name;
            $shippingdoc->description = $description;
            $shippingdoc->price = $price;
            $shippingdoc->created_by = Auth::id();

            $latestCode = ShippingDocuments::withTrashed()->orderBy('id', 'desc')->first();
            info("shipping documnets");
            info($latestCode);
            $code = $this->generateUUID($latestCode, $category);
            $shippingdoc->code = $code;

            $shippingdoc->save();
        }
        else if($category == "Certificates"){
            $shippingcert = New ShippingCertification;
            $shippingcert->name = $addon_name;
            $shippingcert->description = $description;
            $shippingcert->price = $price;
            $shippingcert->created_by = Auth::id();

            $latestCode = ShippingCertification::withTrashed()->orderBy('id', 'desc')->first();
            $code = $this->generateUUID($latestCode, $category);
            $shippingcert->code = $code;

            $shippingcert->save();
        }
        else{
            $shippingother = New OtherLogisticsCharges;
            $shippingother->name = $addon_name;
            $shippingother->description = $description;
            $shippingother->price = $price;
            $shippingother->created_by = Auth::id();

            $latestCode = OtherLogisticsCharges::withTrashed()->orderBy('id', 'desc')->first();
            $code = $this->generateUUID($latestCode, $category);
            $shippingother->code = $code;

            $shippingother->save();
        }

        return redirect()->route('Shipping.index')->with('success', 'Port has been successfully added!');
    }
    public function generateUUID($latestCode, $category) {
        $length = 5;
        $offset = 2;
        if($category == "Shipping") {
          $prefix = 'S-';
        }else if($category == "Shipping Documents") {
            $prefix = 'D-';
        }else if($category == "Certificates") {
            $prefix = 'DP-';
            $length = 6;
            $offset = 3;

        }else{
            $prefix = 'E-';
        }
        if($latestCode){
            $latestShippingCode =  $latestCode->code;

            $latestShippingCodeNumber = substr($latestShippingCode, $offset, $length);
            $newCode =  str_pad($latestShippingCodeNumber + 1, 3, 0, STR_PAD_LEFT);

            $code =  $prefix.$newCode;
        }else{
            $code = $prefix.'001';
        }

        return $code;
    }
    /**
     * Display the specified resource.
     */
    public function show(shipping $shipping)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(shipping $shipping)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, shipping $shipping)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(shipping $shipping)
    {
        //
    }
    public function updateprice(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Update the Price of the Shipping";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $id = $request->input('ids');
        $tableid = $request->input('tableid');
        $price = $request->input('price');
        if($tableid == "dtBasicExample1")
        {
            $shipping = Shipping::find($id);
            $shipping->price = $price;
            $shipping->save();
        }
        else if ($tableid == "dtBasicExample2")
        {
            $shipping = ShippingDocuments::find($id);
            $shipping->price = $price;
            $shipping->save();
        }
        else if ($tableid == "dtBasicExample3")
        {
            $shipping = ShippingCertification::find($id);
            $shipping->price = $price;
            $shipping->save();
        }
        else
        {
            $shipping = OtherLogisticsCharges::find($id);
            $shipping->price = $price;
            $shipping->save();
        }
        return response()->json(['tableid' => $tableid]);
    }
    public function openmedium(Builder $builder, $id)
    {
        (new UserActivityController)->createActivity('Open the Shipping Rates');
        // Get shipping data
        $shipping = Shipping::where('shipping_medium_id', $id)->get();
        $shippingmendium = ShippingMedium::find($id);
        // Retrieve vendor names for each shipping record
        $vendorNames = [];
        foreach ($shipping as $record) {
            $vendor = DB::table('suppliers')->where('id', $record->suppliers_id)->first();
            $vendorNames[$record->id] = $vendor->supplier ?? '';
        }
        // Retrieve port names for each shipping record
        $toPortNames = [];
        $fromPortNames = [];
        foreach ($shipping as $record) {
            $toPort = DB::table('master_shipping_ports')->where('id', $record->to_port)->first();
            $fromPort = DB::table('master_shipping_ports')->where('id', $record->from_port)->first();
            $toPortNames[$record->id] = $toPort->name ?? '';
            $fromPortNames[$record->id] = $fromPort->name ?? '';
        }
        if (request()->ajax()) {
            return DataTables::of($shipping)
                ->editColumn('created_by', function ($query) {
                    return $query->CreatedBy->name ?? '';
                })
                ->addColumn('vendor_name', function ($query) use ($vendorNames) {
                    return $vendorNames[$query->id] ?? '';
                })
                ->addColumn('to_port_name', function ($query) use ($toPortNames) {
                    return $toPortNames[$query->id] ?? '';
                })
                ->editColumn('updated_by', function ($query) {
                    $updater = DB::table('users')->where('id', $query->updated_by)->first();
                    return $updater->name ?? '';
                })
                ->addColumn('from_port_name', function ($query) use ($fromPortNames) {
                    return $fromPortNames[$query->id] ?? '';
                })
                ->editColumn('updated_at', function ($query) {
                    return $query->updated_at ? date('d-m-Y', strtotime($query->updated_at)) : '';
                })
                ->addColumn('action', function ($query) {
                    return '<a href="' . route('shipping_medium.shippingrates', ['id' => $query->id]) . '" class="btn btn-sm btn-info">View Details</a>';
                })
                ->rawColumns(['action'])
                ->toJson();
        }
        $html = $builder->columns([
            ['data' => 'to_port_name', 'name' => 'to_port_name', 'title' => 'To Port Name'],
            ['data' => 'from_port_name', 'name' => 'from_port_name', 'title' => 'From Port Name'],
            ['data' => 'price', 'name' => 'price', 'title' => 'Sale Price'],
            ['data' => 'cost_price', 'name' => 'cost_price', 'title' => 'Cost Price'],
            ['data' => 'vendor_name', 'name' => 'vendor_name', 'title' => 'Vendor Name'],
            ['data' => 'updated_by', 'name' => 'updated_by', 'title' => 'Updated By'],
            ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Updated At'],
            ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false],
        ]);
    
        return view('logistics.shipping_rates', compact('html', 'shippingmendium'));
    }    
public function shippingrates (Builder $builder, $id)
{  
    $shippingRates = DB::table('shipping_rates')
        ->where('shipping_charges_id', $id)
        ->get();
        $shipping = Shipping::find($id);
        $to_port = MasterShippingPorts::find($shipping->to_port);
        $from_port = MasterShippingPorts::find($shipping->from_port);
    if (request()->ajax()) {
        return DataTables::of($shippingRates)
            ->editColumn('suppliers_id', function ($query) {
                $vendor = DB::table('suppliers')->where('id', $query->suppliers_id)->first();
                return $vendor->supplier ?? '';
            })
            ->editColumn('created_by', function ($query) {
                $creator = DB::table('users')->where('id', $query->created_by)->first();
                return $creator->name ?? '';
            })
            ->editColumn('updated_by', function ($query) {
                $updater = DB::table('users')->where('id', $query->updated_by)->first();
                return $updater->name ?? '';
            })
            ->editColumn('created_at', function ($query) {
                return date('d-m-Y', strtotime($query->created_at));
            })
            ->editColumn('updated_at', function ($query) {
                return date('d-m-Y', strtotime($query->updated_at));
            })          
            ->editColumn('status', function ($query) {
                if ($query->status == 'Selected') {
                    return '<span class="badge badge-success">Selected</span>';
                } else {
                    $button = '<button class="btn btn-primary btn-select" data-id="' . $query->id . '">Select</button>';
                    return $button;
                }
            })
            ->addColumn('action', function ($query) {
                $buttons = '<button class="btn btn-warning btn-open-modal" data-id="' . $query->id . '">+</button>';
                return $buttons;
            })
            ->rawColumns(['status', 'action'])
            ->toJson();
    }
    $html = $builder->columns([
        ['data' => 'suppliers_id', 'name' => 'suppliers_id', 'title' => 'Vendor Name'],
        ['data' => 'cost_price', 'name' => 'cost_price', 'title' => 'Cost Price'],
        ['data' => 'selling_price', 'name' => 'selling_price', 'title' => 'Selling Price'],
        ['data' => 'updated_by', 'name' => 'updated_by', 'title' => 'Updated By'],
        ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Updated At'],
        ['data' => 'created_by', 'name' => 'created_by', 'title' => 'Created By'],
        ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At'],
        ['data' => 'status', 'name' => 'status', 'title' => 'Status'],
        ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false],
    ]);
return view('logistics.shipping_vendor_rates', compact('html', 'id', 'shipping', 'to_port', 'from_port'));
}
    public function openmediumcreate ($id)
    {
        $shippingmedium = ShippingMedium::find($id);
        $allPorts = MasterShippingPorts::all();
        $ports = $allPorts->pluck('name', 'id');
        return view('logistics.createportrates' , compact('shippingmedium', 'ports'));
    }
    public function storeportrates (Request $request)
    {
        $fromPortId = $request->input('from_port');
        $toPortId = $request->input('to_port'); 
        $id = $request->input('id');
        $shippingrates = New Shipping();
        $shippingrates->created_by = Auth::id();
        $shippingrates->to_port = $toPortId;
        $shippingrates->from_port = $fromPortId;
        $shippingrates->shipping_medium_id = $id;
        $shippingrates->save();
        return redirect()->route('shipping_medium.openmedium', ['id' => $id])
        ->with('success', 'Shipping Locations saved successfully');
    }
    public function shippingratescreate ($id)
    {
        $shipping = Shipping::find($id);
        $to_port = MasterShippingPorts::find($shipping->to_port);
        $from_port = MasterShippingPorts::find($shipping->from_port);
        $existingVendorIds = ShippingRate::where('shipping_charges_id', $id)->pluck('suppliers_id')->toArray();
        $vendors = Supplier::whereHas('vendorCategories', function ($query) {
            $query->where('category', 'Shipping');
        })
        ->whereNotIn('id', $existingVendorIds)
        ->get();
        return view('logistics.createvendorrates' , compact('shipping', 'vendors', 'to_port', 'from_port'));
    }
    public function storevendorrates(Request $request)
    {
        $shipping_charges_id = $request->input('id');
        $uniqueVendorIds = array_unique($request->vendor_id);
        if(count($uniqueVendorIds) < count($request->vendor_id)) {
            return redirect()->back()->with('error', 'Vendor cannot be repeated');
        }
        foreach ($request->vendor_id as $key => $value) {
            ShippingRate::create([
                'suppliers_id' => $value,
                'cost_price' => $request->cost_price[$key],
                'selling_price' => $request->selling_price[$key],
                'shipping_charges_id' => $shipping_charges_id,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);
        }
        return redirect()->route('shipping_medium.shippingrates', ['id' => $shipping_charges_id])
            ->with('success', 'Shipping rates saved successfully');
    }    
    public function selectShippingRate(Request $request, $id)
    {
        $shippingId = $request->input('shipping_id');
        ShippingRate::where('shipping_charges_id', $shippingId)
                ->where('id', '!=', $id)
                ->update(['status' => 'Not Selected']);
        $selectedRate = ShippingRate::find($id);
        if ($selectedRate) {
            $selectedRate->update(['status' => 'Selected']);
        }
    $shippingRate = ShippingRate::find($id);
    $shippingCharges = Shipping::find($shippingId);
    if ($shippingRate && $shippingCharges) {
        $shippingCharges->update([
            'cost_price' => $shippingRate->cost_price,
            'price' => $shippingRate->selling_price,
            'updated_by' => Auth::id(),
            'suppliers_id' => $shippingRate->suppliers_id,
        ]);
    }
        return response()->json(['message' => 'Shipping rate selection updated successfully']);
    }
    public function getShippingRateDetails($id)
{
    $shippingRate = DB::table('shipping_rates')->find($id);

    return response()->json([
        'currentCostPrice' => $shippingRate->cost_price,
        'currentSellingPrice' => $shippingRate->selling_price,
    ]);
}
public function updateShippingRate(Request $request)
{
    $id = $request->input('id');
    $newCostPrice = $request->input('cost_price');
    $newSellingPrice = $request->input('selling_price');
    $shippingRate = ShippingRate::find($id);
    if (!is_null($newCostPrice)) {
        $shippingRate->cost_price = $newCostPrice;
        $shippingRate->updated_by = Auth::id();
    }
    if (!is_null($newSellingPrice)) {
        $shippingRate->selling_price = $newSellingPrice;
        $shippingRate->updated_by = Auth::id();
    }
    $shippingRate->save();
    if($shippingRate->status == "Selected")
    {
        $shippingcharges = Shipping::find($shippingRate->shipping_charges_id); 
        if (!is_null($newCostPrice)) 
        {
            $shippingcharges->cost_price = $newCostPrice;
        }
        if (!is_null($newSellingPrice)) 
        {
            $shippingcharges->price = $newSellingPrice;
        }
        $shippingcharges->save();
    }
    return response()->json(['message' => 'Shipping rate updated successfully']);
}
    }
