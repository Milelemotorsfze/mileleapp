<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use App\Http\Controllers\UserActivityController;
use App\Models\ShippingDocuments;
use App\Models\ShippingCertification;
use App\Models\OtherLogisticsCharges;
use App\Models\ShippingMedium;
use App\Models\UserActivities;
use App\Models\ShippingRate;
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
        $shipping = New Shipping;
        $shipping->name = $addon_name;
        $shipping->description = $description;
        $shipping->price = $price;
        $shipping->created_by = Auth::id();

        $latestCode = Shipping::withTrashed()->orderBy('id', 'desc')->first();
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

        return redirect()->back()->with("Shipping data Created Successfully.");
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
    // Retrieve cost_price from shipping_rates
    $costPrices = [];
    foreach ($shipping as $record) {
        $shippingRate = DB::table('shipping_rates')
            ->where('shipping_charges_id', $record->shipping_medium_id)
            ->where('status', 'Selected')
            ->first();
        $costPrices[$record->id] = $shippingRate->cost_price ?? '';
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
            ->addColumn('from_port_name', function ($query) use ($fromPortNames) {
                return $fromPortNames[$query->id] ?? '';
            })
            ->addColumn('cost_price', function ($query) use ($costPrices) {
                return $costPrices[$query->id] ?? '';
            })
            ->addColumn('action', function ($query) {
                return '<a href="' . route('shipping_medium.shippingrates', ['id' => $query->shipping_medium_id]) . '" class="btn btn-sm btn-info">View Details</a>';
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
        ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false],
    ]);

    return view('logistics.shipping_rates', compact('html'));
}
public function shippingrates (Builder $builder, $id)
{  
    $shippingRates = DB::table('shipping_rates')
        ->where('shipping_charges_id', $id)
        ->get();
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
                return $query->status == 'Selected' ? '<span class="badge badge-success">Selected</span>' : '<span class="badge badge-danger">Not Selected</span>';
            })
            ->rawColumns(['status'])
            ->toJson();
    }
    $html = $builder->columns([
        ['data' => 'suppliers_id', 'name' => 'suppliers_id', 'title' => 'Vendor Name'],
        ['data' => 'cost_price', 'name' => 'cost_price', 'title' => 'Cost Price'],
        ['data' => 'selling_price', 'name' => 'selling_price', 'title' => 'Selling Price'],
        ['data' => 'created_by', 'name' => 'created_by', 'title' => 'Created By'],
        ['data' => 'updated_by', 'name' => 'updated_by', 'title' => 'Updated By'],
        ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At'],
        ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Updated At'],
        ['data' => 'status', 'name' => 'status', 'title' => 'Status'],
    ]);
return view('logistics.shipping_vendor_rates', compact('html'));
}
}
