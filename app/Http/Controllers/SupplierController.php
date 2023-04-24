<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AddonDetails;
use DB;
use Validator;


class SupplierController extends Controller
{
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
        $paymentMethods = DB::table('payment_methods')->get();
        $addons = AddonDetails::select('id','addon_code')->get();
        return view('suppliers.create',compact('paymentMethods','addons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $authId = Auth::id();
        $validator = Validator::make($request->all(), [
            'supplier' => 'required',
            'contact_person' => 'required',
            'contact_number' => 'required',
            'email' => 'required',
            'person_contact_by' => 'required',
            'supplier_type' => 'required',
            'is_primary_payment_method' => 'required',
            'model' => 'required',
            'addon_id' => 'required',
            'payment_methods_id' => 'required',
        ]);
        
        if ($validator->fails()) 
        {
            // dd($validator);
            return redirect(route('addon.create'))->withInput()->withErrors($validator);
        }
        else 
        {
// dd('hiii');
        //     $fileName = auth()->id() . '_' . time() . '.'. $request->image->extension();  
        //     $type = $request->image->getClientMimeType();
        //     $size = $request->image->getSize();
        //     $request->image->move(public_path('addon_image'), $fileName);
        //     $input = $request->all();
        //     $input['addon_id'] = $request->addon_name;
        //     $input['currency'] = 'AED';
        //     $input['created_by'] = $authId;
        //     $input['image'] = $fileName;
        //     $lastAddonCode = AddonDetails::orderBy('id', 'desc')->first()->addon_code;
        //     $lastAddonCodeNumber = substr($lastAddonCode, 1, 5);
        //     $newAddonCodeNumber =  $lastAddonCodeNumber+1;
        //     $newAddonCode = "P".$newAddonCodeNumber;
        //     $input['addon_code'] = $newAddonCode;
        //     $addon_details = AddonDetails::create($input);
        //     $inputaddontype['addon_details_id'] = $addon_details->id;
        //     $inputaddontype['created_by'] = $authId;
        //     for($i=0; $i<count($request->brand); $i++)
        //     {
        //         $inputaddontype['brand_id'] = $request->brand[$i];
        //         $inputaddontype['model_id'] = $request->model[$i];
        //         $addon_types = AddonTypes::create($inputaddontype);
        //     }
        //     return redirect()->route('addon.index')
        //                     ->with('success','Addon created successfully');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        //
    }
}
