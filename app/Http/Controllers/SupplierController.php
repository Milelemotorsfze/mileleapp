<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Validator;

use App\Models\AddonDetails;
use App\Models\SupplierAddons;
use App\Models\SupplierAvailablePayments;


class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      
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
        // dd($request->contact_number['full']);
        $authId = Auth::id();
        // $validator = Validator::make($request->all(), [
        //     'supplier' => 'required',
        //     'contact_person' => 'required',
        //     'contact_number' => 'required',
        //      'alternative_contact_number' => 'required',
        //     'email' => 'required',
        //     'person_contact_by' => 'required',
        //     'supplier_type' => 'required',
        //     'is_primary_payment_method' => 'required',
        //     'model' => 'required',
        //     'addon_id' => 'required',
        //     'payment_methods_id' => 'required',
        // ]);
       
        // if ($validator->fails()) 
        // {
        //     // dd('hi');
        //     return redirect(route('addon.create'))->withInput()->withErrors($validator);
        // }
        // else 
        // { 

            $input = $request->all();
            
            $input['contact_number'] = $request->contact_number['full'];
            $input['alternative_contact_number'] = $request->alternative_contact_number['full'];
            // dd($input);
            $input['created_by'] = $authId;
            $suppliers = Supplier::create($input);
            $payment_methods['supplier_id'] = $suppliers->id;
            $payment_methods['created_by'] = $authId;
            foreach($request->payment_methods_id as $payment_methods_id)
            {
                $payment_methods['payment_methods_id'] = $payment_methods_id;
                if($payment_methods['payment_methods_id'] == $request->is_primary_payment_method)
                {
                    $payment_methods['is_primary_payment_method'] = 'yes'; 
                }
                else{
                    $payment_methods['is_primary_payment_method'] = 'no'; 
                }
                $paymentMethods = SupplierAvailablePayments::create($payment_methods);
                
            }
            $supplier_addon['supplier_id'] = $suppliers->id;
            $isupplier_addonnput['created_by'] = $authId;
            foreach($request->addon_id as $addon_id)
            {
                $supplier_addon['addon_details_id'] = $addon_id;
                $supplierAddon = SupplierAddons::create($supplier_addon);
            }
        //     $inputaddontype['created_by'] = $authId;
        //     for($i=0; $i<count($request->brand); $i++)
        //     {
        //         $inputaddontype['brand_id'] = $request->brand[$i];
        //         $inputaddontype['model_id'] = $request->model[$i];
        //         $addon_types = AddonTypes::create($inputaddontype);
        //     }
            return redirect()->route('suppliers.index')
                            ->with('success','Addon created successfully');
        // }
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
