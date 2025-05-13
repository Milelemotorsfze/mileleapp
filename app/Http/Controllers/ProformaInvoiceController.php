<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\MasterShippingPorts;
use App\Models\OtherLogisticsCharges;
use App\Models\Setting;
use App\Models\Quotation;
use App\Models\ModelHasRoles;
use App\Models\QuotationClient;
use App\Models\QuotationDetail;
use App\Models\QuotationItem;
use App\Models\QuotationSubItem;
use App\Models\Shipping;
use Illuminate\Support\Facades\DB;
use App\Models\ShippingCertification;
use App\Models\ShippingDocuments;
use App\Models\ShippingMedium;
use Illuminate\Http\Request;
use App\Models\Calls;
use App\Models\QuotationVins;
use App\Models\Brand;
use App\Models\AddonDescription;
use App\Models\MasterModelLines;
use App\Models\MasterModelDescription;
use App\Models\AddonDetails;
use App\Models\SupplierAddons;
use App\Models\AddonTypes;
use App\Models\Varaint;
use Illuminate\Support\Facades\Auth;
use App\Models\Addon;
use App\Models\User;
class ProformaInvoiceController extends Controller {
    public function proforma_invoice($callId) {
        $brands = Brand::all();
        $callDetails = Calls::where('id', $callId)->first();
        $currentUser = Auth::user();
        $hasPermission = $currentUser->hasPermissionForSelectedRole('all-quotation-access');
        if ($callDetails->sales_person !== $currentUser->id && !$hasPermission) {
        return redirect()->route('not_access_page');
         }
        $assessoriesDesc = AddonDescription::whereHas('Addon', function($q) {
            $q->where('addon_type','P');
        })->get();
        $sparePartsDesc = AddonDescription::whereHas('Addon', function($q) {
            $q->where('addon_type','SP');
        })->get();
        $kitsDesc = AddonDescription::whereHas('Addon', function($q) {
            $q->where('addon_type','K');
        })->get();
        $sales_persons = User::where('pfi_access', 1)->where('status','active')
        ->orderBy('name', 'asc')
        ->get();
        $countries = Country::all();
        $shippingPorts = MasterShippingPorts::all();
        $shippings = ShippingMedium::all();
        $shippingDocuments = ShippingDocuments::all();
        $certifications = ShippingCertification::all();
        $otherDocuments = OtherLogisticsCharges::all();
        $aed_to_eru_rate = Setting::where('key', 'aed_to_euro_convertion_rate')->first();
        $aed_to_usd_rate = Setting::where('key', 'aed_to_usd_convertion_rate')->first();
        $usd_to_eru_rate = Setting::where('key', 'usd_to_euro_convertion_rate')->first();

        return view('proforma.invoice', compact('callDetails', 'brands','assessoriesDesc',
            'sparePartsDesc','kitsDesc','shippings','certifications','countries','shippingPorts',
           'otherDocuments', 'shippingDocuments','aed_to_eru_rate','aed_to_usd_rate','usd_to_eru_rate', 'sales_persons'));
    }
    public function getaddonModels(Request $request, $brandId, $type) {
        $modelLines = MasterModelLines::where('brand_id', $brandId)
        // ->whereHas('addons', function($q) use($type) {
        //     $q->whereHas('Addons', function($query) use($type) {
        //         $query->where('addon_type_name',$type);
        //     });
        // });
        ->pluck('model_line', 'id');
        return response()->json($modelLines);
    }
    public function getaddonModelDescriptions(Request $request, $modelLineId, $type) {
        $modelDescriptions = MasterModelDescription::where('model_line_id', $modelLineId)
        ->pluck('model_description', 'id');
        return response()->json($modelDescriptions);
    }
    public function getbookingAccessories($addonId,$brandId,$modelLineId) {
        $brandName = $modelLine = '';
        $accessories = AddonDetails::where('addon_type_name','P');
        if($addonId != 'addonId') {
            $accessories = $accessories->where('description',$addonId);
        }
        if($brandId != 'brandId') {
            if($brandId == 'allbrands') {
                $accessories = $accessories->where('is_all_brands','yes');
            }
            else {
                $accessories = $accessories->where(function ($query) use($modelLineId,$brandId) {
                    $query->where('is_all_brands','yes')
                    ->orWhere('is_all_brands','no')->whereHas('AddonTypes', function($q) use($modelLineId,$brandId) {
                        $q = $q->where('brand_id',$brandId);
                        if($modelLineId != 'modelLineId') {
                            if($modelLineId == 'allmodellines') {
                                $q = $q->where('is_all_model_lines','yes');
                            }
                            else {
                                $q->where( function ($query) use ($modelLineId) {
                                    $query = $query->where('model_id',$modelLineId);
                                });
                            }
                        }
                    });
                });
                $brandName = Brand::where('id',$brandId)->first();
                $modelLine = MasterModelLines::where('id',$modelLineId)->first();
            }
        }
        $accessories = $accessories->with('AddonDescription.Addon','SellingPrice', 'PendingSellingPrice')->get();
        foreach($accessories as $addon) {
            $price = $totalPrice = '';
            $price = SupplierAddons::where('addon_details_id',$addon->id)->where('status','active')->orderBy('purchase_price_aed','ASC')->first();
            $addon->LeastPurchasePrices = $price;
            $existingBrandId = [];
            $existingBrandModel = [];
            if($addon->is_all_brands == 'no') {
                $existingBrandModel = AddonTypes::where('addon_details_id',$addon->id)->groupBy('brand_id')->with('brands')->get();
                foreach($existingBrandModel as $data) {
                    array_push($existingBrandId,$data->brand_id);
                    $jsonmodelLine = [];
                    $data->ModelLine = AddonTypes::where([
                        ['addon_details_id','=',$addon->id],
                        ['brand_id','=',$data->brand_id]
                        ])->groupBy('model_id')->with('modelLines')->get();
                        $data->ModelLine->modeldes = [];
                    if($data->is_all_model_lines == 'no') {
                        foreach($data->ModelLine as $mo) {
                            $mo->allDes = MasterModelDescription::where('model_line_id',$mo->model_id)->get();
                            $mo->modeldes = AddonTypes::where([
                                ['addon_details_id','=',$addon->id],
                                ['brand_id','=',$mo->brand_id],
                                ['model_id','=',$mo->model_id],
                                ])->pluck('model_number');
                                $mo->modeldes = json_decode($mo->modeldes);
                        }
                    }
                    $modelLinesData = AddonTypes::where([
                                                        ['addon_details_id','=',$addon->id],
                                                        ['brand_id','=',$data->brand_id]
                                                        ])->pluck('model_id');
                    $jsonmodelLine = json_decode($modelLinesData);
                    $data->modelLinesData = $jsonmodelLine;
                    $data->ModalLines = MasterModelLines::where('brand_id',$data->brand_id)->get();
                }
                $addon->brandModelLine = $existingBrandModel;
            }
            if($brandName != '' && $modelLine != '') {
                $addon->brandModelLine = $brandName->brand_name.' - '.$modelLine->model_line;
            }
            else if($brandName != '')
                    {
                        $addon->brandModelLine = $brandName->brand_name;
                    }
                    else
                    {
                        $addon->brandModelLine = "All Brands";
                    }
        }
        return response()->json($accessories);
    }
    public function getbookingSpareParts($addonId, $brandId, $modelLineId, $ModelDescriptionId) {
        $brandName = $modelLine = $modelDescription = '';
        $brandName = Brand::where('id',$brandId)->first();
        $modelLine = MasterModelLines::where('id',$modelLineId)->first();
        if($ModelDescriptionId != 'ModelDescriptionId') {
            $modelDescription = MasterModelDescription::where('id',$ModelDescriptionId)->first(); 
        }
        $spare_parts = AddonDetails::where('addon_type_name','SP');
        if($addonId != 'addonId') {
            $spare_parts = $spare_parts->where('description',$addonId);
        }
        if($brandId != 'brandId') {
            $spare_parts = $spare_parts->whereHas('AddonTypes', function($q) use($brandId,$modelLineId,$ModelDescriptionId) {
                $q = $q->where('brand_id',$brandId);
                if($modelLineId != 'modelLineId') {
                    $q = $q->where('model_id',$modelLineId);
                }
                if($ModelDescriptionId != 'ModelDescriptionId') {
                    $q = $q->where('model_number',$ModelDescriptionId);
                }
            });
        }
        $spare_parts = $spare_parts->with('AddonDescription.Addon','SellingPrice', 'PendingSellingPrice','partNumbers')->get();
        foreach($spare_parts as $addon) {
            $price = $totalPrice = '';
            $price = SupplierAddons::where('addon_details_id',$addon->id)->where('status','active')->orderBy('purchase_price_aed','ASC')->first();
            $addon->LeastPurchasePrices = $price;
            $existingBrandId = [];
            $existingBrandModel = [];
            if($addon->is_all_brands == 'no') {
                $existingBrandModel = AddonTypes::where('addon_details_id',$addon->id)->groupBy('brand_id')->with('brands')->get();
                foreach($existingBrandModel as $data) {
                    array_push($existingBrandId,$data->brand_id);
                    $jsonmodelLine = [];
                    $data->ModelLine = AddonTypes::where([
                        ['addon_details_id','=',$addon->id],
                        ['brand_id','=',$data->brand_id]
                        ])->groupBy('model_id')->with('modelLines')->get();
                        $data->ModelLine->modeldes = [];
                    if($data->is_all_model_lines == 'no') {
                        foreach($data->ModelLine as $mo) {
                            $mo->allDes = MasterModelDescription::where('model_line_id',$mo->model_id)->get();
                            $mo->modeldes = AddonTypes::where([
                                ['addon_details_id','=',$addon->id],
                                ['brand_id','=',$mo->brand_id],
                                ['model_id','=',$mo->model_id],
                                ])->pluck('model_number');
                                $mo->modeldes = json_decode($mo->modeldes);
                        }
                    }
                    $modelLinesData = AddonTypes::where([
                                                        ['addon_details_id','=',$addon->id],
                                                        ['brand_id','=',$data->brand_id]
                                                        ])->pluck('model_id');
                    $jsonmodelLine = json_decode($modelLinesData);
                    $data->modelLinesData = $jsonmodelLine;
                    $data->ModalLines = MasterModelLines::where('brand_id',$data->brand_id)->get();
                }
                $addon->brandModelLine = $existingBrandModel;
                if($brandName != '' && $modelLine != '' && $modelDescription != '') {
                    $addon->brandModelLineModelDescription = $brandName->brand_name.' - '.$modelLine->model_line.' , '.$modelDescription->model_description;
                }
                else if($brandName != '' && $modelLine != '') {
                    $addon->brandModelLineModelDescription = $brandName->brand_name.' - '.$modelLine->model_line;
                }
                else if($brandName != '')
                    {
                        $addon->brandModelLineModelDescription = $brandName->brand_name;
                    }
                    else
                    {
                        $addon->brandModelLineModelDescription = "All Brands";
                    }
            }
        }
        return response()->json($spare_parts);
    }
    public function getbookingKits($addonId, $brandId, $modelLineId, $ModelDescriptionId) {
        $brandName = $modelLine = $modelDescription = '';
        $brandName = Brand::where('id',$brandId)->first();
        $modelLine = MasterModelLines::where('id',$modelLineId)->first();
        $modelDescription = MasterModelDescription::where('id',$ModelDescriptionId)->first();
        $kits = AddonDetails::where('addon_type_name','K');
        if($addonId != 'addonId') {
            $kits = $kits->where('description',$addonId);
        }
        if($brandId != 'brandId') {
            $kits = $kits->whereHas('AddonTypes', function($q) use($brandId,$modelLineId,$ModelDescriptionId) {
                $q = $q->where('brand_id',$brandId);
                if($modelLineId != 'modelLineId') {
                    $q = $q->where('model_id',$modelLineId);
                }
                if($ModelDescriptionId != 'ModelDescriptionId') {
                    $q = $q->where('model_number',$ModelDescriptionId);
                }
            });
        }
        $kits = $kits->with('AddonName','SellingPrice','KitItems.addon.AddonDescription','KitItems.item.Addon')->get();
        foreach($kits as $addon) {
            $price = $totalPrice = '';
            $supplierAddonDetails = [];
            $supplierAddonDetails = AddonDetails::where('id',$addon->id)->with('AddonName','SellingPrice','KitItems.item.Addon')->first();
            $totalPrice = 0;
            $totalPriceTrue = 'yes';
            foreach($supplierAddonDetails->KitItems as $oneItem) {
                if($oneItem->kit_item_total_purchase_price != 0) {
                    $totalPrice = $totalPrice + $oneItem->kit_item_total_purchase_price;
                }
                else {
                    $totalPriceTrue = 'no';
                }
            }
            if($totalPriceTrue == 'yes' && $totalPrice != 0) {
                $addon->LeastPurchasePrices = $totalPrice;
            }
            else {
                $addon->LeastPurchasePrices = '';
            }
            $existingBrandId = [];
            $existingBrandModel = [];
            if($addon->is_all_brands == 'no') {
                $existingBrandModel = AddonTypes::where('addon_details_id',$addon->id)->groupBy('brand_id')->with('brands')->get();
                foreach($existingBrandModel as $data) {
                    array_push($existingBrandId,$data->brand_id);
                    $jsonmodelLine = [];
                    $data->ModelLine = AddonTypes::where([
                        ['addon_details_id','=',$addon->id],
                        ['brand_id','=',$data->brand_id]
                        ])->groupBy('model_id')->with('modelLines')->get();
                        $data->ModelLine->modeldes = [];
                    if($data->is_all_model_lines == 'no') {
                        foreach($data->ModelLine as $mo) {
                            $mo->allDes = MasterModelDescription::where('model_line_id',$mo->model_id)->get();
                            $mo->modeldes = AddonTypes::where([
                                ['addon_details_id','=',$addon->id],
                                ['brand_id','=',$mo->brand_id],
                                ['model_id','=',$mo->model_id],
                                ])->pluck('model_number');
                                $mo->modeldes = json_decode($mo->modeldes);
                        }
                    }
                    $modelLinesData = AddonTypes::where([
                                                        ['addon_details_id','=',$addon->id],
                                                        ['brand_id','=',$data->brand_id]
                                                        ])->pluck('model_id');
                    $jsonmodelLine = json_decode($modelLinesData);
                    $data->modelLinesData = $jsonmodelLine;
                    $data->ModalLines = MasterModelLines::where('brand_id',$data->brand_id)->get();
                }
                $addon->brandModelLine = $existingBrandModel;
            }
            if($brandName != '' && $modelLine != '' && $modelDescription != '') {
                $addon->brandModelLineModelDescription = $brandName->brand_name.' , '.$modelLine->model_line.' , '.$modelDescription->model_description;
            }
            else if($brandName != '' && $modelLine != '') {
                $addon->brandModelLineModelDescription = $brandName->brand_name.' - '.$modelLine->model_line;
            }
            else if($brandName != '')
                {
                    $addon->brandModelLineModelDescription = $brandName->brand_name;
                }
                else
                {
                    $addon->brandModelLineModelDescription = "All Brands";
                }
        }
        return response()->json($kits);
    }
    public function addonsModal($modelLineId)
    {
        $modaltype = request()->input('modaltype');
        if($modaltype == "Brand"){
            $modelLineId = $modelLineId;
            $brands = Brand::where('id', $modelLineId)->first();
            $assessoriesDesc = DB::table('addon_descriptions')
            ->join('addons', 'addons.id', '=', 'addon_descriptions.addon_id')
            ->select('addons.name as name', 'addon_descriptions.id as id', 'addons.id as ids' , 'addon_descriptions.description as description')
            ->where('addons.addon_type', 'P')
            ->get();
            $sparePartsDesc = DB::table('addon_descriptions')
            ->join('addons', 'addons.id', '=', 'addon_descriptions.addon_id')
            ->select('addons.name as name', 'addon_descriptions.id as id', 'addons.id as ids', 'addon_descriptions.description as description')
            ->where('addons.addon_type', 'SP')
            ->get();
            $kitsDesc = DB::table('addon_descriptions')
            ->join('addons', 'addons.id', '=', 'addon_descriptions.addon_id')
            ->select('addons.name as name', 'addon_descriptions.id as id', 'addons.id as ids' , 'addon_descriptions.description as description')
            ->where('addons.addon_type', 'K')
            ->get();
            return response()->json([
                'assessoriesDesc' => $assessoriesDesc,
                'modelLineId' => "modelLineId",
                'brands' => $brands->id,
                'sparePartsDesc' => $sparePartsDesc,
                'kitsDesc' => $kitsDesc,
                'brand_name' => $brands->brand_name,
                'modelLineIdname' => "Other",
            ]);
        }
        else if ($modaltype == "ModelLine")
        {
        $modelLineId = $modelLineId;
        $modelLineIdname = MasterModelLines::where('id', $modelLineId)->pluck('model_line')->first();
        $brands = MasterModelLines::with('brand')->find($modelLineId);
        $assessoriesDesc = DB::table('addon_descriptions')
        ->join('addons', 'addons.id', '=', 'addon_descriptions.addon_id')
        ->select('addons.name as name', 'addon_descriptions.id as id', 'addons.id as ids' , 'addon_descriptions.description as description')
        ->where('addons.addon_type', 'P')
        ->get();
        $sparePartsDesc = DB::table('addon_descriptions')
        ->join('addons', 'addons.id', '=', 'addon_descriptions.addon_id')
        ->select('addons.name as name', 'addon_descriptions.id as id', 'addons.id as ids', 'addon_descriptions.description as description')
        ->where('addons.addon_type', 'SP')
        ->get();
        $kitsDesc = DB::table('addon_descriptions')
        ->join('addons', 'addons.id', '=', 'addon_descriptions.addon_id')
        ->select('addons.name as name', 'addon_descriptions.id as id', 'addons.id as ids' , 'addon_descriptions.description as description')
        ->where('addons.addon_type', 'K')
        ->get();
        return response()->json([
            'assessoriesDesc' => $assessoriesDesc,
            'modelLineId' => $modelLineId,
            'brands' => $brands->brand->id,
            'sparePartsDesc' => $sparePartsDesc,
            'kitsDesc' => $kitsDesc,
            'brand_name' => $brands->brand->brand_name,
            'modelLineIdname' => $modelLineIdname,
        ]);
    }
    else if ($modaltype == "Vehicle")
        {
        $modelLineId = $modelLineId;
        $variantID = Varaint::where('id', $modelLineId)->pluck('master_model_lines_id')->first();
        $modelLineIdname = MasterModelLines::where('id', $variantID)->pluck('model_line')->first();
        $brands = MasterModelLines::with('brand')->find($variantID);
        $assessoriesDesc = DB::table('addon_descriptions')
        ->join('addons', 'addons.id', '=', 'addon_descriptions.addon_id')
        ->select('addons.name as name', 'addon_descriptions.id as id', 'addons.id as ids' , 'addon_descriptions.description as description')
        ->where('addons.addon_type', 'P')
        ->get();
        $sparePartsDesc = DB::table('addon_descriptions')
        ->join('addons', 'addons.id', '=', 'addon_descriptions.addon_id')
        ->select('addons.name as name', 'addon_descriptions.id as id', 'addons.id as ids', 'addon_descriptions.description as description')
        ->where('addons.addon_type', 'SP')
        ->get();
        $kitsDesc = DB::table('addon_descriptions')
        ->join('addons', 'addons.id', '=', 'addon_descriptions.addon_id')
        ->select('addons.name as name', 'addon_descriptions.id as id', 'addons.id as ids' , 'addon_descriptions.description as description')
        ->where('addons.addon_type', 'K')
        ->get();
        return response()->json([
            'assessoriesDesc' => $assessoriesDesc,
            'modelLineId' => $variantID,
            'brands' => $brands->brand->id,
            'sparePartsDesc' => $sparePartsDesc,
            'kitsDesc' => $kitsDesc,
            'brand_name' => $brands->brand->brand_name,
            'modelLineIdname' => $modelLineIdname,
        ]);
    }
    else
    {
        if($modelLineId != "undefined")
        {
            $modelLineId = $modelLineId;
            $modelLineIdname = MasterModelLines::where('id', $modelLineId)->pluck('model_line')->first();
            $brands = MasterModelLines::with('brand')->find($modelLineId);
            $assessoriesDesc = DB::table('addon_descriptions')
            ->join('addons', 'addons.id', '=', 'addon_descriptions.addon_id')
            ->select('addons.name as name', 'addon_descriptions.id as id', 'addons.id as ids' , 'addon_descriptions.description as description')
            ->where('addons.addon_type', 'P')
            ->get();
            $sparePartsDesc = DB::table('addon_descriptions')
            ->join('addons', 'addons.id', '=', 'addon_descriptions.addon_id')
            ->select('addons.name as name', 'addon_descriptions.id as id', 'addons.id as ids', 'addon_descriptions.description as description')
            ->where('addons.addon_type', 'SP')
            ->get();
            $kitsDesc = DB::table('addon_descriptions')
            ->join('addons', 'addons.id', '=', 'addon_descriptions.addon_id')
            ->select('addons.name as name', 'addon_descriptions.id as id', 'addons.id as ids' , 'addon_descriptions.description as description')
            ->where('addons.addon_type', 'K')
            ->get();
            return response()->json([
                'assessoriesDesc' => $assessoriesDesc,
                'modelLineId' => $modelLineId,
                'brands' => $brands->brand->id,
                'sparePartsDesc' => $sparePartsDesc,
                'kitsDesc' => $kitsDesc,
                'brand_name' => $brands->brand->brand_name,
                'modelLineIdname' => $modelLineIdname,
            ]);
        }
        else{
        $assessoriesDesc = DB::table('addon_descriptions')
        ->join('addons', 'addons.id', '=', 'addon_descriptions.addon_id')
        ->select('addons.name as name', 'addon_descriptions.id as id', 'addons.id as ids', 'addon_descriptions.description as description')
        ->where('addons.addon_type', 'P')
        ->get();
        $sparePartsDesc = DB::table('addon_descriptions')
        ->join('addons', 'addons.id', '=', 'addon_descriptions.addon_id')
        ->select('addons.name as name', 'addon_descriptions.id as id', 'addons.id as ids', 'addon_descriptions.description as description')
        ->where('addons.addon_type', 'SP')
        ->get();
        $kitsDesc = DB::table('addon_descriptions')
        ->join('addons', 'addons.id', '=', 'addon_descriptions.addon_id')
        ->select('addons.name as name', 'addon_descriptions.id as id', 'addons.id as ids', 'addon_descriptions.description as description')
        ->where('addons.addon_type', 'K')
        ->get();
        return response()->json([
            'assessoriesDesc' => $assessoriesDesc,
            'modelLineId' => "modelLineId",
            'brands' => "brand",
            'sparePartsDesc' => $sparePartsDesc,
            'kitsDesc' => $kitsDesc,
            'brand_name' => "Other",
            'modelLineIdname' => "Other",
        ]);  
    }
    }
    }
    public function proforma_invoice_edit($callId) {

        $quotation = Quotation::where('calls_id', $callId)->first();
        $salespersoncalls = Calls::where('id', $callId)->first();
        $currentUser = Auth::user();
        $hasPermission = $currentUser->hasPermissionForSelectedRole('all-quotation-access');
        if ($salespersoncalls->sales_person !== $currentUser->id && !$hasPermission) {
        return redirect()->route('not_access_page');
         }
        $quotation_details = QuotationDetail::where('quotation_id', $quotation->id)->first();
        $quotationitems = QuotationItem::with('varaint')->with('addon')->with('quotationVins')->with('shippingdocuments')->with('shippingcertification')->with('otherlogisticscharges')->where('quotation_id', $quotation->id)->get();
        $quotation_vins = [];
        foreach ($quotationitems as $quotationitem) {
            $quotation_vins = QuotationVins::where('quotation_items_id', $quotationitem->id)->get();
        }
        $brands = Brand::all();
        $callDetails = Calls::where('id', $callId)->first();
        $assessoriesDesc = AddonDescription::whereHas('Addon', function($q) {
            $q->where('addon_type','P');
        })->get();
        $sparePartsDesc = AddonDescription::whereHas('Addon', function($q) {
            $q->where('addon_type','SP');
        })->get();
        $kitsDesc = AddonDescription::whereHas('Addon', function($q) {
            $q->where('addon_type','K');
        })->get();
        $countries = Country::all();
        $shippingPorts = MasterShippingPorts::all();
        $shippings = ShippingMedium::all();
        $shippingDocuments = ShippingDocuments::all();
        $certifications = ShippingCertification::all();
        $otherDocuments = OtherLogisticsCharges::all();
        $aed_to_eru_rate = Setting::where('key', 'aed_to_euro_convertion_rate')->first();
        $aed_to_usd_rate = Setting::where('key', 'aed_to_usd_convertion_rate')->first();
        $usd_to_eru_rate = Setting::where('key', 'usd_to_euro_convertion_rate')->first();
        $sales_persons = User::where(function ($query) use ($quotation) {
            $query->where(function ($q) {
                $q->where('pfi_access', 1)
                  ->where('status', 'active');
            });
    
            if ($quotation && $quotation->created_by) {
                $query->orWhere('id', $quotation->created_by);
            }
        })
        ->orderBy('name', 'asc')
        ->get()
        ->unique('id')
        ->values();

        $existingItemsJson = json_encode($quotationitems);
        return view('proforma.invoice_edit', compact('callDetails', 'brands','assessoriesDesc',
            'sparePartsDesc','kitsDesc','shippings','certifications','countries','shippingPorts',
           'otherDocuments', 'shippingDocuments','aed_to_eru_rate','aed_to_usd_rate','usd_to_eru_rate', 'quotation_details', 'quotation_vins', 'quotation', 'quotationitems', 'existingItemsJson', 'callId', 'sales_persons'));
    }
    public function getNeighbors($id)
{
    $neighbors = Country::findOrFail($id)
                        ->neighbors()
                        ->get()
                        ->pluck('name', 'id');

    return response()->json($neighbors);
}
    }
