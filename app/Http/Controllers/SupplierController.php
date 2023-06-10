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
                        ->select('addons.name','addon_details.id as addon_details_table_id','addon_details.addon_id','addon_details.addon_code','addon_details.payment_condition',
                        'addon_details.lead_time','addon_details.additional_remarks','addon_details.image','addon_details.is_all_brands','addon_details.status','addon_types.brand_id','addon_types.model_id','addon_types.is_all_model_lines','brands.brand_name',
                        'master_model_lines.model_line')
                        ->orderBy('addon_details.id','ASC')
                        ->get();
        }
        return view('suppliers.show',compact('supplier','primaryPaymentMethod','otherPaymentMethods','addon1','addons','supplierTypes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        $paymentMethods = DB::table('payment_methods')->get();
        $primaryPaymentMethod = SupplierAvailablePayments::where('supplier_id',$supplier->id)->where('is_primary_payment_method','yes')->first();
        $otherPaymentMethods = SupplierAvailablePayments::where('supplier_id',$supplier->id)
                                                            ->where('is_primary_payment_method','no')
                                                            ->pluck('payment_methods_id');
        $array = json_decode($otherPaymentMethods);
        $supplierType = SupplierType::where('supplier_id',$supplier->id)->pluck('supplier_type');
        $supplierTypes = json_decode($supplierType);
        $supplierAddons = SupplierAddons::where('supplier_id',$supplier->id)->pluck('addon_details_id');
        $addons = AddonDetails::whereNotIn('id',$supplierAddons)->select('id','addon_code','addon_id')->with('AddonName')->get();
        return view('suppliers.edit',compact('supplier','primaryPaymentMethod','otherPaymentMethods','addons','paymentMethods','array','supplierTypes'));
    }
    public function delete($id)
    {
        Supplier::find($id)->delete();
        return redirect()->route('suppliers.index')
                        ->with('success','Suppliers deleted successfully');
    }
    public function makeActive($id)
    {
       $user = Supplier::find($id);
       $user->status = 'active';
       $user->update();
       return redirect()->route('suppliers.index')
                       ->with('success','Supplier updated successfully');
    }
    public function updateStatus($id)
    {
        $user = Supplier::find($id);
        $user->status = 'inactive';
        $user->update();
        return redirect()->route('suppliers.index')
                        ->with('success','Supplier updated successfully');
    }
    public function supplierAddonExcelValidation(Request $request)
    {
        if($request->file)
                {
                    $headings = (new HeadingRowImport)->toArray($request->file);  
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
                            }
                        }
                    }
                }
    }
    public function store(Request $request)
    {
        $payment_methods_id = $addon_id = [];
        $authId = Auth::id();
        $validator = Validator::make($request->all(), [
            'supplier' => 'required',
            'is_primary_payment_method' => 'required',
            'supplier_types' => 'required',
        ]);
        if ($validator->fails()) 
        {
            return redirect(route('suppliers.create'))->withInput()->withErrors($validator);
        }
        else 
        { 
            if($request->activeTab == 'uploadExcel')
            {
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
                                $dataError = [];
                                $rows = SupplierAddonTemp::all();
                                $existingAddon = [];
                                for ($i=0; $i< count($rows); $i++) 
                                {
                                    $currencyError = $priceErrror = $addonError = '';
                                    if($rows[$i]['currency'] OR $rows[$i]['purchase_price'] OR $rows[$i]['addon_code'])
                                    {
                                        if($rows[$i]['currency'] == '')
                                        {
                                            $currencyError = "Currency field is required";
                                        }
                                        elseif(!in_array(strtoupper($rows[$i]['currency']), ['AED','USD']))
                                        {
                                            $currencyError = "currency should be  AED or USD";
                                        } 
                                        if($rows[$i]['purchase_price'] == '') 
                                        {
                                            $priceErrror = "Purchase price field is required";
                                        }  
                                        elseif(!is_numeric($rows[$i]['purchase_price']))
                                        {   
                                            $priceErrror = "Purchase price should be a number";
                                        }
                                        if($rows[$i]['addon_code'] == '')
                                        {
                                            $addonError = "Addon code field is required";
                                        }
                                        elseif(in_array(strtoupper($rows[$i]['addon_code']),$existingAddon))
                                        {
                                            $addonError = "This addon code is duplicate";
                                        }
                                        else
                                        {
                                            $addonId = AddonDetails::where('addon_code',$rows[$i]['addon_code'])->select('id')->first();
                                            array_push($existingAddon, $rows[$i]['addon_code']);
                                            if($addonId == '')
                                            {
                                                $addonError = "This addon code is not exising in the system";
                                            }
                                        }
                                        if($currencyError != '' OR $priceErrror != '' OR $addonError != '')
                                        {
                                            array_push($dataError, ["addon_code" => $rows[$i]['addon_code'], "addonError" => $addonError,"currency" => $rows[$i]['currency'], "currencyError" => $currencyError, "purchase_price" => $rows[$i]['purchase_price'], "priceErrror" => $priceErrror]); 
                                        }
                                        $rows[$i]->delete();
                                    }  
                                    else
                                    {
                                        $rows[$i]->delete();
                                    }                                  
                                }
                                if(count($dataError) > 0)
                                {
                                    $data['dataError'] = $dataError;
                                    return response()->json(['success' => true,'data' => $data], 200);
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
                                                        }
                                                    }
                                                    else
                                                    {
                                                        dd("Uploading excel headings should be addon_code , currency and purchase_price");
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    $data['successStore'] = true;
                                    return response()->json(['success' => true,'data' => $data], 200);
                                }
                            }
                            else
                            {
                                $data['headingError'] = "Uploading excel headings should be addon_code , currency and purchase_price";
                                return response()->json(['success' => true,'data' => $data], 200);;
                            }
                        }
                    }
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
                                        }
                                    }
                                    else
                                    {
                                        dd("Uploading excel headings should be addon_code , currency and purchase_price");
                                    }
                                }
                            }
                        }
                    }
                    $data['successStore'] = true;
                    return response()->json(['success' => true,'data' => $data], 200);
                }
            }
            elseif($request->activeTab == 'addSupplierDynamically')
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
                $supAdd['supplier_id'] = $suppliers->id;
                $supAdd['created_by'] = $authId;
                if($request->activeTab == 'addSupplierDynamically')
                {
                    if(count($request->supplierAddon) > 0)
                    {
                        $addonAlredyExist = [];
                        foreach($request->supplierAddon as $supAddon)
                        {
                            if($supAddon['addon_purchase_price_in_usd'] != '' OR $supAddon['addon_purchase_price'] != '')
                            {
                                if($supAddon['currency'] != '' AND $supAddon['addon_id'] != '')
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
                                            if(!in_array($addon_code, $addonAlredyExist))
                                            {
                                                $supAdd['addon_details_id'] = $addon_code;
                                                $suppliers = SupplierAddons::create($supAdd);
                                                array_push($addonAlredyExist, $addon_code);
                                            }
                                        }
                                    }
                                }
                            
                            } 
                        }
                    }
                }
                $data['successStore'] = true;
                return response()->json(['success' => true,'data' => $data], 200);
            }
        
        }
    }
    public function updateDetails(Request $request)
    {
        $payment_methods_id = $addon_id = [];
        $authId = Auth::id();
        $validator = Validator::make($request->all(), [
            'supplier' => 'required',
            'is_primary_payment_method' => 'required',
            'supplier_types' => 'required',
        ]);
        if ($validator->fails()) 
        {
            return redirect(route('suppliers.create'))->withInput()->withErrors($validator);
        }
        else 
        { 
            if($request->activeTab == 'uploadExcel')
            { 
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
                                $dataError = [];
                                $rows = SupplierAddonTemp::all();                                
                                $existingAddon = [];
                                for ($i=0; $i< count($rows); $i++) 
                                {
                                    $currencyError = $priceErrror = $addonError = '';
                                    if($rows[$i]['currency'] OR $rows[$i]['purchase_price'] OR $rows[$i]['addon_code'])
                                    {
                                        if($rows[$i]['currency'] == '')
                                        {
                                            $currencyError = "Currency field is required";
                                        }
                                        elseif(!in_array(strtoupper($rows[$i]['currency']), ['AED','USD']))
                                        {
                                            $currencyError = "currency should be  AED or USD";
                                        } 
                                        if($rows[$i]['purchase_price'] == '') 
                                        {
                                            $priceErrror = "Purchase price field is required";
                                        }  
                                        elseif(!is_numeric($rows[$i]['purchase_price']))
                                        {   
                                            $priceErrror = "Purchase price should be a number";
                                        }
                                        if($rows[$i]['addon_code'] == '')
                                        {
                                            $addonError = "Addon code field is required";
                                        }
                                        elseif(in_array(strtoupper($rows[$i]['addon_code']),$existingAddon))
                                        {
                                            $addonError = "This addon code is duplicate";
                                        }
                                        else
                                        {
                                            $addonId = AddonDetails::where('addon_code',$rows[$i]['addon_code'])->select('id')->first();
                                            array_push($existingAddon, $rows[$i]['addon_code']);
                                            if($addonId == '')
                                            {
                                                $addonError = "This addon code is not exising in the system";
                                            }
                                            else
                                            {
                                                $supAdd = SupplierAddons::where('addon_details_id',$addonId->id)->where('supplier_id',$request->supplier_id)->select('id')->first();
                                                if($supAdd)
                                                {
                                                    $addonError = "This addon code is already assigned for this supplier";
                                                }
                                            }
                                        }
                                        if($currencyError != '' OR $priceErrror != '' OR $addonError != '')
                                        {
                                            array_push($dataError, ["addon_code" => $rows[$i]['addon_code'], "addonError" => $addonError,"currency" => $rows[$i]['currency'], "currencyError" => $currencyError, "purchase_price" => $rows[$i]['purchase_price'], "priceErrror" => $priceErrror]); 
                                        }
                                        $rows[$i]->delete();
                                    }  
                                    else
                                    {
                                        $rows[$i]->delete();
                                    }                                  
                                }
                                if(count($dataError) > 0)
                                {
                                    $data['dataError'] = $dataError;
                                    return response()->json(['success' => true,'data' => $data], 200);
                                }
                                else
                                {
                                    $input = $request->all();
                                    $suppliers = Supplier::find($request->supplier_id);
                                    $input['contact_number'] = $request->contact_number['full'];
                                    $input['alternative_contact_number'] = $request->alternative_contact_number['full'];
                                    $input['updated_by'] = $authId;
                                    $suppliers->update($input);
                                    if($request->supplier_types != null)
                                    {
                                        if(count($request->supplier_types) > 0)
                                        {
                                            $existingSupplierTypes = SupplierType::where('supplier_id',$request->supplier_id)->pluck('supplier_type');
                                            $existingSupplierTypes = json_decode($existingSupplierTypes);
                                            $supplier_typeData['supplier_id'] = $suppliers->id;
                                            $supplier_typeData['updated_by'] = $authId;
                                            foreach($request->supplier_types as $supplier_typeData1)
                                            { 
                                                if(!in_array($supplier_typeData1,$existingSupplierTypes)) 
                                                {
                                                    $supplier_typeData['supplier_type'] = $supplier_typeData1; 
                                                    $supplier_typeDataCreate = SupplierType::create($supplier_typeData); 
                                                }                                                              
                                            }
                                            foreach($existingSupplierTypes as $existingSupplierTypes1)
                                            {
                                                if(!in_array($existingSupplierTypes1,$request->supplier_types)) 
                                                {
                                                    $deleSupType = SupplierType::where('supplier_id',$request->supplier_id)->where('supplier_type',$existingSupplierTypes1)->first();
                                                    if($deleSupType)
                                                    {
                                                        $deleSupType->delete();
                                                    }
                                                }   
                                            }
                                        }
                                    }
                                    $paymentMethodsUpdate = SupplierAvailablePayments::where('is_primary_payment_method','yes')->where('supplier_id',$request->supplier_id)->first();
                                    $payment_methods['supplier_id'] = $request->supplier_id;
                                    $payment_methods['updated_by'] = $authId;
                                    $payment_methods['payment_methods_id'] = $request->is_primary_payment_method;
                                    $payment_methods['is_primary_payment_method'] = 'yes';
                                    $paymentMethodsUpdate->update($payment_methods);
                                    $payment_methods_id = $request->payment_methods_id;
                                    if($payment_methods_id != null)
                                    {
                                        if(count($payment_methods_id) > 0)
                                        {
                                            $existingPaymentMethods = SupplierAvailablePayments::where('supplier_id',$request->supplier_id)->where('is_primary_payment_method','no')->pluck('payment_methods_id');
                                            $existingPaymentMethods = json_decode($existingPaymentMethods);
                                            $paymentMethodsUpdate1['supplier_id'] = $request->supplier_id;
                                            $paymentMethodsUpdate1['updated_by'] = $authId;
                                            foreach($payment_methods_id as $payment_methods_id1)
                                            { 
                                                if(!in_array($payment_methods_id1,$existingPaymentMethods)) 
                                                {
                                                    $paymentMethodsUpdate1['payment_methods_id'] = $payment_methods_id1;                           
                                                    $paymentMethodsUpdate1['is_primary_payment_method'] = 'no'; 
                                                    $supplier_typeDataCreate = SupplierAvailablePayments::create($paymentMethodsUpdate1); 
                                                }                                                              
                                            }
                                            foreach($existingPaymentMethods as $existingPaymentMethods1)
                                            {

                                                if(!in_array($existingPaymentMethods1,$payment_methods_id)) 
                                                {
                                                    $delSupPayMet = SupplierAvailablePayments::where('supplier_id',$request->supplier_id)->where('payment_methods_id',$existingPaymentMethods1)->where('is_primary_payment_method','no')->first();
                                                    if($delSupPayMet)
                                                    {
                                                        $delSupPayMet->delete();
                                                    }
                                                }   
                                            }
                                        }
                                    }
                                    $supplier_addon['supplier_id'] = $suppliers->id;
                                    $isupplier_addonnput['updated_by'] = $authId;
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
                                    $supAdd['updated_by'] = $authId;
                                    if($request->activeTab == 'uploadExcel')
                                    {
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
                                                        }
                                                    }
                                                    else
                                                    {
                                                        dd("Uploading excel headings should be addon_code , currency and purchase_price");
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    $data['successStore'] = true;
                                    return response()->json(['success' => true,'data' => $data], 200);
                                }
                            }
                            else
                            {
                                $data['headingError'] = "Uploading excel headings should be addon_code , currency and purchase_price";
                                return response()->json(['success' => true,'data' => $data], 200);;
                            }
                        }
                    }
                }
                else
                {
                    $input = $request->all();
                    $suppliers = Supplier::find($request->supplier_id);
                    $input['contact_number'] = $request->contact_number['full'];
                    $input['alternative_contact_number'] = $request->alternative_contact_number['full'];
                    $input['updated_by'] = $authId;
                    $suppliers->update($input);
                    if($request->supplier_types != null)
                    {
                        if(count($request->supplier_types) > 0)
                        {
                            $existingSupplierTypes = SupplierType::where('supplier_id',$request->supplier_id)->pluck('supplier_type');
                            $existingSupplierTypes = json_decode($existingSupplierTypes);
                            $supplier_typeData['supplier_id'] = $suppliers->id;
                            $supplier_typeData['updated_by'] = $authId;
                            foreach($request->supplier_types as $supplier_typeData1)
                            { 
                                if(!in_array($supplier_typeData1,$existingSupplierTypes)) 
                                {
                                    $supplier_typeData['supplier_type'] = $supplier_typeData1; 
                                    $supplier_typeDataCreate = SupplierType::create($supplier_typeData); 
                                }                                                              
                            }
                            foreach($existingSupplierTypes as $existingSupplierTypes1)
                            {
                                if(!in_array($existingSupplierTypes1,$request->supplier_types)) 
                                {
                                    $deleSupType = SupplierType::where('supplier_id',$request->supplier_id)->where('supplier_type',$existingSupplierTypes1)->first();
                                    if($deleSupType)
                                    {
                                        $deleSupType->delete();
                                    }
                                }   
                            }
                        }
                    }
                    $paymentMethodsUpdate = SupplierAvailablePayments::where('is_primary_payment_method','yes')->where('supplier_id',$request->supplier_id)->first();
                    $payment_methods['supplier_id'] = $request->supplier_id;
                    $payment_methods['updated_by'] = $authId;
                    $payment_methods['payment_methods_id'] = $request->is_primary_payment_method;
                    $payment_methods['is_primary_payment_method'] = 'yes';
                    $paymentMethodsUpdate->update($payment_methods);
                    $payment_methods_id = $request->payment_methods_id;
                    if($payment_methods_id != null)
                    {
                        if(count($payment_methods_id) > 0)
                        {
                            $existingPaymentMethods = SupplierAvailablePayments::where('supplier_id',$request->supplier_id)->where('is_primary_payment_method','no')->pluck('payment_methods_id');
                            $existingPaymentMethods = json_decode($existingPaymentMethods);
                            $paymentMethodsUpdate1['supplier_id'] = $request->supplier_id;
                            $paymentMethodsUpdate1['updated_by'] = $authId;
                            foreach($payment_methods_id as $payment_methods_id1)
                            { 
                                if(!in_array($payment_methods_id1,$existingPaymentMethods)) 
                                {
                                    $paymentMethodsUpdate1['payment_methods_id'] = $payment_methods_id1;                           
                                    $paymentMethodsUpdate1['is_primary_payment_method'] = 'no'; 
                                    $supplier_typeDataCreate = SupplierAvailablePayments::create($paymentMethodsUpdate1); 
                                }                                                              
                            }
                            foreach($existingPaymentMethods as $existingPaymentMethods1)
                            {
    
                                if(!in_array($existingPaymentMethods1,$payment_methods_id)) 
                                {
                                    $delSupPayMet = SupplierAvailablePayments::where('supplier_id',$request->supplier_id)->where('payment_methods_id',$existingPaymentMethods1)->where('is_primary_payment_method','no')->first();
                                    if($delSupPayMet)
                                    {
                                        $delSupPayMet->delete();
                                    }
                                }   
                            }
                        }
                    }
                    $supplier_addon['supplier_id'] = $suppliers->id;
                    $isupplier_addonnput['updated_by'] = $authId;
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
                    $supAdd['updated_by'] = $authId;
                    if($request->activeTab == 'uploadExcel')
                    {
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
                                        }
                                    }
                                    else
                                    {
                                        dd("Uploading excel headings should be addon_code , currency and purchase_price");
                                    }
                                }
                            }
                        }
                    }
                    $data['successStore'] = true;
                    return response()->json(['success' => true,'data' => $data], 200);
                }
            }
            elseif($request->activeTab == 'addSupplierDynamically')
            { 
                $input = $request->all();
                $suppliers = Supplier::find($request->supplier_id);
                $input['contact_number'] = $request->contact_number['full'];
                $input['alternative_contact_number'] = $request->alternative_contact_number['full'];
                $input['updated_by'] = $authId;
                $suppliers->update($input);
               
                if($request->supplier_types != null)
                {
                    if(count($request->supplier_types) > 0)
                    {
                        $existingSupplierTypes = SupplierType::where('supplier_id',$request->supplier_id)->pluck('supplier_type');
                        $existingSupplierTypes = json_decode($existingSupplierTypes);
                        $supplier_typeData['supplier_id'] = $suppliers->id;
                        $supplier_typeData['updated_by'] = $authId;
                        foreach($request->supplier_types as $supplier_typeData1)
                        { 
                            if(!in_array($supplier_typeData1,$existingSupplierTypes)) 
                            {
                                $supplier_typeData['supplier_type'] = $supplier_typeData1; 
                                $supplier_typeDataCreate = SupplierType::create($supplier_typeData); 
                            }                                                              
                        }
                        foreach($existingSupplierTypes as $existingSupplierTypes1)
                        {
                            if(!in_array($existingSupplierTypes1,$request->supplier_types)) 
                            {
                                $deleSupType = SupplierType::where('supplier_id',$request->supplier_id)->where('supplier_type',$existingSupplierTypes1)->first();
                                if($deleSupType)
                                {
                                    $deleSupType->delete();
                                }
                            }   
                        }
                    }
                }
                $paymentMethodsUpdate = SupplierAvailablePayments::where('is_primary_payment_method','yes')->where('supplier_id',$request->supplier_id)->first();
                $payment_methods['supplier_id'] = $request->supplier_id;
                $payment_methods['updated_by'] = $authId;
                $payment_methods['payment_methods_id'] = $request->is_primary_payment_method;
                $payment_methods['is_primary_payment_method'] = 'yes';
                $paymentMethodsUpdate->update($payment_methods);
                $payment_methods_id = $request->payment_methods_id;
                if($payment_methods_id != null)
                {
                    if(count($payment_methods_id) > 0)
                    {
                        $existingPaymentMethods = SupplierAvailablePayments::where('supplier_id',$request->supplier_id)->where('is_primary_payment_method','no')->pluck('payment_methods_id');
                        $existingPaymentMethods = json_decode($existingPaymentMethods);
                        $paymentMethodsUpdate1['supplier_id'] = $request->supplier_id;
                        $paymentMethodsUpdate1['updated_by'] = $authId;
                        foreach($payment_methods_id as $payment_methods_id1)
                        { 
                            if(!in_array($payment_methods_id1,$existingPaymentMethods)) 
                            {
                                $paymentMethodsUpdate1['payment_methods_id'] = $payment_methods_id1;                           
                                $paymentMethodsUpdate1['is_primary_payment_method'] = 'no'; 
                                $supplier_typeDataCreate = SupplierAvailablePayments::create($paymentMethodsUpdate1); 
                            }                                                              
                        }
                        foreach($existingPaymentMethods as $existingPaymentMethods1)
                        {
                            if(!in_array($existingPaymentMethods1,$payment_methods_id)) 
                            {
                                $delSupPayMet = SupplierAvailablePayments::where('supplier_id',$request->supplier_id)->where('payment_methods_id',$existingPaymentMethods1)->where('is_primary_payment_method','no')->first();
                                if($delSupPayMet)
                                {
                                    $delSupPayMet->delete();
                                }
                            }   
                        }
                    }
                }
                $supplier_addon['supplier_id'] = $suppliers->id;
                $isupplier_addonnput['updated_by'] = $authId;
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
                $supAdd['updated_by'] = $authId;
                if($request->activeTab == 'addSupplierDynamically')
                {
                    if(count($request->supplierAddon) > 0)
                    {
                        $addonAlredyExist = [];
                        foreach($request->supplierAddon as $supAddon)
                        {
                            if($supAddon['addon_purchase_price_in_usd'] != '' OR $supAddon['addon_purchase_price'] != '')
                            {
                                if($supAddon['currency'] != '' AND $supAddon['addon_id'] != '')
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
                                            if(!in_array($addon_code, $addonAlredyExist))
                                            {
                                                $supAdd['addon_details_id'] = $addon_code;
                                                $suppliers = SupplierAddons::create($supAdd);
                                                array_push($addonAlredyExist, $addon_code);
                                            }
                                        }
                                    }
                                }                              
                            } 
                        }
                    }
                }
                $data['successStore'] = true;
                return response()->json(['success' => true,'data' => $data], 200);
            }   
        }
    }
}