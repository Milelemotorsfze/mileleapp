<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethods;
use App\Models\Supplier;
use App\Models\VendorCategory;
use App\Models\VendorDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Monarobase\CountryList\CountryListFacade;
use Validator;
use App\Models\AddonDetails;
use App\Models\SupplierAddons;
use App\Models\SupplierAvailablePayments;
use App\Models\Addon;
use App\Models\Brand;
use App\Models\MasterModelLines;
use App\Models\SupplierAddonTemp;
use App\Models\SupplierType;
use App\Models\AddonSellingPrice;
use App\Models\WarrantyPremiums;
use App\Models\PurchasePriceHistory;
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
        $suppliers = Supplier::with('supplierAddons.supplierAddonDetails','paymentMethods.PaymentMethods','supplierTypes')
            ->whereHas('supplierTypes', function ($query){
                $query->whereNot('supplier_type', Supplier::SUPPLIER_TYPE_DEMAND_PLANNING);
            })
            ->where('status', Supplier::SUPPLIER_STATUS_ACTIVE)
            ->get();

        $inactiveSuppliers = Supplier::with('supplierAddons.supplierAddonDetails','paymentMethods.PaymentMethods','supplierTypes')
            ->whereHas('supplierTypes', function ($query){
                $query->whereNot('supplier_type', Supplier::SUPPLIER_TYPE_DEMAND_PLANNING);
            })
            ->where('status', 'inactive')
            ->get();

        if(Auth::user()->hasPermissionTo('demand-planning-supplier-list') && !Auth::user()->hasPermissionTo('addon-supplier-list')) {
             $suppliers = Supplier::with('supplierTypes')
                 ->whereHas('supplierTypes', function ($query){
                     $query->where('supplier_type', Supplier::SUPPLIER_TYPE_DEMAND_PLANNING);
                 })
                 ->where('status', Supplier::SUPPLIER_STATUS_ACTIVE)
                 ->get();
         }
        return view('suppliers.index',compact('suppliers','inactiveSuppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $paymentMethods = DB::table('payment_methods')->get();
        $addons = AddonDetails::select('id','addon_code','addon_id')->with('AddonName')->get();
        if(Auth::user()->hasPermissionTo('demand-planning-supplier-create') && !Auth::user()->hasPermissionTo('addon-supplier-create'))
        {
            return view('demand_planning_suppliers.create');
        }
        return view('suppliers.create',compact('paymentMethods','addons'));
    }

    /**
     * Display the specified resource.
     */
    public function addonprice($id)
    {
        $supplierAddons = SupplierAddons::where('supplier_id',$id)->where('status','active')->with('supplierAddonDetails.AddonName')->get();
        return view('suppliers.addonprice',compact('supplierAddons','id'));
    }
    public function purchasepricehistory($id)
    {
        $currentPrice = SupplierAddons::where('id',$id)->first();
        $history = PurchasePriceHistory::where('supplier_addon_id',$currentPrice->id)
        ->with('SupplierAddon.supplierAddonDetails.AddonName','CreatedBy')->latest()->get();
        // $supplierAddons = SupplierAddons::where('supplier_id',$id)->where('status','active')->with('supplierAddonDetails.AddonName')->get();
        $supplierId = $currentPrice->supplier_id;
        return view('suppliers.pricehistory',compact('history','supplierId'));
    }
    public function sellingPriceHistory($id)
    {
        $currentPrice = AddonSellingPrice::where('addon_details_id',$id)->where('status','active')->select('selling_price')->first();
        $history =  AddonSellingPrice::where('addon_details_id',$id)->with('StatusUpdatedBy','CreatedBy')->get();
        return view('addon.sellingPricehistory',compact('history','currentPrice'));
    }
    public function newSellingPriceRequest(Request $request)
    {
        $authId = Auth::id();
        $existingSellingprice = AddonSellingPrice::where('id',$request->id)->where('status','active')->latest()->first();
        if($existingSellingprice == '')
        {
            $existingSellingprice = AddonSellingPrice::where('id',$request->id)->where('status','pending')->latest()->first();
        }
        $input['addon_details_id'] = $existingSellingprice->addon_details_id;
        $input['selling_price'] = $request->selling_price;
        $input['created_by'] = $authId;
        $input['status'] = 'pending';
        $createInput = AddonSellingPrice::create($input);
        return redirect()->back()->with('success','Addon Selling Price Updated successfully.');
    }
    public function createNewSupplierAddonPrice(Request $request)
    {
        $authId = Auth::id();
        // $validator = Validator::make($request->all(), [
        //    'name' => 'required',
        // ]);
        // if ($validator->fails())
        // {
        //     return redirect(route('addon.create'))->withInput()->withErrors($validator);
        // }
        // else
        // {
            $input = $request->all();
            $existibgData = SupplierAddons::where('id',$request->id)->where('status','active')->latest('updated_at')->first();
            if($existibgData)
            {
                $existibgData->updated_by = $authId;
                $existibgData->purchase_price_aed = $request->name;
                $existibgData->update();
                $existingHistory = PurchasePriceHistory::where('supplier_addon_id',$existibgData->id)->where('status','active')->first();
                if($existingHistory)
                {
                    $existingHistory->status = 'inactive';
                    $existingHistory->update();
                }
                $input['supplier_addon_id'] = $existibgData->id;
                $input['purchase_price_aed'] = $request->name;
                $input['created_by'] = $authId;
                $addons = PurchasePriceHistory::create($input);
                // $addons = SupplierAddons::where('id',$addons->id)->first();
            }
            return redirect()->route('suppliers.addonprice', $request->supplier_id)->with('success','Supplier Addon Price Updated Successfully.');
            // return response()->json($addons);
        // }
    }
    public function show(Supplier $supplier)
    {
        $content = '';
        $addon1 = $addons = $supplierTypes = '';
        $primaryPaymentMethod = SupplierAvailablePayments::where('supplier_id',$supplier->id)->where('is_primary_payment_method','yes')
            ->with('PaymentMethods')->first();
        $otherPaymentMethods = SupplierAvailablePayments::where('supplier_id',$supplier->id)
//            ->where('is_primary_payment_method','no')
            ->with('PaymentMethods')
            ->get();
        $supplierAddonId = SupplierAddons::where('supplier_id',$supplier->id)->pluck('addon_details_id');
        $supplierTypes = SupplierType::where('supplier_id',$supplier->id)->get();
        if(count($supplierAddonId) > 0)
        {
            $addon1 = AddonDetails::whereIn('id',$supplierAddonId)->with('AddonName','AddonTypes.brands','AddonTypes.modelLines','SellingPrice')
            ->with('PurchasePrices', function($q) use($supplier){
                $q->where('supplier_id', $supplier->id);
            })
            ->orderBy('id', 'ASC')->get();
            foreach($addon1 as $addon)
            {
                $price = '';
                $price = SupplierAddons::where('addon_details_id',$addon->id)->where('status','active')->orderBy('purchase_price_aed','ASC')->first();
                $addon->LeastPurchasePrices = $price;
            }
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
        return view('suppliers.show',compact('supplier','primaryPaymentMethod','otherPaymentMethods','addon1','addons','supplierTypes','content'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {

        $supplierTypes = [];
        if(Auth::user()->hasPermissionTo('demand-planning-supplier-list') && !Auth::user()->hasPermissionTo('addon-supplier-list'))
        {
            $supplier = Supplier::findOrFail($supplier->id);
            return view('demand_planning_suppliers.edit', compact('supplier'));
        }
        $paymentMethods = DB::table('payment_methods')->get();
        $primaryPaymentMethod = SupplierAvailablePayments::where('supplier_id',$supplier->id)->where('is_primary_payment_method','yes')->first();
        $otherPaymentMethods = SupplierAvailablePayments::where('supplier_id',$supplier->id)
                                                            ->where('is_primary_payment_method','no')
                                                            ->pluck('payment_methods_id');
        $array = json_decode($otherPaymentMethods);
        $supplierType = SupplierType::where('supplier_id',$supplier->id)->pluck('supplier_type');
        $supplierTypes = json_decode($supplierType);
        $supplierAddons = SupplierAddons::where('supplier_id',$supplier->id)->pluck('addon_details_id');

        // find using Supplier types
        $supAddTypesName = [];
        $nonRemovableVendorCategories = [];
        $supAddTypes = AddonDetails::whereNot('addon_type_name','K')->whereIn('id',$supplierAddons)->select('addon_type_name')->distinct()->get();
        if(count($supAddTypes) > 0)
        {
            foreach($supAddTypes as $supAddType)
            {
                if($supAddType->addon_type_name == 'P')
                {
                    array_push($supAddTypesName, 'accessories');
                    array_push($nonRemovableVendorCategories, Supplier::SUPPLIER_CATEGORY_PARTS_AND_ACCESSORIES);

                }
                elseif($supAddType->addon_type_name == 'SP')
                {
                    array_push($supAddTypesName, 'spare_parts');
                    array_push($nonRemovableVendorCategories, Supplier::SUPPLIER_CATEGORY_PARTS_AND_ACCESSORIES);

                }
            }
        }
        if(!in_array('accessories', $supAddTypesName) && !in_array('spare_parts', $supAddTypesName))
        {
            $kitAddTypes = AddonDetails::where('addon_type_name','K')->whereIn('id',$supplierAddons)->select('id')->get();
            if(count($kitAddTypes) > 0)
            {
                foreach($kitAddTypes as $kitAddType)
                {
                    $kitSupId = [];
                    $kitSupId = SupplierAddons::where('supplier_id',$supplier->id)->where('addon_details_id',$kitAddType->id)->pluck('id');
                    $kitSupAddons = AddonDetails::whereNot('addon_type_name','K')->whereIn('id',$kitSupId)->select('addon_type_name')->distinct()->get();
                    if(count($kitSupAddons) > 0)
                    {
                        foreach($kitSupAddons as $kitSupAddon)
                        {
                            if($kitSupAddon->addon_type_name == 'P' && !in_array('accessories', $supAddTypesName))
                            {
                                array_push($supAddTypesName, 'accessories');
                                array_push($nonRemovableVendorCategories, Supplier::SUPPLIER_CATEGORY_PARTS_AND_ACCESSORIES);

                            }
                            elseif($kitSupAddon->addon_type_name == 'SP' && !in_array('spare_parts', $supAddTypesName))
                            {
                                array_push($supAddTypesName, 'spare_parts');
                                array_push($nonRemovableVendorCategories, Supplier::SUPPLIER_CATEGORY_PARTS_AND_ACCESSORIES);

                            }
                        }
                    }
                }
            }
        }
        $warrantySupp = WarrantyPremiums::where('supplier_id',$supplier->id)->get();
        if(count($warrantySupp) > 0)
        {
            array_push($supAddTypesName, 'warranty');
            array_push($nonRemovableVendorCategories, Supplier::SUPPLIER_CATEGORY_PARTS_AND_ACCESSORIES);

        }
        // end find using Supplier types
        // addon based on supplier type
        $supTyp = [];
        if(in_array('accessories', $supplierTypes) && in_array('spare_parts', $supplierTypes))
        {
            array_push($supTyp, 'K');
            array_push($supTyp, 'P');
            array_push($supTyp, 'SP');
        }
        else
        {
            if(in_array('accessories', $supplierTypes))
            {
                array_push($supTyp, 'K');
                array_push($supTyp, 'P');
            }
            if(in_array('spare_parts', $supplierTypes))
            {
                array_push($supTyp, 'K');
                array_push($supTyp, 'SP');
            }
        }

        $vendorCategories = VendorCategory::where('supplier_id', $supplier->id)->pluck('category')->toArray();
        $vendorSubCategories = SupplierType::where('supplier_id', $supplier->id)->pluck('supplier_type')->toArray();
        $vendorPaymentMethods = SupplierAvailablePayments::where('supplier_id', $supplier->id)->pluck('payment_methods_id')->toArray();

        $addons = AddonDetails::whereIn('addon_type_name',$supTyp)->whereNotIn('id',$supplierAddons)
            ->select('id','addon_code','addon_id')->with('AddonName')->get();
        return view('suppliers.edit',compact('supplier','primaryPaymentMethod','otherPaymentMethods',
            'addons','paymentMethods','array','supplierTypes','supAddTypesName','supplierAddons','vendorCategories',
            'vendorSubCategories','vendorPaymentMethods','nonRemovableVendorCategories'));
    }
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        DB::beginTransaction();
        SupplierType::where('supplier_id', $id)->delete();
        VendorCategory::where('supplier_id', $id)->delete();
        VendorDocument::where('supplier_id', $id)->delete();

        $supplier->delete();

        DB::commit();
        return response(true);
    }
    // public function makeActive($id)
    // {
    //    $user = Supplier::find($id);
    //    $user->status = 'active';
    //    $user->update();
    //    return redirect()->route('suppliers.index')
    //                    ->with('success','Supplier updated successfully');
    // }
    public function updateStatus(Request $request)
    {
        $supplier = Supplier::find($request->id);
        $supplier->status = $request->status;

        $supplier->save();
        // return response($supplier, 200);
        return response(true);
    }
    // public function statusChange(Request $request)
    // {
    //     $supplier = Supplier::find($request->id);
    //     $supplier->status = $request->status;

    //     $supplier->save();
    //     // return response($supplier, 200);
    //     return response(true);
    // }
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
//        return $request->all();
        $payment_methods_id = $addon_id = [];
        $authId = Auth::id();
        $validator = Validator::make($request->all(), [
            'supplier' => 'required',
            'supplier_types' => 'required',
            'categories' => 'required',
            'contact_number' => 'required'
        ]);

        $isSupplierExist = Supplier::where('supplier', $request->supplier)->where('contact_number', $request->contact_number['full'])->first();
        if($isSupplierExist) {
            return redirect(route('suppliers.create'))->with('error','Name and Contact Number should be unique.');
        }
        if ($validator->fails())
        {
            return redirect(route('suppliers.create'))->withInput()->withErrors($validator);
        }
        else
        {
            $supplierTypeInput = $request->supplier_types;
            $input = $request->all();

            $input['contact_number'] = $request->contact_number['full'];
            $input['alternative_contact_number'] = $request->alternative_contact_number['full'];
            $input['created_by'] = $authId;
            $input['is_communication_fax'] = $request->is_communication_fax ? true : false;
            $input['is_communication_mobile'] = $request->is_communication_mobile ? true : false;
            $input['is_communication_email'] = $request->is_communication_email ? true : false;
            $input['is_communication_postal'] = $request->is_communication_postal ? true : false;
            $input['is_communication_any'] = $request->is_communication_any ? true : false;


            if ($request->hasFile('passport_copy_file'))
            {
                $file = $request->file('passport_copy_file');
                $extension = $file->getClientOriginalExtension();
                $fileName = time().'.'.$extension;
                $destinationPath = 'vendor/passport';
                $file->move($destinationPath, $fileName);

                $input['passport_copy_file'] = $fileName;
            }
            if ($request->hasFile('trade_license_file'))
            {
                $file = $request->file('trade_license_file');
                $extension = $file->getClientOriginalExtension();
                $fileName = time().'.'.$extension;
                $destinationPath = 'vendor/trade_license';
                $file->move($destinationPath, $fileName);
                $input['trade_license_file'] = $fileName;

            }
            if ($request->hasFile('vat_certificate_file'))
            {
                $file = $request->file('vat_certificate_file');
                $extension = $file->getClientOriginalExtension();
                $fileName = time().'.'.$extension;
                $destinationPath = 'vendor/vat_certificate';
                $file->move($destinationPath, $fileName);
                $input['vat_certificate_file'] = $fileName;
            }

            $suppliers = Supplier::create($input);

            if ($request->hasFile('documents'))
            {
                foreach ($request->file('documents') as $file)
                {
                    $extension = $file->getClientOriginalExtension();
                    $fileName = time().'.'.$extension;
                    $destinationPath = 'vendor/other-documents';
                    $file->move($destinationPath, $fileName);

                    $vendorDocument = new VendorDocument();
                    $vendorDocument->supplier_id = $suppliers->id;
                    $vendorDocument->file = $fileName;
                    $vendorDocument->save();
                }
            }
            if($request->categories) {

                foreach ($request->categories as $categoryItem) {
                    $category = new VendorCategory();
                    $category->supplier_id = $suppliers->id;
                    $category->category = $categoryItem;
                    $category->save();
                }

            }
            if($request->payment_methods) {
                foreach ($request->payment_methods as $paymentMethod) {
                    info($paymentMethod);
                    $payment_method = new SupplierAvailablePayments();
                    $payment_method->supplier_id = $suppliers->id;
                    $payment_method->payment_methods_id = $paymentMethod;
                    $payment_method->created_by = Auth::id();
                    $payment_method->save();
                }

            }

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
                                                if(count($supplierTypeInput)  > 0)
                                                {
                                                    if(in_array('accessories', $supplierTypeInput) && in_array('spare_parts', $supplierTypeInput))
                                                    {
                                                        $typeAddonData = AddonDetails::whereIn('addon_type_name',['P','SP','K'])->where('addon_code',$rows[$i]['addon_code'])->select('id')->first();
                                                    }
                                                    elseif(in_array('accessories', $supplierTypeInput))
                                                    {
                                                        $typeAddonData = AddonDetails::whereIn('addon_type_name',['P','K'])->where('addon_code',$rows[$i]['addon_code'])->select('id')->first();
                                                    }
                                                    elseif(in_array('spare_parts', $supplierTypeInput))
                                                    {
                                                        $typeAddonData = AddonDetails::whereIn('addon_type_name',['SP','K'])->where('addon_code',$rows[$i]['addon_code'])->select('id')->first();
                                                    }
                                                    if($typeAddonData == '')
                                                    {
                                                        $addonError = "This addon code is not match with supplier type";
                                                    }
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
//                                    $payment_methods['supplier_id'] = $suppliers->id;
//                                    $payment_methods['created_by'] = $authId;
//                                    $payment_methods['payment_methods_id'] = $request->is_primary_payment_method;
//                                    $payment_methods['is_primary_payment_method'] = 'yes';
//                                    $paymentMethods = SupplierAvailablePayments::create($payment_methods);
//                                    $payment_methods_id = $request->payment_methods_id;
//                                    if($payment_methods_id != null)
//                                    {
//                                        if(count($payment_methods_id) > 0)
//                                        {
//                                            foreach($payment_methods_id as $payment_methods_id)
//                                            {
//                                                $payment_methods['payment_methods_id'] = $payment_methods_id;
//                                                    $payment_methods['is_primary_payment_method'] = 'no';
//                                                $paymentMethods = SupplierAvailablePayments::create($payment_methods);
//                                            }
//                                        }
//                                    }
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
                                                $supplier_addon['supplier_addon_id'] = $supplierAddon1->id;
                                                $createHistory = PurchasePriceHistory::create($supplier_addon);
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
                                                            $supAdd['supplier_addon_id'] = $suppliers->id;
                                                            $createHistory = PurchasePriceHistory::create($supAdd);
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
                                $supplier_addon['supplier_addon_id'] = $supplierAddon1->id;
                                $createHistory = PurchasePriceHistory::create($supplier_addon);
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
                                            $supAdd['supplier_addon_id'] = $suppliers->id;
                                            $createHistory = PurchasePriceHistory::create($supAdd);
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
                $supplier_addon['supplier_id'] = $suppliers->id;
                $isupplier_addonnput['created_by'] = $authId;
                $supAdd['supplier_id'] = $suppliers->id;
                $supAdd['created_by'] = $authId;
                if($request->activeTab == 'addSupplierDynamically')
                {
                    if(count($request->supplierAddon) > 0)
                    {
                        info($request->supplierAddon);
                        $addonAlredyExist = [];
                        foreach($request->supplierAddon as $supAddon)
                        {

                            if($supAddon['addon_purchase_price_in_usd'] != NULL || $supAddon['addon_purchase_price'] != NULL)
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
                                                $supAdd['supplier_addon_id'] = $suppliers->id;
                                                $createHistory = PurchasePriceHistory::create($supAdd);
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
            'contact_number' => 'required',
            'supplier_types' => 'required',
            'categories' => 'required'
        ]);
        if ($validator->fails())
        {
            return redirect(route('suppliers.create'))->withInput()->withErrors($validator);
        }
        else {

            $supplierTypeInput = $request->supplier_types;
            $input = $request->all();
            $suppliers = Supplier::find($request->supplier_id);
            $input['contact_number'] = $request->contact_number['full'];
            $input['alternative_contact_number'] = $request->alternative_contact_number['full'];

            $input['is_communication_fax'] = $request->is_communication_fax ? true : false;
            $input['is_communication_mobile'] = $request->is_communication_mobile ? true : false;
            $input['is_communication_email'] = $request->is_communication_email ? true : false;
            $input['is_communication_postal'] = $request->is_communication_postal ? true : false;
            $input['is_communication_any'] = $request->is_communication_any ? true : false;

            $input['updated_by'] = $authId;

            if ($request->hasFile('passport_copy_file')) {
                $file = $request->file('passport_copy_file');
                $extension = $file->getClientOriginalExtension();
                $fileName = time() . '.' . $extension;
                $destinationPath = 'vendor/passport';
                $file->move($destinationPath, $fileName);

                $input['passport_copy_file'] = $fileName;
            }
            if ($request->hasFile('trade_license_file')) {
                $file = $request->file('trade_license_file');
                $extension = $file->getClientOriginalExtension();
                $fileName = time() . '.' . $extension;
                $destinationPath = 'vendor/trade_license';
                $file->move($destinationPath, $fileName);
                $input['trade_license_file'] = $fileName;

            }
            if ($request->hasFile('vat_certificate_file')) {
                $file = $request->file('vat_certificate_file');
                $extension = $file->getClientOriginalExtension();
                $fileName = time() . '.' . $extension;
                $destinationPath = 'vendor/vat_certificate';
                $file->move($destinationPath, $fileName);
                $input['vat_certificate_file'] = $fileName;
            }

            $suppliers->update($input);

            if ($request->hasFile('documents')) {
                info($request->file('documents'));

                foreach ($request->file('documents') as $file) {
                    $extension = $file->getClientOriginalExtension();
                     $fileName = $file->getClientOriginalName();
                    info("file name");
                    $destinationPath = 'vendor/other-documents';
                    $file->move($destinationPath, $fileName);

                    $vendorDocument = new VendorDocument();
                    $vendorDocument->supplier_id = $suppliers->id;
                    $vendorDocument->file = $fileName;
                    $vendorDocument->save();
                }
            }
            if ($request->categories) {
                $existingVendorCategories = VendorCategory::where('supplier_id',$request->supplier_id)->delete();

                foreach ($request->categories as $categoryItem) {
                    $category = new VendorCategory();
                    $category->supplier_id = $suppliers->id;
                    $category->category = $categoryItem;
                    $category->save();
                }
            }
            if($request->payment_methods) {
                $existingPaymentMethods = SupplierAvailablePayments::where('supplier_id',$request->supplier_id)->delete();

                foreach ($request->payment_methods as $paymentMethod) {

                    $payment_method = new SupplierAvailablePayments();
                    $payment_method->supplier_id = $suppliers->id;
                    $payment_method->payment_methods_id = $paymentMethod;
                    $payment_method->created_by = Auth::id();
                    $payment_method->save();
                }
            }
            if($request->supplier_types)
            {
                $existingSupplierTypes = SupplierType::where('supplier_id',$request->supplier_id)->delete();
                foreach ($request->supplier_types as $supplierType)
                {
                    $supplier_type = new SupplierType();
                    $supplier_type->supplier_id = $suppliers->id;
                    $supplier_type->supplier_type = $supplierType;
                    $supplier_type->created_by = Auth::id();
                    $supplier_type->save();
                }
            }

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
                                                else
                                                {
                                                    if(count($supplierTypeInput > 0))
                                                    {
                                                        if(in_array('accessories', $supplierTypeInput) && in_array('spare_parts', $supplierTypeInput))
                                                        {
                                                            $typeAddonData = AddonDetails::whereIn('addon_type_name',['P','SP','K'])->where('addon_code',$rows[$i]['addon_code'])->select('id')->first();
                                                        }
                                                        elseif(in_array('accessories', $supplierTypeInput))
                                                        {
                                                            $typeAddonData = AddonDetails::whereIn('addon_type_name',['P','K'])->where('addon_code',$rows[$i]['addon_code'])->select('id')->first();
                                                        }
                                                        elseif(in_array('spare_parts', $supplierTypeInput))
                                                        {
                                                            $typeAddonData = AddonDetails::whereIn('addon_type_name',['SP','K'])->where('addon_code',$rows[$i]['addon_code'])->select('id')->first();
                                                        }
                                                        if($typeAddonData == '')
                                                        {
                                                            $addonError = "This addon code is not match with supplier type";
                                                        }
                                                    }
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
//                                    $input = $request->all();
//                                    $suppliers = Supplier::find($request->supplier_id);
//                                    $input['contact_number'] = $request->contact_number['full'];
//                                    $input['alternative_contact_number'] = $request->alternative_contact_number['full'];
//                                    $input['updated_by'] = $authId;
//                                    $suppliers->update($input);
//                                    if($request->supplier_types != null)
//                                    {
//                                        if(count($request->supplier_types) > 0)
//                                        {
//                                            $existingSupplierTypes = SupplierType::where('supplier_id',$request->supplier_id)->pluck('supplier_type');
//                                            $existingSupplierTypes = json_decode($existingSupplierTypes);
//                                            $supplier_typeData['supplier_id'] = $suppliers->id;
//                                            $supplier_typeData['updated_by'] = $authId;
//                                            foreach($request->supplier_types as $supplier_typeData1)
//                                            {
//                                                if(!in_array($supplier_typeData1,$existingSupplierTypes))
//                                                {
//                                                    $supplier_typeData['supplier_type'] = $supplier_typeData1;
//                                                    $supplier_typeDataCreate = SupplierType::create($supplier_typeData);
//                                                }
//                                            }
//                                            foreach($existingSupplierTypes as $existingSupplierTypes1)
//                                            {
//                                                if(!in_array($existingSupplierTypes1,$request->supplier_types))
//                                                {
//                                                    $deleSupType = SupplierType::where('supplier_id',$request->supplier_id)->where('supplier_type',$existingSupplierTypes1)->first();
//                                                    if($deleSupType)
//                                                    {
//                                                        $deleSupType->delete();
//                                                    }
//                                                }
//                                            }
//                                        }
//                                    }
//                                    $paymentMethodsUpdate = SupplierAvailablePayments::where('is_primary_payment_method','yes')->where('supplier_id',$request->supplier_id)->first();
//                                    $payment_methods['supplier_id'] = $request->supplier_id;
//                                    $payment_methods['updated_by'] = $authId;
//                                    $payment_methods['payment_methods_id'] = $request->is_primary_payment_method;
//                                    $payment_methods['is_primary_payment_method'] = 'yes';
//                                    $paymentMethodsUpdate->update($payment_methods);
//                                    $payment_methods_id = $request->payment_methods_id;
//                                    if($payment_methods_id != null)
//                                    {
//                                        if(count($payment_methods_id) > 0)
//                                        {
//                                            $existingPaymentMethods = SupplierAvailablePayments::where('supplier_id',$request->supplier_id)->where('is_primary_payment_method','no')->pluck('payment_methods_id');
//                                            $existingPaymentMethods = json_decode($existingPaymentMethods);
//                                            $paymentMethodsUpdate1['supplier_id'] = $request->supplier_id;
//                                            $paymentMethodsUpdate1['updated_by'] = $authId;
//                                            foreach($payment_methods_id as $payment_methods_id1)
//                                            {
//                                                if(!in_array($payment_methods_id1,$existingPaymentMethods))
//                                                {
//                                                    $paymentMethodsUpdate1['payment_methods_id'] = $payment_methods_id1;
//                                                    $paymentMethodsUpdate1['is_primary_payment_method'] = 'no';
//                                                    $supplier_typeDataCreate = SupplierAvailablePayments::create($paymentMethodsUpdate1);
//                                                }
//                                            }
//                                            foreach($existingPaymentMethods as $existingPaymentMethods1)
//                                            {
//
//                                                if(!in_array($existingPaymentMethods1,$payment_methods_id))
//                                                {
//                                                    $delSupPayMet = SupplierAvailablePayments::where('supplier_id',$request->supplier_id)->where('payment_methods_id',$existingPaymentMethods1)->where('is_primary_payment_method','no')->first();
//                                                    if($delSupPayMet)
//                                                    {
//                                                        $delSupPayMet->delete();
//                                                    }
//                                                }
//                                            }
//                                        }
//                                    }
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
                                                $supplier_addon['supplier_addon_id'] = $supplierAddon1->id;
                                                $createHistory = PurchasePriceHistory::create($supplier_addon);
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
                                                            $supAdd['supplier_addon_id'] = $suppliers->id;
                                                            $createHistory = PurchasePriceHistory::create($supAdd);
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
//                    if($request->supplier_types != null)
//                    {
//                        if(count($request->supplier_types) > 0)
//                        {
//                            $existingSupplierTypes = SupplierType::where('supplier_id',$request->supplier_id)->pluck('supplier_type');
//                            $existingSupplierTypes = json_decode($existingSupplierTypes);
//                            $supplier_typeData['supplier_id'] = $suppliers->id;
//                            $supplier_typeData['updated_by'] = $authId;
//                            foreach($request->supplier_types as $supplier_typeData1)
//                            {
//                                if(!in_array($supplier_typeData1,$existingSupplierTypes))
//                                {
//                                    $supplier_typeData['supplier_type'] = $supplier_typeData1;
//                                    $supplier_typeDataCreate = SupplierType::create($supplier_typeData);
//                                }
//                            }
//                            foreach($existingSupplierTypes as $existingSupplierTypes1)
//                            {
//                                if(!in_array($existingSupplierTypes1,$request->supplier_types))
//                                {
//                                    $deleSupType = SupplierType::where('supplier_id',$request->supplier_id)->where('supplier_type',$existingSupplierTypes1)->first();
//                                    if($deleSupType)
//                                    {
//                                        $deleSupType->delete();
//                                    }
//                                }
//                            }
//                        }
//                    }
//                    $paymentMethodsUpdate = SupplierAvailablePayments::where('is_primary_payment_method','yes')->where('supplier_id',$request->supplier_id)->first();
//                    $payment_methods['supplier_id'] = $request->supplier_id;
//                    $payment_methods['updated_by'] = $authId;
//                    $payment_methods['payment_methods_id'] = $request->is_primary_payment_method;
//                    $payment_methods['is_primary_payment_method'] = 'yes';
//                    $paymentMethodsUpdate->update($payment_methods);
//                    $payment_methods_id = $request->payment_methods_id;
//                    if($payment_methods_id != null)
//                    {
//                        if(count($payment_methods_id) > 0)
//                        {
//                            $existingPaymentMethods = SupplierAvailablePayments::where('supplier_id',$request->supplier_id)->where('is_primary_payment_method','no')->pluck('payment_methods_id');
//                            $existingPaymentMethods = json_decode($existingPaymentMethods);
//                            $paymentMethodsUpdate1['supplier_id'] = $request->supplier_id;
//                            $paymentMethodsUpdate1['updated_by'] = $authId;
//                            foreach($payment_methods_id as $payment_methods_id1)
//                            {
//                                if(!in_array($payment_methods_id1,$existingPaymentMethods))
//                                {
//                                    $paymentMethodsUpdate1['payment_methods_id'] = $payment_methods_id1;
//                                    $paymentMethodsUpdate1['is_primary_payment_method'] = 'no';
//                                    $supplier_typeDataCreate = SupplierAvailablePayments::create($paymentMethodsUpdate1);
//                                }
//                            }
//                            foreach($existingPaymentMethods as $existingPaymentMethods1)
//                            {
//
//                                if(!in_array($existingPaymentMethods1,$payment_methods_id))
//                                {
//                                    $delSupPayMet = SupplierAvailablePayments::where('supplier_id',$request->supplier_id)->where('payment_methods_id',$existingPaymentMethods1)->where('is_primary_payment_method','no')->first();
//                                    if($delSupPayMet)
//                                    {
//                                        $delSupPayMet->delete();
//                                    }
//                                }
//                            }
//                        }
//                    }
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
                                $supplier_addon['supplier_addon_id'] = $supplierAddon1->id;
                                $createHistory = PurchasePriceHistory::create($supplier_addon);
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
                                            $supAdd['supplier_addon_id'] = $suppliers->id;
                                            $createHistory = PurchasePriceHistory::create($supAdd);
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

            info("dynamic add");
//                if($request->supplier_types != null)
//                {
//                    if(count($request->supplier_types) > 0)
//                    {
//                        $existingSupplierTypes = SupplierType::where('supplier_id',$request->supplier_id)->pluck('supplier_type');
//                        $existingSupplierTypes = json_decode($existingSupplierTypes);
//                        $supplier_typeData['supplier_id'] = $suppliers->id;
//                        $supplier_typeData['updated_by'] = $authId;
//                        foreach($request->supplier_types as $supplier_typeData1)
//                        {
//                            if(!in_array($supplier_typeData1,$existingSupplierTypes))
//                            {
//                                $supplier_typeData['supplier_type'] = $supplier_typeData1;
//                                $supplier_typeDataCreate = SupplierType::create($supplier_typeData);
//                            }
//                        }
//                        foreach($existingSupplierTypes as $existingSupplierTypes1)
//                        {
//                            if(!in_array($existingSupplierTypes1,$request->supplier_types))
//                            {
//                                $deleSupType = SupplierType::where('supplier_id',$request->supplier_id)->where('supplier_type',$existingSupplierTypes1)->first();
//                                if($deleSupType)
//                                {
//                                    $deleSupType->delete();
//                                }
//                            }
//                        }
//                    }
//                }
//                $paymentMethodsUpdate = SupplierAvailablePayments::where('is_primary_payment_method','yes')->where('supplier_id',$request->supplier_id)->first();
//                $payment_methods['supplier_id'] = $request->supplier_id;
//                $payment_methods['updated_by'] = $authId;
//                $payment_methods['payment_methods_id'] = $request->is_primary_payment_method;
//                $payment_methods['is_primary_payment_method'] = 'yes';
//                $paymentMethodsUpdate->update($payment_methods);
//                $payment_methods_id = $request->payment_methods_id;
//                if($payment_methods_id != null)
//                {
//                    if(count($payment_methods_id) > 0)
//                    {
//                        $existingPaymentMethods = SupplierAvailablePayments::where('supplier_id',$request->supplier_id)->where('is_primary_payment_method','no')->pluck('payment_methods_id');
//                        $existingPaymentMethods = json_decode($existingPaymentMethods);
//                        $paymentMethodsUpdate1['supplier_id'] = $request->supplier_id;
//                        $paymentMethodsUpdate1['updated_by'] = $authId;
//                        foreach($payment_methods_id as $payment_methods_id1)
//                        {
//                            if(!in_array($payment_methods_id1,$existingPaymentMethods))
//                            {
//                                $paymentMethodsUpdate1['payment_methods_id'] = $payment_methods_id1;
//                                $paymentMethodsUpdate1['is_primary_payment_method'] = 'no';
//                                $supplier_typeDataCreate = SupplierAvailablePayments::create($paymentMethodsUpdate1);
//                            }
//                        }
//                        foreach($existingPaymentMethods as $existingPaymentMethods1)
//                        {
//                            if(!in_array($existingPaymentMethods1,$payment_methods_id))
//                            {
//                                $delSupPayMet = SupplierAvailablePayments::where('supplier_id',$request->supplier_id)->where('payment_methods_id',$existingPaymentMethods1)->where('is_primary_payment_method','no')->first();
//                                if($delSupPayMet)
//                                {
//                                    $delSupPayMet->delete();
//                                }
//                            }
//                        }
//                    }
//                }
                $supplier_addon['supplier_id'] = $suppliers->id;
                $isupplier_addonnput['updated_by'] = $authId;
                $addon_id = $request->addon_id;
                // if($addon_id != NULL)
                // {dd('hi');
                //     if(count($addon_id) > 0)
                //     {
                //         foreach($addon_id as $addon_id)
                //         {
                //             $supplier_addon['addon_details_id'] = $addon_id;
                //             $supplierAddon1 = SupplierAddons::create($supplier_addon);
                //         }
                //     }
                // }
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
                                                $supAdd['supplier_addon_id'] = $suppliers->id;
                                                $createHistory = PurchasePriceHistory::create($supAdd);
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

    public function getAddonForSupplier(Request $request)
    {
        $data = AddonDetails::select('id','addon_code','addon_id')->with('AddonName');
        if($request->selectedAddonTypes)
        {
            if(count($request->selectedAddonTypes) > 0)
            {
                if(in_array('accessories', $request->selectedAddonTypes) && in_array('spare_parts', $request->selectedAddonTypes))
                {
                    $data = $data->whereIn('addon_type_name',['P','SP','K']);
                }
                elseif(in_array('accessories', $request->selectedAddonTypes))
                {
                    $data = $data->whereIn('addon_type_name',['P','K']);
                }
                elseif(in_array('spare_parts', $request->selectedAddonTypes))
                {
                    $data = $data->whereIn('addon_type_name',['SP','K']);
                }
            }
        }
        if($request->supplierAddons)
        {
            if(count($request->supplierAddons) > 0)
            {
                $data = $data->whereNotIn('id',$request->supplierAddons);
            }
        }
        if($request->filteredArray)
        {
            if(count($request->filteredArray) > 0)
            {
                $data = $data->whereNotIn('id',$request->filteredArray);
            }
        }
        if($request->id) {
            $id = $request->id;
            // AddonSuppliersUsed
            // need to check active or not
            $alreadyAddedAddonIds = AddonDetails::whereHas('AddonSuppliers', function ($query) use($id) {
                $query->where('supplier_id', $id);
            })->pluck('addon_id');
            $data = $data->whereNotIn('id', $alreadyAddedAddonIds);
        }
        $data = $data->get();
        return response()->json($data);
    }
    public function updateSupplier(Request $request) {
        $suppliers = Supplier::find($request->supplier_id);
        $authId = Auth::id();
        $validator = Validator::make($request->all(), [
            'supplier' => 'required',
            'supplier_types' => 'required',
            'category' => 'required'
        ]);
        if ($validator->fails())
        {
            return redirect(route('suppliers.create'))->withInput()->withErrors($validator);
        }
        else
        {
            $input = $request->all();
            $suppliers = Supplier::find($request->supplier_id);
            $input['contact_number'] = $request->contact_number['full'];
            $input['alternative_contact_number'] = $request->alternative_contact_number['full'];
            $input['updated_by'] = $authId;
            $suppliers->update($input);

//            if(count($request->supplier_types) > 0)
//            {
//                $existingSupplierTypes = SupplierType::where('supplier_id',$request->supplier_id)->delete();
//
//                $supplier_typeData['supplier_id'] = $suppliers->id;
//                $supplier_typeData['updated_by'] = $authId;
//                foreach($request->supplier_types as $supplier_typeData1)
//                {
//                    $supplier_typeData['supplier_type'] = $supplier_typeData1;
//                    $supplier_typeDataCreate = SupplierType::create($supplier_typeData);
//                }
//            }
            $data['successStore'] = true;
            return response()->json(['success' => true,'data' => $data], 200);
        }
    }
    public function vendorUniqueCheck(Request $request)
    {
        $contactNumber = $request->contact_number;
        $isVendorExist = Supplier::where('contact_number', $contactNumber)->where('supplier', $request->name);
        if($request->id) {
            $isVendorExist = $isVendorExist->whereNot('id', $request->id);
        }
        $isVendorExist = $isVendorExist->first();
        $data = [];
        if($isVendorExist) {

            $data['error'] = 'Combination of Name and Contact Number should be unique';
            return response($data);
        }else{
            return response($data);
        }
    }
}
