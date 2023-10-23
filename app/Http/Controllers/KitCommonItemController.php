<?php

namespace App\Http\Controllers;
use App\Models\AddonSellingPrice;
use App\Models\KitPriceHistory;
use App\Models\SparePartsNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\KitCommonItem;
use App\Models\Addon;
use App\Models\AddonDetails;
use App\Models\Brand;
use App\Models\MasterModelLines;
use App\Models\Supplier;
use App\Models\SupplierAddons;
use App\Models\PurchasePriceHistory;
use App\Models\KitItems;
use App\Models\AddonTypes;
use App\Models\SupplierType;
use App\Models\MasterModelDescription;
use App\Models\AddonDescription;
use App\Http\Controllers\UserActivityController;
class KitCommonItemController extends Controller {
    public function create() {
        $kitItemDropdown = Addon::whereIn('addon_type',['SP'])->pluck('id');
        $kitItemDropdown = AddonDetails::whereIn('addon_id', $kitItemDropdown)->with('AddonName')->get();
        $addons = Addon::whereIn('addon_type',['K'])->select('id','name')->orderBy('name', 'ASC')->get();
        $brands = Brand::select('id','brand_name')->get();
        $modelLines = MasterModelLines::select('id','brand_id','model_line')->get();
        $masterAddonByType = Addon::where('addon_type','K')->pluck('id');
        if($masterAddonByType != '') {
            $lastAddonCode = AddonDetails::whereIn('addon_id',$masterAddonByType)->withTrashed()->orderBy('id', 'desc')->first();
            if($lastAddonCode != '') {
                $lastAddonCodeNo =  $lastAddonCode->addon_code;
                $lastAddonCodeNumber = substr($lastAddonCodeNo, 1, 5);
                $newAddonCodeNumber =  $lastAddonCodeNumber+1;
                $newAddonCode = "K".$newAddonCodeNumber;
            }
            else {
                $newAddonCode = "K"."1";
            }
        }
        else {
            $newAddonCode = "K"."1";
        }
        return view('kit.create',compact('addons','brands','modelLines','kitItemDropdown','newAddonCode'));
    }
    public function store(Request $request){
        if($request->kitSupplierAndPrice) {
            if(count($request->kitSupplierAndPrice) > 0 ) {
                foreach($request->kitSupplierAndPrice as $kitSupplierAndPriceData) {
                    $supPriInput = [];
                    $supPriInput['created_by'] = Auth::id();
                    $supPriInput['supplier_id'] = $kitSupplierAndPriceData['supplier_id'];
                    $supPriInput['addon_details_id'] = $request->kit_addon_id;
                    $supPriInput['purchase_price_aed'] = $kitSupplierAndPriceData['supplier_addon_purchase_price_in_aed'];
                    $supPriInput['purchase_price_usd'] = $kitSupplierAndPriceData['supplier_addon_purchase_price_in_usd'];
                    if($supPriInput['purchase_price_aed'] != '') {
                        $CreateSupAddPri = SupplierAddons::create($supPriInput);
                        $supPriInput['supplier_addon_id'] = $CreateSupAddPri->id;
                        $createHistrory = PurchasePriceHistory::create($supPriInput);
                        if(count($kitSupplierAndPriceData['item']) > 0) {
                            foreach($kitSupplierAndPriceData['item'] as $kitItemData) {
                                $createkit = [];
                                $createkit['created_by'] = Auth::id();
                                $createkit['supplier_addon_id'] = $CreateSupAddPri->id;
                                $createkit['addon_details_id'] = $kitItemData['kit_item_id'];
                                $createkit['quantity'] = $kitItemData['quantity'];
                                $createkit['unit_price_in_aed'] = $kitItemData['unit_price_in_aed'];
                                $createkit['total_price_in_aed'] = $kitItemData['total_price_in_aed'];
                                $createkit['unit_price_in_usd'] = $kitItemData['unit_price_in_usd'];
                                $createkit['total_price_in_usd'] = $kitItemData['total_price_in_usd'];
                                $CreateSupAddPri1 = KitItems::create($createkit);
                            }
                        }
                    }
                }
            }
        }
        (new UserActivityController)->createActivity('New Kit Created');
        $data = 'all';
        return redirect()->route('addon.list', $data)
                        ->with('success','Kit created successfully');
    }
    public function kitSuppliers($id) {
        $kitItemDropdown = KitCommonItem::where('addon_details_id',$id)->with('item.AddonName')->get();
        $suppliers = Supplier::select('id','supplier')->get();
        return view('kit.suppliers',compact('suppliers','kitItemDropdown','id'));
    }
    public function editKitSuppliers($id) {
        $kitItemDropdown = KitCommonItem::where('addon_details_id',$id)->with('item.AddonName')->get();
        $kitSupId = SupplierAddons::where('addon_details_id',$id)->pluck('supplier_id');
        $suppliers = Supplier::whereIn('id',$kitSupId)->select('id','supplier')->get();
        foreach($suppliers as $supplier) {
            $supplierAddon = '';
            $supplierAddon = SupplierAddons::where('addon_details_id',$id)->where('supplier_id',$supplier->id)->first();
            $supplier->purchase_price_aed = '';
            $supplier->purchase_price_aed = $supplierAddon->purchase_price_aed;
            $supplier->purchase_price_usd = '';
            $supplier->purchase_price_usd = $supplierAddon->purchase_price_usd;
            $supplier->item = KitItems::where('supplier_addon_id',$supplierAddon->id)->with('addon.AddonName')->get();
        }
        $otherSuppliers = Supplier::whereNotIn('id',$kitSupId)->select('id','supplier')->get();
        return view('kit.editsuppliers',compact('suppliers','kitItemDropdown','id','otherSuppliers'));
    }
    public function editAddonDetails(Request $request,$id) {
        $addonDetails = AddonDetails::where('id',$id)->with('AddonTypes','AddonName','AddonSuppliers','SellingPrice','PendingSellingPrice')->first();
        $price = '';
        $price = SupplierAddons::where('addon_details_id',$addonDetails->id)->where('status','active')->orderBy('purchase_price_aed','ASC')->first();
        $addonDetails->LeastPurchasePrices = $price;
        $addons = Addon::whereIn('addon_type',['K'])->select('id','name')->orderBy('name', 'ASC')->get();
        $existingBrandId = [];
        $existingBrandModel = [];
        $existingAddonTypes = AddonTypes::where('addon_details_id', $addonDetails->id)->groupBy('model_id')->get();
        $brands = Brand::select('id','brand_name')->get();
        $brandId = $addonDetails->latestAddonType->brand_id ?? '';
        $kitModelLineIds = AddonTypes::where('addon_details_id', $addonDetails->id)->pluck('model_id')->toArray();
        $modelLines = MasterModelLines::where('brand_id', $brandId)->whereNotIn('id', $kitModelLineIds)->select('id','model_line')->get();
        $typeSuppliers = SupplierType::select('supplier_id','supplier_type');
        if($addonDetails->addon_type_name == 'P') {
            $typeSuppliers = $typeSuppliers->where('supplier_type','accessories');
        }
        elseif($addonDetails->addon_type_name == 'SP') {
            $typeSuppliers = $typeSuppliers->where('supplier_type','spare_parts');
        }
        elseif($addonDetails->addon_type_name == 'K') {
            $typeSuppliers = $typeSuppliers->whereIn('supplier_type',['accessories','spare_parts']);
        }
        $typeSuppliers = $typeSuppliers->pluck('supplier_id');
        $existingSupplierId = SupplierAddons::where([
                                                ['addon_details_id', '=', $addonDetails->id],
                                                ['status', '=', 'active'],
                                            ])->pluck('supplier_id');
        $suppliers = Supplier::whereNotIn('id',$existingSupplierId)->whereIn('id',$typeSuppliers)->select('id','supplier')->get();
        $kitItemDropdown = Addon::whereIn('addon_type',['SP'])->pluck('id');
        $kitItemDropdown = AddonDetails::whereIn('addon_id', $kitItemDropdown)->with('AddonName')->get();
        $supplierAddons = SupplierAddons::where([
                                            ['addon_details_id', '=', $addonDetails->id],
                                            ['status', '=', 'active'],
            ])->groupBy(['purchase_price_aed','purchase_price_usd'])->select('id','purchase_price_aed','purchase_price_usd','addon_details_id','status')->get();
        foreach($supplierAddons as $supplierAddon) {
            $supplierId = [];
            $supplierId = SupplierAddons::where([
                                            ['purchase_price_aed', '=', $supplierAddon->purchase_price_aed],
                                            ['purchase_price_usd', '=', $supplierAddon->purchase_price_usd],
                                        ])->pluck('supplier_id');
            $supplierAddon->suppliers = Supplier::whereIn('id',$supplierId)->select('id','supplier')->get();
        }
        $kitItems = [];
        $kitItems = KitCommonItem::where('addon_details_id',$addonDetails->id)->with('item')->get();
        $count = KitCommonItem::where('addon_details_id',$addonDetails->id)->with('item')->count();
        $kitItemiD = [];
        $kitItemiD = KitCommonItem::where('addon_details_id',$addonDetails->id)->pluck('item_id')->toArray();
        $a = [];
        $a = Addon::whereIn('addon_type',['SP'])->pluck('id');
        $aa = [];
        $aa = AddonDetails::whereIn('addon_id',$a)->pluck('id');
        $itemDropdown = [];
        $itemDropdown = AddonDetails::whereIn('id',$aa)->whereNotIn('id',$kitItemiD)->with('AddonName')->get();
        $selectedAddonModelNumbers = AddonTypes::where('addon_details_id', $addonDetails->id)->pluck('model_number')->toArray();
        $availableKitItems = $this->availableKitItems($selectedAddonModelNumbers);
        $unselectedKitItems = array_diff($kitItemiD, $availableKitItems);
        $availableCommonItems = AddonDetails::whereIn('id', $unselectedKitItems)->get();
        (new UserActivityController)->createActivity( $addonDetails->addon_code.' Updated');
        return view('kit.edit',compact('addons','brands','modelLines','addonDetails','suppliers',
            'kitModelLineIds', 'kitItemDropdown','supplierAddons','existingBrandModel',
            'kitItems','itemDropdown','count','existingAddonTypes','availableCommonItems','kitItemiD'));
    }
    function availableKitItems($selectedAddonModelNumbers) {
        if($selectedAddonModelNumbers) {
            if(count($selectedAddonModelNumbers) > 0) {
                $kitItemDropdown = Addon::whereIn('addon_type', ['SP'])->pluck('id');
                $Items = [];
                foreach($selectedAddonModelNumbers as $key => $modelNumber) {
                    $commonItems[$key] = [];
                    $addonDetailIds = AddonTypes::where('model_number', $modelNumber)->pluck('addon_details_id')->toArray();
                    foreach ($addonDetailIds as $addonDetail) {
                        array_push($commonItems[$key], $addonDetail);
                    }
                    $Items[] = $commonItems[$key];
                }
                $result = call_user_func_array('array_intersect', $Items);
                $dataId = AddonDetails::whereIn('addon_id', $kitItemDropdown)->whereIn('id', $result)->pluck('description');
                $data = AddonDescription::with('Addon')->whereIn('id',$dataId)->pluck('id')->toArray();
                return $data;
            }
        }
    }
    public function updateKitSupplier(Request $request, $id) {
        $ExistingSuppliersId = [];
        $ExistingSuppliersId = SupplierAddons::where('addon_details_id',$request->kit_addon_id)->pluck('supplier_id');
        $ExistingSuppliersId = json_decode($ExistingSuppliersId);
        $currentSupplierAddonid = [];
        if(count($request->kitSupplierAndPrice) > 0) {
            foreach($request->kitSupplierAndPrice as $kitSupplierAndPrice) {
                if(in_array($kitSupplierAndPrice['supplier_id'],$ExistingSuppliersId)) {
                    $updateSupAdd = [];
                    $updateSupAdd = SupplierAddons::where('addon_details_id',$request->kit_addon_id)->where('supplier_id',$kitSupplierAndPrice['supplier_id'])->where('status','active')->first();
                    $oldPurchasePrice = $updateSupAdd->purchase_price_aed;
                    $updateSupAdd->purchase_price_aed = $kitSupplierAndPrice['supplier_addon_purchase_price_in_aed'];
                    $updateSupAdd->purchase_price_usd = $kitSupplierAndPrice['supplier_addon_purchase_price_in_usd'];
                    $updateSupAdd->updated_by = Auth::id();
                    $updateSupAdd->update();
                    array_push($currentSupplierAddonid,$updateSupAdd->id);
                    if($oldPurchasePrice != $kitSupplierAndPrice['supplier_addon_purchase_price_in_aed']){
                        $supPriInput = [];
                        $supPriInput['created_by'] = Auth::id();
                        $supPriInput['supplier_id'] = $kitSupplierAndPrice['supplier_id'];
                        $supPriInput['addon_details_id'] = $request->kit_addon_id;
                        $supPriInput['purchase_price_aed'] = $kitSupplierAndPrice['supplier_addon_purchase_price_in_aed'];
                        $supPriInput['purchase_price_usd'] = $kitSupplierAndPrice['supplier_addon_purchase_price_in_usd'];
                        $supPriInput['supplier_addon_id'] = $updateSupAdd->id;
                        $createHistrory = PurchasePriceHistory::create($supPriInput);
                    }
                    if(count($kitSupplierAndPrice['item']) > 0 ) {
                        foreach($kitSupplierAndPrice['item'] as $item) {
                            $item1 = '';
                            $item1 = KitItems::where('supplier_addon_id',$updateSupAdd->id)->where('addon_details_id',$item['kit_item_id'])->first();
                            $item1->quantity = $item['quantity'];
                            $item1->unit_price_in_aed = $item['unit_price_in_aed'];
                            $item1->total_price_in_aed = $item['total_price_in_aed'];
                            $item1->unit_price_in_usd = $item['unit_price_in_usd'];
                            $item1->total_price_in_usd = $item['total_price_in_usd'];
                            $item1->updated_by = Auth::id();
                            $item1->update();
                        }
                    }
                }
                else {
                    $supPriInput = [];
                    $supPriInput['created_by'] = Auth::id();
                    $supPriInput['supplier_id'] = $kitSupplierAndPrice['supplier_id'];
                    $supPriInput['addon_details_id'] = $request->kit_addon_id;
                    $supPriInput['purchase_price_aed'] = $kitSupplierAndPrice['supplier_addon_purchase_price_in_aed'];
                    $supPriInput['purchase_price_usd'] = $kitSupplierAndPrice['supplier_addon_purchase_price_in_usd'];
                    if($supPriInput['purchase_price_aed'] != '') {
                        $CreateSupAddPri = SupplierAddons::create($supPriInput);
                        array_push($currentSupplierAddonid,$CreateSupAddPri->id);
                        $supPriInput['supplier_addon_id'] = $CreateSupAddPri->id;
                        $createHistrory = PurchasePriceHistory::create($supPriInput);
                        if(count($kitSupplierAndPrice['item']) > 0) {
                            foreach($kitSupplierAndPrice['item'] as $kitItemData) {
                                $createkit = [];
                                $createkit['created_by'] = Auth::id();
                                $createkit['supplier_addon_id'] = $CreateSupAddPri->id;
                                $createkit['addon_details_id'] = $kitItemData['kit_item_id'];
                                $createkit['quantity'] = $kitItemData['quantity'];
                                $createkit['unit_price_in_aed'] = $kitItemData['unit_price_in_aed'];
                                $createkit['total_price_in_aed'] = $kitItemData['total_price_in_aed'];
                                $createkit['unit_price_in_usd'] = $kitItemData['unit_price_in_usd'];
                                $createkit['total_price_in_usd'] = $kitItemData['total_price_in_usd'];
                                $CreateSupAddPri1 = KitItems::create($createkit);
                            }
                        }
                    }
                }
            }
        }
        $deleteSupAdd = [];
        $deleteSupAdd = SupplierAddons::where('addon_details_id',$id)->whereNotIn('id',$currentSupplierAddonid)->get();
        foreach($deleteSupAdd as $del) {
            $deleteSupKit = KitItems::where('id',$del->id)->get();
            if(count($deleteSupKit) > 0) {
                foreach($deleteSupKit as $deleteSupKit1) {
                    $deleteSupKit1->delete();
                }
            }
            $deletehistory = PurchasePriceHistory::where('supplier_addon_id',$del->id)->get();
            if(count($deletehistory) > 0) {
                foreach($deletehistory as $deletehistory1) {
                    $deletehistory1->delete();
                }
            }
            $del = $del->delete();
        }
        $data = 'K';
            return redirect()->route('addon.list', $data)
                            ->with('success','Addon created successfully');
    }
    public function kitItems1($id) {
        $supplierAddonDetails = [];
        $supplierAddonDetails = AddonDetails::where('id',$id)->with('AddonName','AddonTypes.brands','SellingPrice','KitItems.item.AddonName',
        'KitItems.addon.AddonDescription','KitItems.partNumbers','AddonSuppliers.Suppliers','AddonSuppliers.Kit.addon.AddonName'
        )->first();
        $totalPrice = 0;
        foreach($supplierAddonDetails->KitItems as $oneItem) {
            $itemMinPrice= '';
            $itemMinPrice = SupplierAddons::where('addon_details_id',$oneItem->item_id)->where('status','active')->min('purchase_price_aed');
            $oneItem->leastPriceSupplier = SupplierAddons::where('addon_details_id',$oneItem->item_id)->where('status','active')->where('purchase_price_aed',$itemMinPrice)->with('Suppliers')->first();
            $oneItem->allItemSuppliers = SupplierAddons::where('addon_details_id',$oneItem->item_id)->where('status','active')->orderBy('purchase_price_aed','ASC')->with('Suppliers')->get();
            $oneItem->totalItemPrice = $itemMinPrice * $oneItem->quantity;
            $totalPrice = $totalPrice + $oneItem->totalItemPrice;
        }
        $supplierAddonDetails->totalPrice = $totalPrice;
                $price = '';
                $price = SupplierAddons::where('addon_details_id',$supplierAddonDetails->id)->where('status','active')->orderBy('purchase_price_aed','ASC')->first();
                $supplierAddonDetails->LeastPurchasePrices = $price;
        return view('kit.kititems',compact('supplierAddonDetails'));
    }
    public function kitItems($id) {
        $supplierAddonDetails = [];
        $supplierAddonDetails = AddonDetails::where('id',$id)->with('AddonName','AddonTypes.brands','SellingPrice','KitItems.addon.AddonDescription')->first();
        $totalPrice = 0;
        foreach($supplierAddonDetails->KitItems as $oneItem) {
            $totalPrice = $totalPrice + $oneItem->kit_item_total_purchase_price;
            $itemSps = [];
            $itemSps = AddonDetails::where('description',$oneItem->item_id)->pluck('id')->toArray();
            $itemModelDes = [];
            $itemModelDes = AddonTypes::where('addon_details_id',$id)->pluck('model_number');
            $modelDescSps = [];
            $modelDescSps = AddonTypes::whereIn('model_number',$itemModelDes)->pluck('addon_details_id')->toArray();
            $intersectArray = [];
            $intersectArray = array_intersect($modelDescSps,$itemSps);
            $oneItem->countArray = count($intersectArray);
            $SpWithoutVendorIds = [];
            $SpWithoutVendorIds = AddonDetails::whereIn('id',$intersectArray)->doesntHave('AddonSuppliers')->pluck('id');
            $SpWithoutVendorPartNos = [];
            $SpWithoutVendorPartNos = SparePartsNumber::whereIn('addon_details_id',$SpWithoutVendorIds)->latest()->with('addondetails')->get();
            if(count($SpWithoutVendorPartNos) > 0) {
                $oneItem->latestPartNoSp = $SpWithoutVendorPartNos[0]->addondetails;
            }
            $oneItem->SpWithoutVendorPartNos = $SpWithoutVendorPartNos;
        }
        $supplierAddonDetails->totalPrice = $totalPrice;
        $previousPurchsePriceHistory = KitPriceHistory::where('status', 'active')->where('addon_details_id', $id)->first();
        $previousPurchasePrice = '';
        if($previousPurchsePriceHistory) {
            if($previousPurchsePriceHistory->old_price != '') {
                $previousPurchasePrice = $previousPurchsePriceHistory->old_price;
            }
            else {
                $previousPurchasePrice = $previousPurchsePriceHistory->updated_price;
            }
        }
        $previousSellingPriceHistory = AddonSellingPrice::where([
            ['addon_details_id','=', $id],
            ['status','=','active']
        ])->latest()->first();
        $previousSellingPrice = '';
        if($previousSellingPriceHistory) {
           $previousSellingPrice = $previousSellingPriceHistory->selling_price;
        }
        $previous = $next = '';
        $previous = AddonDetails::where('addon_type_name','K')->where('id', '<', $id)->max('id');
        $next = AddonDetails::where('addon_type_name','K')->where('id', '>', $id)->min('id');
        return view('kit.kititems',compact('supplierAddonDetails','previousPurchasePrice',
        'previousSellingPrice','id','previous','next'));
    }
    public function getCommonKitItems(Request $request) {
        $data = [];
        if($request->selectedAddonModelNumbers) {
            if(count($request->selectedAddonModelNumbers) > 0) {
                $kitItemDropdown = Addon::whereIn('addon_type', ['SP'])->pluck('id');
                $Items = [];
                foreach($request->selectedAddonModelNumbers as $key => $modelNumber) {
                    $commonItems[$key] = [];
                    $addonDetailIds = AddonTypes::where('model_number', $modelNumber)->pluck('addon_details_id')->toArray();
                        foreach ($addonDetailIds as $addonDetail) {
                            array_push($commonItems[$key], $addonDetail);
                        }
                    $Items[] = $commonItems[$key];
                }
                $result = call_user_func_array('array_intersect', $Items);
                $dataId = AddonDetails::whereIn('addon_id', $kitItemDropdown)->whereIn('id', $result)->pluck('description');
                $data = AddonDescription::with('Addon')->whereIn('id',$dataId);
                if($request->type == 'ADD_ITEM') {
                    if($request->selectedItems) {
                        $data = $data->whereNotIn('id', $request->selectedItems);
                    }
                }
                $data = $data->get();
            }
        }
        return response($data);
    }
    public function priceStore(Request $request) {
        $success = false;
        if(($request->current_purchase_price != '' || $request->current_purchase_price != 'NOT AVAILABLE' ) 
        && $request->previous_purchase_price != $request->current_purchase_price) {
            $existingPurchasePrice = KitPriceHistory::where([
                ['status','=','active'],
                ['addon_details_id','=',$request->addon_details_id]
            ])->first();
            if($existingPurchasePrice != '') {
                $existingPurchasePrice->status = 'inactive';
                $existingPurchasePrice->updated_by =Auth::id();
                $existingPurchasePrice->save();
            }
            $purchasePrice['addon_details_id'] = $request->addon_details_id;
            $purchasePrice['old_price'] = $request->previous_purchase_price;
            $purchasePrice['updated_price'] = $request->current_purchase_price;
            $purchasePrice['status'] = 'active';
            $purchasePrice['created_by'] = Auth::id();
            $createPurchasePrice = KitPriceHistory::create($purchasePrice);
            $success = true;
        }
        if($request->current_selling_price != '' && $request->previous_selling_price != $request->current_selling_price) {
            $sellingPrice = AddonSellingPrice::where([
                ['addon_details_id','=',$request->addon_details_id],
                ['selling_price','=',$request->current_selling_price],
                ['status','=','pending']
            ])->latest()->first();
            if(!$sellingPrice) {
                $createSellingPrice['addon_details_id'] = $request->addon_details_id;
                $createSellingPrice['selling_price'] = $request->current_selling_price;
                $createSellingPrice['status'] = 'pending';
                $createSellingPrice['created_by'] = Auth::id();
                $createSellPrice = AddonSellingPrice::create($createSellingPrice);
            }
            $success = true;
        }
        if($success = true) {
            return redirect()->back()->with('success','Price added successfully');
        }
        else{
            return redirect()->back();
        }
    }
    public function getPartNumbers(Request $request) {
        $currentSupplierAddon = SupplierAddons::find($request->id);
        $data['item_image'] = url('addon_image/' . $currentSupplierAddon->supplierAddonDetails->image) ;
        $data['item_code'] = $currentSupplierAddon->supplierAddonDetails->addon_code;
        $data['item_id'] = $currentSupplierAddon->supplierAddonDetails->id;
        $addonDetailId = $currentSupplierAddon->addon_details_id;
        $data['part_number'] = SparePartsNumber::where('addon_details_id', $addonDetailId)->get();
        return response($data);
    }
    public function storeKitItems(Request $request) {
        $requestModelDescriptions = [];
        if(count($request->ModelLineDescriptionArr) > 0) {
            foreach($request->ModelLineDescriptionArr[0] as $brandModel) {
                array_push($requestModelDescriptions, $brandModel);
            }
        }
        $requestItemIds = [];
        if(count($request->mainItem) > 0) {
            $mainItem = $request->mainItem;
            $count = count($request->mainItem);
            for($i=0; $i<$count; $i++) {
                array_push($requestItemIds, $mainItem[$i][0][0]);
            }
        }
        $isExist = AddonDetails::where([['addon_type_name','=','K'],
                                        ['addon_id','=',$request->addon_id],
                                        ['is_all_brands','=','no']])->whereHas('AddonTypes', function($query) use($request) {
                $query->where('brand_id','=',$request->brand_id);
        });
        if($request->currentKitId) {
            $isExist = $isExist->where('id','!=',$request->currentKitId);
        }
        $isExist = $isExist->select('id')->get(); 
        $isSameKitExist = 'no';
        $existingSameKitId = '';
        foreach($isExist as $oneKit) {
            $kititems = KitCommonItem::where('addon_details_id',$oneKit->id)->select('addon_details_id','item_id')->get();
            $kititemsArr = [];
            foreach($kititems as $kititem) {
                array_push($kititemsArr, $kititem->item_id);
            }
            $result1 = array_diff( $kititemsArr,$requestItemIds);
            $result2 = array_diff( $requestItemIds,$kititemsArr);
            if(count($result1) == 0 && count($result2) == 0) {
                $isSameItemQuantityExist = 'yes';
                for($i=0; $i<$count; $i++) {
                    $quantity = '';
                    $quantity = KitCommonItem::where([['addon_details_id','=',$oneKit->id],
                                                    ['item_id','=',$mainItem[$i][0][0]],
                                                    ['quantity','=',$mainItem[$i][1]],])->first();
                    if($quantity == '') {
                        $isSameItemQuantityExist = 'no';
                    }
                    else {                       
                        $existingSameKitId = $quantity->addon_details_id;
                    }
                } 
                if($isSameItemQuantityExist ==  'yes') {
                    $isSameKitExist = 'yes';
                }            
            }
        }
        if($isSameKitExist == 'yes') {
            if($existingSameKitId != '') {
                $existingModelDescriptions = AddonTypes::where('addon_details_id',$existingSameKitId)->pluck('model_number');
                $existingModelDescriptionsArr = [];
                foreach($existingModelDescriptions as $existingModelDescription) {
                    array_push($existingModelDescriptionsArr, $existingModelDescription);
                }
                $result = array_intersect($existingModelDescriptionsArr, $requestModelDescriptions);
                $alreadyExisting = MasterModelDescription::whereIn('id',$requestModelDescriptions)->select('model_description')->get();
            }
            return response()->json(['error' => 'Same kit existing']);
        }
        else{
            return response()->json(['success' => 'This is a new kit']);
        }
    }
}
