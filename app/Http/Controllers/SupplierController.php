<?php

namespace App\Http\Controllers;

use App\Models\MasterVendorCategory;
use App\Models\MasterVendorSubCategory;
use App\Models\PaymentMethods;
use App\Models\Supplier;
use App\Models\User;
use App\Models\VendorCategory;
use App\Models\VendorDocument;
use http\Env\Response;
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
use App\Http\Controllers\UserActivityController;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        (new UserActivityController)->createActivity('Open Vendor Listing Section');

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

        if(Auth::user()->hasPermissionForSelectedRole('demand-planning-supplier-list') && !Auth::user()->hasPermissionForSelectedRole('addon-supplier-list')) {

             $suppliers = Supplier::with('supplierTypes')
                 ->whereHas('supplierTypes', function ($query){
                     $query->where('supplier_type', Supplier::SUPPLIER_TYPE_DEMAND_PLANNING);
                 })
                 ->where('status', Supplier::SUPPLIER_STATUS_ACTIVE)
                 ->get();

            $inactiveSuppliers = Supplier::with('supplierTypes')
                ->whereHas('supplierTypes', function ($query){
                    $query->where('supplier_type', Supplier::SUPPLIER_TYPE_DEMAND_PLANNING);
                })
                ->where('status', Supplier::SUPPLIER_STATUS_INACTIVE)
                ->get();
         }
        return view('suppliers.index',compact('suppliers','inactiveSuppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        (new UserActivityController)->createActivity('Open Vendor Create Section');

        $paymentMethods = DB::table('payment_methods')->get();
        $addons = AddonDetails::select('id','addon_code','addon_id')->with('AddonName')->get();
//        if(Auth::user()->hasPermissionTo('demand-planning-supplier-create') && !Auth::user()->hasPermissionTo('addon-supplier-create'))
//        {
//            return view('demand_planning_suppliers.create');
//        }
        $categories = MasterVendorCategory::all();
        $users = User::whereNotIn('id',[1,16])->select('id','name')->get();
        return view('suppliers.create',compact('paymentMethods','addons','users','categories'));
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
                $existibgData->purchase_price_usd = $request->name / 3.6725;
                $existibgData->update();
                $existingHistory = PurchasePriceHistory::where('supplier_addon_id',$existibgData->id)->where('status','active')->first();
                if($existingHistory)
                {
                    $existingHistory->status = 'inactive';
                    $existingHistory->update();
                }
                $input['supplier_addon_id'] = $existibgData->id;
                $input['purchase_price_aed'] = $request->name;
                $input['purchase_price_usd'] = $request->name / 3.6725;
                $input['created_by'] = $authId;
                $addons = PurchasePriceHistory::create($input);
                // $addons = SupplierAddons::where('id',$addons->id)->first();
            }
            if($request->kit_id) {
                return response(true);
            }
            return redirect()->route('suppliers.addonprice', $request->supplier_id)->with('success','Supplier Addon Price Updated Successfully.');
            // return response()->json($addons);
        // }
    }
    public function show(Supplier $supplier)
    {
        (new UserActivityController)->createActivity('Open Vendor View Section');

        $rowperpage = $data = '';
        $content = '';
        $addon1 =  $supplierTypes = '';
        $addons = [];
        $addonIds = [];

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
                $addon->least_purchase_price = $price;
            }
            $addons = DB::table('addon_details')
                        ->join('addons','addons.id','addon_details.addon_id')
                        ->join('addon_types','addon_types.addon_details_id','addon_details.id')
                        ->join('brands','brands.id','addon_types.brand_id')
                        ->join('master_model_lines','master_model_lines.id','addon_types.model_id')
                        ->whereIn('addon_details.id',$supplierAddonId)
                        ->select('addons.name','addon_details.id as addon_details_table_id','addon_details.addon_id','addon_details.addon_code','addon_details.payment_condition',
                        'addon_details.additional_remarks','addon_details.image','addon_details.is_all_brands','addon_details.status','addon_types.brand_id','addon_types.model_id','addon_types.is_all_model_lines','brands.brand_name',
                        'master_model_lines.model_line')
                        ->orderBy('addon_details.id','ASC')
                        ->get();
                        $addonIds = $addons->pluck('id');
                        $addonIds = json_decode($addonIds);
        }
        return view('suppliers.show',compact('supplier','primaryPaymentMethod','otherPaymentMethods','addon1','addons','supplierTypes','content','addonIds'
                                                ,'rowperpage','data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        $supplierTypes = [];
//        if(Auth::user()->hasPermissionTo('demand-planning-supplier-list') && !Auth::user()->hasPermissionTo('addon-supplier-list'))
//        {
//            $supplier = Supplier::findOrFail($supplier->id);
//            return view('demand_planning_suppliers.edit', compact('supplier'));
//        }
        (new UserActivityController)->createActivity('Open Vendor Edit Section');

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

        $masterVendorCategories = MasterVendorCategory::all();
        $vendorCategories = VendorCategory::where('supplier_id', $supplier->id)->pluck('category')->toArray();
        $vendorCategoyIds = MasterVendorCategory::whereIn('name', $vendorCategories)->pluck('id')->toArray();

        $masterSubCategories = MasterVendorSubCategory::whereIn('master_vendor_category_id', $vendorCategoyIds)->get();

        $vendorSubCategories = SupplierType::where('supplier_id', $supplier->id)->pluck('supplier_type')->toArray();
        $vendorPaymentMethods = SupplierAvailablePayments::where('supplier_id', $supplier->id)->pluck('payment_methods_id')->toArray();

        $addons = AddonDetails::whereIn('addon_type_name',$supTyp)->whereNotIn('id',$supplierAddons)
            ->select('id','addon_code','addon_id')->with('AddonName')->get();
        $users = User::whereNotIn('id',[1,16])->select('id','name')->get();

        return view('suppliers.edit',compact('supplier','primaryPaymentMethod','otherPaymentMethods',
            'addons','paymentMethods','array','supplierTypes','supAddTypesName','supplierAddons','vendorCategories','masterSubCategories',
            'vendorSubCategories','vendorPaymentMethods','nonRemovableVendorCategories','users','masterVendorCategories'));
    }
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        DB::beginTransaction();
        SupplierType::where('supplier_id', $id)->delete();
        VendorCategory::where('supplier_id', $id)->delete();
        VendorDocument::where('supplier_id', $id)->delete();

        $supplier->delete();
        (new UserActivityController)->createActivity('Vendor Deleted');
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
        
        $payment_methods_id = $addon_id = [];
        (new UserActivityController)->createActivity('Created Vendor');

        $authId = Auth::id();
        $validator = Validator::make($request->all(), [
            'supplier' => 'required',
            'supplier_types' => 'required',
            'categories' => 'required',
            'contact_number' => 'required',
            'email' =>'required'
        ]);

        // $isSupplierExist = Supplier::where('supplier', $request->supplier)->where('contact_number', $request->contact_number['full'])->first();
        // if($isSupplierExist) {
        //     return redirect(route('suppliers.create'))->with('error','Name and Contact Number should be unique.');
        // }
        if ($validator->fails())
        {
            
            return redirect(route('suppliers.create'))->withInput()->withErrors($validator);
        }
        else
        {
            $supplierTypeInput = $request->supplier_types;
            $input = $request->all();

            if($request->activeTab == 'uploadExcel')
            {
                if($request->file('file'))
                {
                    $headings = (new HeadingRowImport)->toArray($request->file('file'));
                    if(count($headings) > 0)
                    {
                        foreach($headings[0] as $heading)
                        {
                            if(in_array('addon_code', $heading) && in_array('currency', $heading) && in_array('purchase_price', $heading)
                            && in_array('lead_time_min', $heading) && in_array('lead_time_max', $heading))
                            {
                                Excel::import(new SupplierAddonImport,request()->file('file'));
                                $dataError = [];
                                $rows = SupplierAddonTemp::all();
                                $existingAddon = [];
                                for ($i=0; $i< count($rows); $i++)
                                {
                                    $currencyError = $priceErrror = $addonError = $minLeadTime = $maxLeadTime ='';
                                    if($rows[$i]['currency'] OR $rows[$i]['purchase_price'] OR $rows[$i]['addon_code'] OR $rows[$i]['lead_time_min'] OR $rows[$i]['lead_time_max'])
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
                                        if($rows[$i]['lead_time_min'] != '' && !is_numeric($rows[$i]['lead_time_min']) && strlen($rows[$i]['lead_time_min']) > 3)
                                        {
                                            $minLeadTime = "Number with maximum 3 digits expected as Minimum Lead Time ";
                                        }
                                        if($rows[$i]['lead_time_max'] != ''  && !is_numeric($rows[$i]['lead_time_max']) && strlen($rows[$i]['lead_time_max']) > 3)
                                        {
                                            $maxLeadTime = "Number with maximum 3 digits expected as Maximum Lead Time ";
                                        }
                                        if($rows[$i]['lead_time_min'] != '' && is_numeric($rows[$i]['lead_time_min']) && strlen($rows[$i]['lead_time_min']) <= 3
                                        && $rows[$i]['lead_time_max'] != '' && is_numeric($rows[$i]['lead_time_max']) && strlen($rows[$i]['lead_time_max']) <= 3)
                                        {
                                            if(intval($rows[$i]['lead_time_max']) > $rows[$i]['lead_time_min'])
                                            {
                                                $maxLeadTime = "Greater than minimum leadtime expected";
                                            }
                                        }
                                        if($currencyError != '' OR $priceErrror != '' OR $addonError != '' OR $minLeadTime != '' OR $maxLeadTime != '')
                                        {
                                            array_push($dataError, ["addon_code" => $rows[$i]['addon_code'], "addonError" => $addonError,
                                                                    "currency" => $rows[$i]['currency'], "currencyError" => $currencyError,
                                                                    "purchase_price" => $rows[$i]['purchase_price'], "priceErrror" => $priceErrror,
                                                                    "lead_time_min" => $rows[$i]['lead_time_min'], "minLeadTimeErrror" => $minLeadTime,
                                                                    "lead_time_max" => $rows[$i]['lead_time_max'], "maxLeadTimeErrror" => $maxLeadTime,
                                                                ]);
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
                                    $suppliers = $this->createSupplier($request);

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
                                                        info("Uploading excel headings should be addon_code , currency and purchase_price");
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    $data['successStore'] = true;
                                    (new UserActivityController)->createActivity('Vendor Created');
                                    return response()->json(['success' => true,'data' => $data], 200);
                                }
                            }
                            else
                            {
                                $data['headingError'] = "Uploading excel headings should be addon_code , currency , purchase_price , lead_time_min and lead_time_max";
                                return response()->json(['success' => true,'data' => $data], 200);;
                            }
                        }
                    }
                }
                else
                {
                    $suppliers = $this->createSupplier($request);

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
                                    if(in_array('addon_code', $heading) && in_array('currency', $heading) && in_array('purchase_price', $heading)
                                    && in_array('lead_time_min', $heading) && in_array('lead_time_max', $heading))
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
                                            if($supplierAddon->lead_time != '' && $supplierAddon->lead_time_max != '')
                                            {
                                                if(intval($supplierAddon->lead_time) == intval($supplierAddon->lead_time_max))
                                                {
                                                    $supAdd['lead_time_min'] = $supplierAddon->lead_time;
                                                    $supAdd['lead_time_max'] = NULL;
                                                }
                                                elseif(intval($supplierAddon->lead_time) < intval($supplierAddon->lead_time_max))
                                                {
                                                    $supAdd['lead_time_min'] = $supplierAddon->lead_time;
                                                    $supAdd['lead_time_max'] = $supplierAddon->lead_time_max;
                                                }
                                            }
                                            else
                                            {
                                                $supAdd['lead_time_min'] = $supplierAddon->lead_time;
                                                $supAdd['lead_time_max'] = $supplierAddon->lead_time_max;
                                            }
                                            $suppliers = SupplierAddons::create($supAdd);
                                            $supAdd['supplier_addon_id'] = $suppliers->id;
                                            $createHistory = PurchasePriceHistory::create($supAdd);
                                        }
                                    }
                                    else
                                    {
                                        info("Uploading excel headings should be addon_code , currency and purchase_price");
                                    }
                                }
                            }
                        }
                    }
                    $data['successStore'] = true;
                    (new UserActivityController)->createActivity('Vendor Created');
                    return response()->json(['success' => true,'data' => $data], 200);
                }
            }
            elseif($request->activeTab == 'addSupplierDynamically')
            {
                $suppliers = $this->createSupplier($request);

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

                            if(isset($supAddon['addon_purchase_price_in_usd']) || isset($supAddon['addon_purchase_price']))
                            {
                                if($supAddon['currency'] != '' AND isset($supAddon['addon_id']))
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
                                                if($supAddon['lead_time'] != '' && $supAddon['lead_time_max'] != '')
                                                {
                                                    if(intval($supAddon['lead_time']) == intval($supAddon['lead_time_max']))
                                                    {
                                                        $supAdd['lead_time_min'] = $supAddon['lead_time'];
                                                        $supAdd['lead_time_max'] = NULL;
                                                    }
                                                    elseif(intval($supAddon['lead_time']) < intval($supAddon['lead_time_max']))
                                                    {
                                                        $supAdd['lead_time_min'] = $supAddon['lead_time'];
                                                        $supAdd['lead_time_max'] = $supAddon['lead_time_max'];
                                                    }
                                                }
                                                else
                                                {
                                                    $supAdd['lead_time_min'] = $supAddon['lead_time'];
                                                    $supAdd['lead_time_max'] = $supAddon['lead_time_max'];
                                                }
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
                (new UserActivityController)->createActivity('Vendor Created');
                return response()->json(['success' => true,'data' => $data], 200);
            }
            else{
                $suppliers = $this->createSupplier($request);
                $data['successStore'] = true;
                (new UserActivityController)->createActivity('Vendor Created');
                return response()->json(['success' => true,'data' => $data], 200);
            }

        }
    }
    public function createSupplier(Request $request)
    {
        $input = $request->all();

        $input['contact_number'] = $request->contact_number['full'];
        $input['alternative_contact_number'] = $request->alternative_contact_number['full'];
        $input['phone'] = $request->phone['full'];
        $input['office_phone'] = $request->office_phone['full'];
        $input['created_by'] = Auth::id();
        $input['is_communication_fax'] = $request->is_communication_fax ? true : false;
        $input['is_communication_mobile'] = $request->is_communication_mobile ? true : false;
        $input['is_communication_email'] = $request->is_communication_email ? true : false;
        $input['is_communication_postal'] = $request->is_communication_postal ? true : false;
        $input['is_communication_any'] = $request->is_communication_any ? true : false;
        $input['is_MMC'] = $request->is_mmc ? true : false;
        $input['is_AMS'] = $request->is_ams ? true : false;

        if($request->form_action == 'UPDATE') {

        if($request->deletedDocuments[0] !== NULL) {
            foreach ($request->deletedDocuments as $deletedDocument) {
                $document = VendorDocument::find($deletedDocument);
                $document->delete();
            }
        }
    }

        if($request->form_action == 'UPDATE') {
            $suppliers = Supplier::find($request->supplier_id);
            if($request->is_passport_delete = true && $suppliers->passport_copy_file) {
                if (file_exists(public_path('vendor/passport/'.$suppliers->passport_copy_file))){
                    $filedeleted = unlink(public_path('vendor/passport/'.$suppliers->passport_copy_file));
                    if ($filedeleted) {
                        $input['passport_copy_file'] = NULL;
                    }
                }
            }
            if($request->is_trade_license_delete = true && $suppliers->trade_license_file) {
                if (file_exists(public_path('vendor/trade_license/'.$suppliers->trade_license_file))){
                    $filedeleted = unlink(public_path('vendor/trade_license/'.$suppliers->trade_license_file));
                    if ($filedeleted) {
                        $input['trade_license_file'] = NULL;
                    }
                }
            }
            if($request->is_vat_delete = true && $suppliers->vat_certificate_file) {
                if (file_exists(public_path('vendor/vat_certificate/'.$suppliers->vat_certificate_file))){
                    $filedeleted = unlink(public_path('vendor/vat_certificate/'.$suppliers->vat_certificate_file));
                    if ($filedeleted) {
                        $input['vat_certificate_file'] = NULL;
                    }
                }
            }
        }

        if ($request->hasFile('passport_copy_file'))
        {
            if($request->form_action == 'UPDATE' && $suppliers->passport_copy_file) {
                if (file_exists(public_path('vendor/passport/' . $suppliers->passport_copy_file))) {
                   unlink(public_path('vendor/passport/' . $suppliers->passport_copy_file));
                }
            }
            $file = $request->file('passport_copy_file');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'.'.$extension;
            $destinationPath = 'vendor/passport';
            $file->move($destinationPath, $fileName);

            $input['passport_copy_file'] = $fileName;
        }
        if ($request->hasFile('trade_license_file'))
        {
            if($request->form_action == 'UPDATE' && $suppliers->trade_license_file) {
                if (file_exists(public_path('vendor/trade_license/' . $suppliers->trade_license_file))) {
                    unlink(public_path('vendor/trade_license/' . $suppliers->trade_license_file));
                }
            }
            $file = $request->file('trade_license_file');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'.'.$extension;
            $destinationPath = 'vendor/trade_license';
            $file->move($destinationPath, $fileName);
            $input['trade_license_file'] = $fileName;

        }
        if ($request->hasFile('vat_certificate_file'))
        {
            if($request->form_action == 'UPDATE' && $suppliers->vat_certificate_file) {
                if (file_exists(public_path('vendor/vat_certificate/' . $suppliers->vat_certificate_file))) {
                    unlink(public_path('vendor/vat_certificate/' . $suppliers->vat_certificate_file));
                }
            }
            $file = $request->file('vat_certificate_file');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'.'.$extension;
            $destinationPath = 'vendor/vat_certificate';
            $file->move($destinationPath, $fileName);
            $input['vat_certificate_file'] = $fileName;
        }
        if($request->form_action == 'UPDATE') {
            $suppliers = Supplier::find($request->supplier_id);

            $suppliers->update($input);
        }else{
            $suppliers = Supplier::create($input);
        }

        if ($request->hasFile('documents'))
        {
            foreach ($request->file('documents') as $file)
            {
                $extension = $file->getClientOriginalExtension();
                $fileName = time().'_'.$file->getClientOriginalName();
                $destinationPath = 'vendor/other-documents';
                $file->move($destinationPath, $fileName);

                $vendorDocument = new VendorDocument();
                $vendorDocument->supplier_id = $suppliers->id;
                $vendorDocument->file = $fileName;
                $vendorDocument->save();
            }
        }
        if($request->categories) {
            if($request->form_action == 'UPDATE') {
                $existingVendorCategories = VendorCategory::where('supplier_id',$request->supplier_id)->delete();
            }
            foreach ($request->categories as $categoryItem) {
                $category = new VendorCategory();
                $category->supplier_id = $suppliers->id;
                $category->category = $categoryItem;
                $category->save();
            }

        }
        if($request->payment_methods) {
            if($request->form_action == 'UPDATE') {
                $existingPaymentMethods = SupplierAvailablePayments::where('supplier_id',$request->supplier_id)->delete();
            }
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
            if($request->form_action == 'UPDATE') {
                $existingSupplierTypes = SupplierType::where('supplier_id', $request->supplier_id)->delete();
            }
            foreach ($request->supplier_types as $supplierType)
            {
                $supplier_type = new SupplierType();
                $supplier_type->supplier_id = $suppliers->id;
                $supplier_type->supplier_type = $supplierType;
                $supplier_type->created_by = Auth::id();
                $supplier_type->save();
            }
        }



        return $suppliers;
    }
    public function updateDetails(Request $request)
    {
        (new UserActivityController)->createActivity('Updated Vendor Details');
        $payment_methods_id = $addon_id = [];
        $authId = Auth::id();
        $validator = Validator::make($request->all(), [
            'supplier' => 'required',
            'contact_number' => 'required',
            'supplier_types' => 'required',
            'categories' => 'required',
            'email' => 'required',
        ]);
        if ($validator->fails())
        {
            return redirect(route('suppliers.create'))->withInput()->withErrors($validator);
        }
        else {

            $request['form_action'] = 'UPDATE';
            $supplierTypeInput = $request->supplier_types;
            $input = $request->all();

            if($request->activeTab == 'uploadExcel')
            {
                if($request->file('file'))
                {
                    $headings = (new HeadingRowImport)->toArray($request->file('file'));
                    if(count($headings) > 0)
                    {
                        foreach($headings[0] as $heading)
                        {
                            if(in_array('addon_code', $heading) && in_array('currency', $heading) && in_array('purchase_price', $heading)
                            && in_array('lead_time_min', $heading) && in_array('lead_time_max', $heading))
                            {
                                Excel::import(new SupplierAddonImport,request()->file('file'));
                                $dataError = [];
                                $rows = SupplierAddonTemp::all();
                                $existingAddon = [];
                                for ($i=0; $i< count($rows); $i++)
                                {
                                    $currencyError = $priceErrror = $addonError = $minLeadTime = $maxLeadTime ='';
                                    if($rows[$i]['currency'] OR $rows[$i]['purchase_price'] OR $rows[$i]['addon_code'] OR $rows[$i]['lead_time_min'] OR $rows[$i]['lead_time_max'])
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
                                        if($rows[$i]['lead_time_min'] != '' && !is_numeric($rows[$i]['lead_time_min']) && strlen($rows[$i]['lead_time_min']) > 3)
                                        {
                                            $minLeadTime = "Number with maximum 3 digits expected as Minimum Lead Time ";
                                        }
                                        if($rows[$i]['lead_time_max'] != '' && !is_numeric($rows[$i]['lead_time_max']) && strlen($rows[$i]['lead_time_min']) > 3)
                                        {
                                            $maxLeadTime = "Number with maximum 3 digits expected as Minimum Lead Time ";
                                        }
                                        if($rows[$i]['lead_time_min'] != '' && is_numeric($rows[$i]['lead_time_min']) && strlen($rows[$i]['lead_time_min']) <= 3
                                        && $rows[$i]['lead_time_max'] != '' && is_numeric($rows[$i]['lead_time_max']) && strlen($rows[$i]['lead_time_min']) <= 3)
                                        {
                                            if(intval($rows[$i]['lead_time_max']) > $rows[$i]['lead_time_min'])
                                            {
                                                $maxLeadTime = "Greater than minimum leadtime expected";
                                            }
                                        }
                                        if($currencyError != '' OR $priceErrror != '' OR $addonError != '' OR $minLeadTime != '' OR $maxLeadTime != '')
                                        {
                                            array_push($dataError, ["addon_code" => $rows[$i]['addon_code'], "addonError" => $addonError,
                                                                    "currency" => $rows[$i]['currency'], "currencyError" => $currencyError,
                                                                    "purchase_price" => $rows[$i]['purchase_price'], "priceErrror" => $priceErrror,
                                                                    "lead_time_min" => $rows[$i]['lead_time_min'], "minLeadTimeErrror" => $minLeadTime,
                                                                    "lead_time_max" => $rows[$i]['lead_time_max'], "maxLeadTimeErrror" => $maxLeadTime,
                                                                ]);
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
                                    $suppliers = $this->createSupplier($request);

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
                                                        info("Uploading excel headings should be addon_code , currency and purchase_price");
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    $data['successStore'] = true;
                                    (new UserActivityController)->createActivity('Vendor Updated');
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
                    $suppliers = $this->createSupplier($request);

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
                                    if(in_array('addon_code', $heading) && in_array('currency', $heading) && in_array('purchase_price', $heading)
                                    && in_array('lead_time_min', $heading) && in_array('lead_time_max', $heading))
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
                                            if($supplierAddon->lead_time != '' && $supplierAddon->lead_time_max != '')
                                            {
                                                if(intval($supplierAddon->lead_time) == intval($supplierAddon->lead_time_max))
                                                {
                                                    $supAdd['lead_time_min'] = $supplierAddon->lead_time;
                                                    $supAdd['lead_time_max'] = NULL;
                                                }
                                                elseif(intval($supplierAddon->lead_time) < intval($supplierAddon->lead_time_max))
                                                {
                                                    $supAdd['lead_time_min'] = $supplierAddon->lead_time;
                                                    $supAdd['lead_time_max'] = $supplierAddon->lead_time_max;
                                                }
                                            }
                                            else
                                            {
                                                $supAdd['lead_time_min'] = $supplierAddon->lead_time;
                                                $supAdd['lead_time_max'] = $supplierAddon->lead_time_max;
                                            }
                                            $suppliers = SupplierAddons::create($supAdd);
                                            $supAdd['supplier_addon_id'] = $suppliers->id;
                                            $createHistory = PurchasePriceHistory::create($supAdd);
                                        }
                                    }
                                    else
                                    {
                                        info("Uploading excel headings should be addon_code , currency and purchase_price");
                                    }
                                }
                            }
                        }
                    }
                    $data['successStore'] = true;
                    (new UserActivityController)->createActivity('Vendor Updated');
                    return response()->json(['success' => true,'data' => $data], 200);
                }
            }
            elseif($request->activeTab == 'addSupplierDynamically')
            {
                $suppliers = $this->createSupplier($request);

                $supplier_addon['supplier_id'] = $suppliers->id;
                $isupplier_addonnput['updated_by'] = $authId;
                $addon_id = $request->addon_id;
                // if($addon_id != NULL)
                // {
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
                            if(isset($supAddon['addon_purchase_price_in_usd'] ) OR isset($supAddon['addon_purchase_price'] ))
                            {
                                if($supAddon['currency'] != '' AND isset($supAddon['addon_id']))
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
                                                if($supAddon['lead_time'] != '' && $supAddon['lead_time_max'] != '')
                                                {
                                                    if(intval($supAddon['lead_time']) == intval($supAddon['lead_time_max']))
                                                    {
                                                        $supAdd['lead_time_min'] = $supAddon['lead_time'];
                                                        $supAdd['lead_time_max'] = NULL;
                                                    }
                                                    elseif(intval($supAddon['lead_time']) < intval($supAddon['lead_time_max']))
                                                    {
                                                        $supAdd['lead_time_min'] = $supAddon['lead_time'];
                                                        $supAdd['lead_time_max'] = $supAddon['lead_time_max'];
                                                    }
                                                }
                                                else
                                                {
                                                    $supAdd['lead_time_min'] = $supAddon['lead_time'];
                                                    $supAdd['lead_time_max'] = $supAddon['lead_time_max'];
                                                }
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
                (new UserActivityController)->createActivity('Vendor Updated');
                return response()->json(['success' => true,'data' => $data], 200);
            } else{
                $suppliers = $this->createSupplier($request);
                $data['successStore'] = true;
                (new UserActivityController)->createActivity('Vendor Created');
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

            $data['successStore'] = true;
            (new UserActivityController)->createActivity('Vendor Updated');
            return response()->json(['success' => true,'data' => $data], 200);
        }
    }
    public function vendorUniqueCheck(Request $request)
    {
        $contactNumber = $request->contact_number;
        if(in_array(Supplier::SUPPLIER_TYPE_DEMAND_PLANNING, $request->supplierType)) {
            $isVendorExist = Supplier::where('supplier', $request->name);

        }else{
            $isVendorExist = Supplier::select('contact_number','supplier','id')
                ->whereIn('contact_number', [$contactNumber,Supplier::MIGRATED_SUPPLIER_DUMMY_CONTACT_NUMBER])
                ->where('supplier', $request->name);
              
        }
        if($request->id) {
            $isVendorExist = $isVendorExist->whereNot('id', $request->id);
        }
        $isVendorExist = $isVendorExist->first();
      
        $data = [];
        if($isVendorExist) {
            if(in_array(Supplier::SUPPLIER_TYPE_DEMAND_PLANNING, $request->supplierType)) {
                $data['name_error'] = 'Supplier Name already existing';
                $data['error'] = "";
            }else{
                $data['error'] = 'Combination of Name and Contact Number('.$isVendorExist->contact_number.') already existing.';
                $data['name_error'] = "";
            }
            return response($data);
        }else{
            return response($data);
        }
    }
    public function getVendorSubCategories(Request $request) {
        if($request->categories) {
            $categoryIds  = MasterVendorCategory::whereIn('name', $request->categories)->pluck('id')->toArray();
            $subCategories = MasterVendorSubCategory::whereIn('master_vendor_category_id', $categoryIds)->get();
        }else{
            $subCategories = [];
        }

        return $subCategories;
    }
}
