<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\OtherLogisticsCharges;
use App\Models\Setting;
use App\Models\Shipping;
use App\Models\ShippingCertification;
use App\Models\ShippingDocuments;
use App\Models\ShippingMedium;
use Illuminate\Http\Request;
use App\Models\Calls;
use App\Models\Brand;
use App\Models\AddonDescription;
use App\Models\MasterModelLines;
use App\Models\MasterModelDescription;
use App\Models\AddonDetails;
use App\Models\SupplierAddons;
use App\Models\AddonTypes;
use App\Models\Addon;
class ProformaInvoiceController extends Controller {
    public function proforma_invoice($callId) {
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
        $shippings = ShippingMedium::all();
        $shippingDocuments = ShippingDocuments::all();
        $certifications = ShippingCertification::all();
        $otherDocuments = OtherLogisticsCharges::all();
        $aed_to_eru_rate = Setting::where('key', 'aed_to_euro_convertion_rate')->first();
        $aed_to_usd_rate = Setting::where('key', 'aed_to_usd_convertion_rate')->first();
        $usd_to_eru_rate = Setting::where('key', 'usd_to_euro_convertion_rate')->first();

        return view('proforma.invoice', compact('callDetails', 'brands','assessoriesDesc',
            'sparePartsDesc','kitsDesc','shippings','certifications','countries',
           'otherDocuments', 'shippingDocuments','aed_to_eru_rate','aed_to_usd_rate','usd_to_eru_rate'));
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
        }
        return response()->json($accessories);
    }
    public function getbookingSpareParts($addonId, $brandId, $modelLineId, $ModelDescriptionId) {
        $brandName = $modelLine = $modelDescription = '';
        $brandName = Brand::where('id',$brandId)->first();
        $modelLine = MasterModelLines::where('id',$modelLineId)->first();
        $modelDescription = MasterModelDescription::where('id',$ModelDescriptionId)->first();
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
        }
        return response()->json($kits);
    }
    public function addonsModal($modelLineId)
    {
        info($modelLineId);
        $modelLineId = $modelLineId;
        $brands = MasterModelLines::where('id', $modelLineId)->pluck('brand_id')->first();
        $assessoriesDesc = Addon::where('addon_type','P')->get();
        $sparePartsDesc = Addon::where('addon_type','SP')->get();
        $kitsDesc = Addon::where('addon_type','K')->get();
        return response()->json([
            'assessoriesDesc' => $assessoriesDesc,
            'modelLineId' => $modelLineId,
            'brands' => $brands,
            'sparePartsDesc' => $sparePartsDesc,
            'kitsDesc' => $kitsDesc,
        ]);
    }
    }
