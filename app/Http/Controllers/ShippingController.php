<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use App\Models\ShippingDocuments;
use App\Models\ShippingCertification;
use App\Models\OtherLogisticsCharges;
use App\Models\UserActivities;
use Illuminate\Http\Request;
use Psy\Util\Str;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

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
                $data = Shipping::select([
                    'shipping_charges.id',
                    'shipping_charges.name',
                    'shipping_charges.description',
                    'shipping_charges.price',
                    'shipping_charges.created_by',
                    'shipping_charges.created_at',
                ]);
                $data = $data->groupBy('shipping_charges.id');
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
}
