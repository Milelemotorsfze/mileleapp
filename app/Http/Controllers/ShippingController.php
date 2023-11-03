<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use App\Models\ShippingDocuments;
use App\Models\ShippingCertification;
use App\Models\OtherLogisticsCharges;
use App\Models\UserActivities;
use Illuminate\Http\Request;
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
                    'shipping_charges.category',
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
                    'shipping_documents.category',
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
                    'shipping_certification.category',
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
        $shipping->category = $addon_name;
        $shipping->description = $description;
        $shipping->price = $price;
        $shipping->created_by = Auth::id();
        $shipping->save();
        }
        else if($category == "Shipping Documents"){
            $shippingdoc = New ShippingDocuments;
            $shippingdoc->category = $addon_name;
            $shippingdoc->description = $description;
            $shippingdoc->price = $price;
            $shippingdoc->created_by = Auth::id();
            $shippingdoc->save();
        }
        else if($category == "Certificates"){
            $shippingcert = New ShippingCertification;
            $shippingcert->category = $addon_name;
            $shippingcert->description = $description;
            $shippingcert->price = $price;
            $shippingcert->created_by = Auth::id();
            $shippingcert->save();
        }
        else{
            $shippingother = New OtherLogisticsCharges;
            $shippingother->name = $addon_name;
            $shippingother->description = $description;
            $shippingother->price = $price;
            $shippingother->created_by = Auth::id();
            $shippingother->save();
        }
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
