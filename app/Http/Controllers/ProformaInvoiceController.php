<?php

namespace App\Http\Controllers;

use App\Models\OtherLogisticsCharges;
use App\Models\Shipping;
use App\Models\ShippingCertification;
use App\Models\ShippingDocuments;
use Illuminate\Http\Request;
use App\Models\Calls;
use App\Models\Brand;
use App\Models\AddonDescription;
use App\Models\MasterModelLines;
use App\Models\MasterModelDescription;
use App\Models\AddonDetails;
use App\Models\SupplierAddons;
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
        $accessoriesBrands = Brand::whereHas('addons', function($q) {
            $q->whereHas('Addons', function($query) {
                $query->where('addon_type_name','P');
            });
        })->get();
        $sparePartsBrands = Brand::whereHas('addons', function($q) {
            $q->whereHas('Addons', function($query) {
                $query->where('addon_type_name','SP');
            });
        })->get();
        $kitsBrands = Brand::whereHas('addons', function($q) {
            $q->whereHas('Addons', function($query) {
                $query->where('addon_type_name','K');
            });
        })->get();
        $shippings = Shipping::all();
        $shippingDocuments = ShippingDocuments::all();
        $certifications = ShippingCertification::all();
        $otherDocuments = OtherLogisticsCharges::all();
        return view('proforma.invoice', compact('callDetails', 'brands','assessoriesDesc',
            'sparePartsDesc','kitsDesc','accessoriesBrands','sparePartsBrands', 'kitsBrands','shippings','certifications',
           'otherDocuments', 'shippingDocuments'));
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
            }
        }
        $accessories = $accessories->with('AddonDescription.Addon','AddonTypes.brands','SellingPrice', 'PendingSellingPrice')->get();
        foreach($accessories as $addon) {
            $price = $totalPrice = '';
            if($addon->addon_type_name == 'P' OR $addon->addon_type_name == 'SP') {
                $price = SupplierAddons::where('addon_details_id',$addon->id)->where('status','active')->orderBy('purchase_price_aed','ASC')->first();
                $addon->LeastPurchasePrices = $price;
            }
            else if($addon->addon_type_name == 'K') {
                $supplierAddonDetails = [];
                $supplierAddonDetails = AddonDetails::where('id',$addon->id)->with('AddonName','AddonTypes.brands','SellingPrice','KitItems.addon.AddonDescription')->first();
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
            }
        }
        return response()->json($accessories);
    }
    public function getbookingSpareParts($addonId, $brandId, $modelLineId, $ModelDescriptionId) {
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
        $spare_parts = $spare_parts->get();
        return response()->json($spare_parts);
    }
    public function getbookingKits($addonId, $brandId, $modelLineId, $ModelDescriptionId) {
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
        $kits = $kits->get();
        return response()->json($kits);
    }
}
