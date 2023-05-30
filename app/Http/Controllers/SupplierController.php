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
use App\Models\Addon;
use App\Models\Brand;
use App\Models\MasterModelLines;
use App\Models\SupplierAddonTemp;
use App\Models\SupplierType;
use App\Imports\SupplierAddonImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\HeadingRowImport;


class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::with('supplierAddons.supplierAddonDetails','paymentMethods.PaymentMethods','supplierTypes')->get();
        return view('suppliers.index',compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $paymentMethods = DB::table('payment_methods')->get();
        $addons = AddonDetails::select('id','addon_code','addon_id')->with('AddonName')->get();
        return view('suppliers.create',compact('paymentMethods','addons'));
    }

    /**
     * Store a newly created resource in storage.
     */
 
    public function store(Request $request)
    {
        // dd($request->all());
        // dd($request->contact_number['full']);
        $payment_methods_id = $addon_id = [];
        $authId = Auth::id();
        $validator = Validator::make($request->all(), [
            'supplier' => 'required',
            // 'contact_person' => 'required',
            // 'contact_number' => 'required',
            //  'alternative_contact_number' => 'required',
            // 'email' => 'required',
            // 'person_contact_by' => 'required',
            // 'supplier_type' => 'required',
            'is_primary_payment_method' => 'required',
            // 'model' => 'required',
            // 'addon_id' => 'required',
            // 'payment_methods_id' => 'required',
            'supplier_types' => 'required',
            // 'contact_number' => 'required_without:alternative_contact_number',
            // 'alternative_contact_number' => 'required_without:contact_number', 
        ]);
       
        if ($validator->fails()) 
        {
            // dd('hi');
            return redirect(route('suppliers.create'))->withInput()->withErrors($validator);
        }
        else 
        { 
            $input = $request->all();

            $input['contact_number'] = $request->contact_number['full'];
            $input['alternative_contact_number'] = $request->alternative_contact_number['full'];
            $input['created_by'] = $authId;
            $suppliers = Supplier::create($input);
            if($request->supplier_types != null)
            {
                if(count($request->supplier_types) > 0)
                {
                    $supplier_typeData['supplier_id'] = $suppliers->id;
                    $supplier_typeData['created_by'] = $authId; 
                    foreach($request->supplier_types as $supplier_typeData1)
                    {                    
                        $supplier_typeData['supplier_type'] = $supplier_typeData1; 
                        $supplier_typeDataCreate = SupplierType::create($supplier_typeData);  
                    }
                }
            }
            $payment_methods['supplier_id'] = $suppliers->id;
            $payment_methods['created_by'] = $authId;         
            $payment_methods['payment_methods_id'] = $request->is_primary_payment_method;
            $payment_methods['is_primary_payment_method'] = 'yes'; 
            $paymentMethods = SupplierAvailablePayments::create($payment_methods);
            $payment_methods_id = $request->payment_methods_id;
            if($payment_methods_id != null)
            {
                if(count($payment_methods_id) > 0)
                {
                    foreach($payment_methods_id as $payment_methods_id)
                    {
                        $payment_methods['payment_methods_id'] = $payment_methods_id;
                            $payment_methods['is_primary_payment_method'] = 'no'; 
                        $paymentMethods = SupplierAvailablePayments::create($payment_methods);  
                    }
                }
            }
            $supplier_addon['supplier_id'] = $suppliers->id;
            $isupplier_addonnput['created_by'] = $authId;
            $addon_id = $request->addon_id;
            if($addon_id != NULL)
            {
                if(count($addon_id) > 0)
                {
                    foreach($addon_id as $addon_id)
                    {
                        $supplier_addon['addon_details_id'] = $addon_id;
                        $supplierAddon1 = SupplierAddons::create($supplier_addon);
                    }
                }
            }
            $supAdd['supplier_id'] = $suppliers->id;
            $supAdd['created_by'] = $authId;
            if($request->activeTab == 'uploadExcel')
            {
                // dd($request->file('file'));
                if($request->file('file'))
                {
                    $headings = (new HeadingRowImport)->toArray($request->file('file'));  
                    if(count($headings) > 0)
                    {
                        foreach($headings[0] as $heading)
                        {
                            if(in_array('addon_code', $heading) && in_array('currency', $heading) && in_array('purchase_price', $heading))
                            {
                                Excel::import(new SupplierAddonImport,request()->file('file'));
                                $supplierAddons = SupplierAddonTemp::all();
                                foreach($supplierAddons as $supplierAddon)
                                {
                                    $addonId = AddonDetails::where('addon_code',$supplierAddon->addon_code)->select('id')->first();
                                    $supAdd['addon_details_id'] = $addonId->id;
                                    if($supplierAddon->currency == 'AED')
                                    {
                                        $supAdd['purchase_price_aed'] = $supplierAddon->purchase_price;
                                    }
                                    elseif($supplierAddon->currency == 'USD')
                                    {
                                        $supAdd['purchase_price_usd'] = $supplierAddon->purchase_price;
                                        $supAdd['purchase_price_aed'] = $supplierAddon->purchase_price * 3.6725;
                                    }
                                    $suppliers = SupplierAddons::create($supAdd);
                                    // $supplierAddon->delete();
                                }
                            }
                            else
                            {
                                dd("Uploading excel headings should be addon_code , currency and purchase_price");
                                // $errorMsg = "Uploading excel headings should be addon_code , currency and purchase_price";
                                // return redirect(route('suppliers.create'))->withInput()->withErrors($errorMsg);
                            }
                        }
                    }
                }
            }
            elseif($request->activeTab == 'addSupplierDynamically')
            {
                if(count($request->supplierAddon) > 0)
                {
                    foreach($request->supplierAddon as $supAddon)
                    {
                        if($supAddon['addon_purchase_price_in_usd'] != '' OR $supAddon['addon_purchase_price'] != '')
                        {
                            $supAdd['currency'] = $supAddon['currency'];
                            if($supAddon['currency'] == 'AED')
                            {
                                $supAdd['purchase_price_aed'] = $supAddon['addon_purchase_price'];
                            }
                            elseif($supAddon['currency'] == 'USD')
                            {
                                $supAdd['purchase_price_usd'] = $supAddon['addon_purchase_price_in_usd'];
                                $supAdd['purchase_price_aed'] = $supAddon['addon_purchase_price_in_usd'] * 3.6725;
                            }
                            if(count($supAddon['addon_id']) > 0)
                            {
                                foreach($supAddon['addon_id'] as $addon_code)
                                {
                                    $supAdd['addon_details_id'] = $addon_code;
                                    $suppliers = SupplierAddons::create($supAdd);
                                }
                            }
                        } 
                    }
                }
            }
            return redirect()->route('suppliers.index')
                             ->with('success','Addon created successfully');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        $addon1 = $addons = $supplierTypes = '';
        $primaryPaymentMethod = SupplierAvailablePayments::where('supplier_id',$supplier->id)->where('is_primary_payment_method','yes')->with('PaymentMethods')->first();
        $otherPaymentMethods = SupplierAvailablePayments::where('supplier_id',$supplier->id)->where('is_primary_payment_method','no')->with('PaymentMethods')->get();
        $supplierAddonId = SupplierAddons::where('supplier_id',$supplier->id)->pluck('addon_details_id');
        $supplierTypes = SupplierType::where('supplier_id',$supplier->id)->get();
        if(count($supplierAddonId) > 0)
        {
            $addon1 = AddonDetails::whereIn('id',$supplierAddonId)->with('AddonName','AddonTypes.brands','AddonTypes.modelLines')->orderBy('id', 'ASC')->get();
            $addons = DB::table('addon_details')
                        ->join('addons','addons.id','addon_details.addon_id')
                        ->join('addon_types','addon_types.addon_details_id','addon_details.id')
                        ->join('brands','brands.id','addon_types.brand_id')
                        ->join('master_model_lines','master_model_lines.id','addon_types.model_id')
                        ->whereIn('addon_details.id',$supplierAddonId)
                        ->select('addons.name','addon_details.id as addon_details_table_id','addon_details.addon_id','addon_details.addon_code','addon_details.purchase_price','addon_details.selling_price','addon_details.payment_condition','addon_details.currency',
                        'addon_details.lead_time','addon_details.additional_remarks','addon_details.image','addon_details.is_all_brands','addon_details.status','addon_types.brand_id','addon_types.model_id','addon_types.is_all_model_lines','brands.brand_name',
                        'master_model_lines.model_line')
                        ->orderBy('addon_details.id','ASC')
                        ->get();
        }
        //  dd($supplierTypes);
        return view('suppliers.show',compact('supplier','primaryPaymentMethod','otherPaymentMethods','addon1','addons','supplierTypes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        $paymentMethods = DB::table('payment_methods')->get();
        $primaryPaymentMethod = SupplierAvailablePayments::where('supplier_id',$supplier->id)->where('is_primary_payment_method','yes')->first();
        $otherPaymentMethods = SupplierAvailablePayments::where('supplier_id',$supplier->id)->where('is_primary_payment_method','no')->pluck('payment_methods_id');
        $array = json_decode($otherPaymentMethods);
        
        // $supplierTypes = json_decode($supplierType);
        // dd($array);
        $addons = AddonDetails::select('id','addon_code','addon_id')->with('AddonName')->get();
        return view('suppliers.edit',compact('supplier','primaryPaymentMethod','otherPaymentMethods','addons','paymentMethods','array'));
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
    public function supplierAddonExcelValidation(Request $request)
    {
        dd($request);
        if($request->file)
                {
                    $headings = (new HeadingRowImport)->toArray($request->file);  dd($headings);
                    if(count($headings) > 0)
                    {
                        foreach($headings[0] as $heading)
                        {
                            if(in_array('addon_code', $heading) && in_array('currency', $heading) && in_array('purchase_price', $heading))
                            {
                                Excel::import(new SupplierAddonImport,$request->file);
                                // $supplierAddons = SupplierAddonTemp::all();
                                // foreach($supplierAddons as $supplierAddon)
                                // {
                                //     $addonId = AddonDetails::where('addon_code',$supplierAddon->addon_code)->select('id')->first();
                                //     $supAdd['addon_details_id'] = $addonId->id;
                                //     if($supplierAddon->currency == 'AED')
                                //     {
                                //         $supAdd['purchase_price_aed'] = $supplierAddon->purchase_price;
                                //     }
                                //     elseif($supplierAddon->currency == 'USD')
                                //     {
                                //         $supAdd['purchase_price_usd'] = $supplierAddon->purchase_price;
                                //         $supAdd['purchase_price_aed'] = $supplierAddon->purchase_price * 3.6725;
                                //     }
                                //     $suppliers = SupplierAddons::create($supAdd);
                                //     // $supplierAddon->delete();
                                // }
                            }
                            else
                            {
                                return response()->json('Uploading excel headings should be addon_code , currency and purchase_price');
                                // dd("Uploading excel headings should be addon_code , currency and purchase_price");
                                // $errorMsg = "Uploading excel headings should be addon_code , currency and purchase_price";
                                // return redirect(route('suppliers.create'))->withInput()->withErrors($errorMsg);
                            }
                        }
                    }
                }
    }
}
