<?php

namespace App\Http\Controllers;
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

class KitCommonItemController extends Controller
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
        $kitItemDropdown = Addon::whereIn('addon_type',['SP'])->pluck('id');
        $kitItemDropdown = AddonDetails::whereIn('addon_id', $kitItemDropdown)->with('AddonName')->get();
        $addons = Addon::whereIn('addon_type',['K'])->select('id','name')->orderBy('name', 'ASC')->get();
        $brands = Brand::select('id','brand_name')->get();
        $modelLines = MasterModelLines::select('id','brand_id','model_line')->get();
        $masterAddonByType = Addon::where('addon_type','K')->pluck('id');
        if($masterAddonByType != '')
        {
            $lastAddonCode = AddonDetails::whereIn('addon_id',$masterAddonByType)->withTrashed()->orderBy('id', 'desc')->first();
            if($lastAddonCode != '')
            {
                $lastAddonCodeNo =  $lastAddonCode->addon_code;
                $lastAddonCodeNumber = substr($lastAddonCodeNo, 1, 5);
                $newAddonCodeNumber =  $lastAddonCodeNumber+1;
                $newAddonCode = "K".$newAddonCodeNumber;
            }
            else
            {
                $newAddonCode = "K"."1";
            }
        }
        else
        {
            $newAddonCode = "K"."1";
        }
        return view('kit.create',compact('addons','brands','modelLines','kitItemDropdown','newAddonCode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        if($request->kitSupplierAndPrice)
        {
            if(count($request->kitSupplierAndPrice) > 0 )
            {
                foreach($request->kitSupplierAndPrice as $kitSupplierAndPriceData)
                {
                    $supPriInput = [];
                    $supPriInput['created_by'] = Auth::id();
                    $supPriInput['supplier_id'] = $kitSupplierAndPriceData['supplier_id'];
                    $supPriInput['addon_details_id'] = $request->kit_addon_id;
                    $supPriInput['purchase_price_aed'] = $kitSupplierAndPriceData['supplier_addon_purchase_price_in_aed'];
                    $supPriInput['purchase_price_usd'] = $kitSupplierAndPriceData['supplier_addon_purchase_price_in_usd'];
                    if($supPriInput['purchase_price_aed'] != '')
                    {
                        $CreateSupAddPri = SupplierAddons::create($supPriInput);
                        $supPriInput['supplier_addon_id'] = $CreateSupAddPri->id;
                        $createHistrory = PurchasePriceHistory::create($supPriInput);
                        if(count($kitSupplierAndPriceData['item']) > 0)
                        {
                            foreach($kitSupplierAndPriceData['item'] as $kitItemData)
                            {
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
        $data = 'all';
        return redirect()->route('addon.list', $data)
                        ->with('success','Addon created successfully');
    }
    /**
     * Display the specified resource.
     */
    public function show(KitCommonItem $kitCommonItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KitCommonItem $kitCommonItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KitCommonItem $kitCommonItem)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KitCommonItem $kitCommonItem)
    {
        //
    }

    public function kitSuppliers($id)
    {
        $kitItemDropdown = KitCommonItem::where('addon_details_id',$id)->with('item.AddonName')->get();
        $suppliers = Supplier::select('id','supplier')->get();
        return view('kit.suppliers',compact('suppliers','kitItemDropdown','id'));
    }
    public function editKitSuppliers($id)
    {
        $kitItemDropdown = KitCommonItem::where('addon_details_id',$id)->with('item.AddonName')->get();
        $kitSupId = SupplierAddons::where('addon_details_id',$id)->pluck('supplier_id');
        $suppliers = Supplier::whereIn('id',$kitSupId)->select('id','supplier')->get();
        foreach($suppliers as $supplier)
        {
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

    public function editAddonDetails(Request $request,$id)
    {
        // AddonSuppliersUsed
        // one addon - multiple suppliers - suppliers cannot repeat
        $addonDetails = AddonDetails::where('id',$id)->with('AddonTypes','AddonName','AddonSuppliers','SellingPrice','PendingSellingPrice')->first();
        $price = '';
        $price = SupplierAddons::where('addon_details_id',$addonDetails->id)->where('status','active')->orderBy('purchase_price_aed','ASC')->first();
        $addonDetails->LeastPurchasePrices = $price;
        $addons = Addon::whereIn('addon_type',['K'])->select('id','name')->orderBy('name', 'ASC')->get();
        $existingBrandId = [];
        $existingBrandModel = [];
        $existingAddonTypes = AddonTypes::where('addon_details_id', $addonDetails->id)->groupBy('model_id')->get();
//        if($addonDetails->is_all_brands == 'no')
//        {
//            $existingBrandModel = AddonTypes::where('addon_details_id',$id)->groupBy('brand_id')->with('brands')->get();
//            foreach($existingBrandModel as $data)
//            {
//                array_push($existingBrandId,$data->brand_id);
//                $jsonmodelLine = [];
//                $data->ModelLine = AddonTypes::where([
//                    ['addon_details_id','=',$id],
//                    ['brand_id','=',$data->brand_id]
//                    ])->groupBy('model_id')->with('modelLines')->get();
//                    $data->ModelLine->modeldes = [];
//                if($data->is_all_model_lines == 'no')
//                {
//                    foreach($data->ModelLine as $mo)
//                    {
//                        $mo->allDes = MasterModelDescription::where('model_line_id',$mo->model_id)->get();
//                        $mo->modeldes = AddonTypes::where([
//                            ['addon_details_id','=',$id],
//                            ['brand_id','=',$mo->brand_id],
//                            ['model_id','=',$mo->model_id],
//                            ])->pluck('model_number');
//                            $mo->modeldes = json_decode($mo->modeldes);
//                    }
//                }
//                $modelLinesData = AddonTypes::where([
//                                                    ['addon_details_id','=',$id],
//                                                    ['brand_id','=',$data->brand_id]
//                                                    ])->pluck('model_id');
//                $jsonmodelLine = json_decode($modelLinesData);
//                $data->modelLinesData = $jsonmodelLine;
//                $data->ModalLines = MasterModelLines::where('brand_id',$data->brand_id)->get();
//            }
//        }
        // dd($existingBrandModel);
        $brands = Brand::select('id','brand_name')->get();
        $brandId = $addonDetails->latestAddonType->brand_id ?? '';
        $kitModelLineIds = AddonTypes::where('addon_details_id', $addonDetails->id)->pluck('model_id')->toArray();

        $modelLines = MasterModelLines::where('brand_id', $brandId)
            ->whereNotIn('id', $kitModelLineIds)
            ->select('id','model_line')
            ->get();
//        return $modelLines;
        $typeSuppliers = SupplierType::select('supplier_id','supplier_type');
        if($addonDetails->addon_type_name == 'P')
        {
            $typeSuppliers = $typeSuppliers->where('supplier_type','accessories');
        }
        elseif($addonDetails->addon_type_name == 'SP')
        {
            $typeSuppliers = $typeSuppliers->where('supplier_type','spare_parts');
        }
        elseif($addonDetails->addon_type_name == 'K')
        {
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
                                        ])->groupBy(['purchase_price_aed','purchase_price_usd'])
                                        ->select('id','purchase_price_aed','purchase_price_usd','addon_details_id','status')
                                        ->get();
        foreach($supplierAddons as $supplierAddon)
        {
            $supplierId = [];
            $supplierId = SupplierAddons::where([
                                            ['purchase_price_aed', '=', $supplierAddon->purchase_price_aed],
                                            ['purchase_price_usd', '=', $supplierAddon->purchase_price_usd],
                                        ])->pluck('supplier_id');
            $supplierAddon->suppliers = Supplier::whereIn('id',$supplierId)->select('id','supplier')->get();
        }

        // Kit common items
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
        // get available common items for this kit
        $itemDropdown = AddonDetails::whereIn('id',$aa)->whereNotIn('id',$kitItemiD)->with('AddonName')->get();
        $selectedAddonModelNumbers = AddonTypes::where('addon_details_id', $addonDetails->id)->pluck('model_number')->toArray();
        $alreadyAddedItems = $this->availableKitItems($selectedAddonModelNumbers);
        $unselectedKitItems = array_diff($alreadyAddedItems, $kitItemiD);
        $availableCommonItems = AddonDetails::whereIn('id', $unselectedKitItems)->get();

//        return $availableCommonItems;

        // kit common items which is used for this kit

        return view('kit.edit',compact('addons','brands','modelLines','addonDetails','suppliers','kitModelLineIds','alreadyAddedItems',
            'kitItemDropdown','supplierAddons','existingBrandModel','kitItems','itemDropdown','count','existingAddonTypes','availableCommonItems'));
    }

    function availableKitItems($data)
    {

        $kitItemDropdown = Addon::whereIn('addon_type', ['SP'])->pluck('id');
        // get each model description rows
        $Items = [];
        foreach($data as $key => $modelNumber) {
            $commonItems[$key] = [];
            $addonDetailIds = AddonTypes::where('model_number', $modelNumber)->pluck('addon_details_id')->toArray();
            foreach ($addonDetailIds as $addonDetail) {
                array_push($commonItems[$key], $addonDetail);
            }
            $Items[] = $commonItems[$key];
        }

        $result = call_user_func_array('array_intersect', $Items);
        $availableKitItems = AddonDetails::with('AddonName')
                            ->whereIn('addon_id', $kitItemDropdown)
                            ->whereIn('id', $result);

        $availableKitItems = $availableKitItems->pluck('id')->toArray();
        return $availableKitItems;
    }
    public function updateKitSupplier(Request $request, $id)
    {
        $ExistingSuppliersId = [];
        $ExistingSuppliersId = SupplierAddons::where('addon_details_id',$request->kit_addon_id)->pluck('supplier_id');
        $ExistingSuppliersId = json_decode($ExistingSuppliersId);

        $currentSupplierAddonid = [];

        if(count($request->kitSupplierAndPrice) > 0)
        {
            foreach($request->kitSupplierAndPrice as $kitSupplierAndPrice)
            {
                if(in_array($kitSupplierAndPrice['supplier_id'],$ExistingSuppliersId))
                {
                    $updateSupAdd = [];
                    $updateSupAdd = SupplierAddons::where('addon_details_id',$request->kit_addon_id)
                                                    ->where('supplier_id',$kitSupplierAndPrice['supplier_id'])->where('status','active')->first();
                    $oldPurchasePrice = $updateSupAdd->purchase_price_aed;
                    $updateSupAdd->purchase_price_aed = $kitSupplierAndPrice['supplier_addon_purchase_price_in_aed'];
                    $updateSupAdd->purchase_price_usd = $kitSupplierAndPrice['supplier_addon_purchase_price_in_usd'];
                    $updateSupAdd->updated_by = Auth::id();
                    $updateSupAdd->update();
                    array_push($currentSupplierAddonid,$updateSupAdd->id);
                    if($oldPurchasePrice != $kitSupplierAndPrice['supplier_addon_purchase_price_in_aed'])
                    {
                        $supPriInput = [];
                        $supPriInput['created_by'] = Auth::id();
                        $supPriInput['supplier_id'] = $kitSupplierAndPrice['supplier_id'];
                        $supPriInput['addon_details_id'] = $request->kit_addon_id;
                        $supPriInput['purchase_price_aed'] = $kitSupplierAndPrice['supplier_addon_purchase_price_in_aed'];
                        $supPriInput['purchase_price_usd'] = $kitSupplierAndPrice['supplier_addon_purchase_price_in_usd'];
                        $supPriInput['supplier_addon_id'] = $updateSupAdd->id;
                        $createHistrory = PurchasePriceHistory::create($supPriInput);
                    }

                    if(count($kitSupplierAndPrice['item']) > 0 )
                    {
                        foreach($kitSupplierAndPrice['item'] as $item)
                        {
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
                else
                {
                    $supPriInput = [];
                    $supPriInput['created_by'] = Auth::id();
                    $supPriInput['supplier_id'] = $kitSupplierAndPrice['supplier_id'];
                    $supPriInput['addon_details_id'] = $request->kit_addon_id;
                    $supPriInput['purchase_price_aed'] = $kitSupplierAndPrice['supplier_addon_purchase_price_in_aed'];
                    $supPriInput['purchase_price_usd'] = $kitSupplierAndPrice['supplier_addon_purchase_price_in_usd'];
                    if($supPriInput['purchase_price_aed'] != '')
                    {
                        $CreateSupAddPri = SupplierAddons::create($supPriInput);
                        array_push($currentSupplierAddonid,$CreateSupAddPri->id);
                        $supPriInput['supplier_addon_id'] = $CreateSupAddPri->id;
                        $createHistrory = PurchasePriceHistory::create($supPriInput);
                        if(count($kitSupplierAndPrice['item']) > 0)
                        {
                            foreach($kitSupplierAndPrice['item'] as $kitItemData)
                            {
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
        // delete
        $deleteSupAdd = [];
        $deleteSupAdd = SupplierAddons::where('addon_details_id',$id)->whereNotIn('id',$currentSupplierAddonid)->get();
        foreach($deleteSupAdd as $del)
        {
            $deleteSupKit = KitItems::where('id',$del->id)->get();
            if(count($deleteSupKit) > 0)
            {
                foreach($deleteSupKit as $deleteSupKit1)
                {
                    $deleteSupKit1->delete();
                }
            }
            $deletehistory = PurchasePriceHistory::where('supplier_addon_id',$del->id)->get();
            if(count($deletehistory) > 0)
            {
                foreach($deletehistory as $deletehistory1)
                {
                    $deletehistory1->delete();
                }
            }
            $del = $del->delete();
        }
        $data = 'K';
            return redirect()->route('addon.list', $data)
                            ->with('success','Addon created successfully');
    }
    public function kitItems1($id)
    {
        $supplierAddonDetails = [];
        $supplierAddonDetails = AddonDetails::where('id',$id)->with('AddonName','AddonTypes.brands','SellingPrice','KitItems.item.AddonName',
        'KitItems.addon.AddonDescription',
        'KitItems.partNumbers',

        // 'KitItems.item.AddonSuppliers.Suppliers',
        // old code start
        'AddonSuppliers.Suppliers','AddonSuppliers.Kit.addon.AddonName'
        )
        // old code end
        ->first();
        $totalPrice = 0;
        foreach($supplierAddonDetails->KitItems as $oneItem)
        {
            $itemMinPrice= '';
            $itemMinPrice = SupplierAddons::where('addon_details_id',$oneItem->item_id)->where('status','active')->min('purchase_price_aed');
            $oneItem->leastPriceSupplier = SupplierAddons::where('addon_details_id',$oneItem->item_id)->where('status','active')
                                            ->where('purchase_price_aed',$itemMinPrice)->with('Suppliers')->first();
            $oneItem->allItemSuppliers = SupplierAddons::where('addon_details_id',$oneItem->item_id)->where('status','active')
                                    ->orderBy('purchase_price_aed','ASC')->with('Suppliers')->get();
            $oneItem->totalItemPrice = $itemMinPrice * $oneItem->quantity;
            $totalPrice = $totalPrice + $oneItem->totalItemPrice;
        }
        $supplierAddonDetails->totalPrice = $totalPrice;
                // old code start
                $price = '';
                $price = SupplierAddons::where('addon_details_id',$supplierAddonDetails->id)->where('status','active')->orderBy('purchase_price_aed','ASC')->first();
                $supplierAddonDetails->LeastPurchasePrices = $price;
                //old code end

        // $supplierAddonDetails = AddonDetails::where('id',$id)->with('AddonName','AddonTypes.brands','SellingPrice','AddonSuppliers.Suppliers',
        // 'AddonSuppliers.Kit.addon.AddonName')->with('LeastPurchasePrices', function($q)
        // {
        //     return $q->where('status','active')->min('purchase_price_aed');
        //     // $q->where('status','active')->ofMany('purchase_price_aed', 'min')->first();
        // })->first();
        // ->with('AddonSuppliers','AddonSuppliers.Suppliers','AddonSuppliers.Kit.addon.AddonName')
        // $supplierAddonDetails = SupplierAddons::where('addon_details_id',$id)->with('Suppliers','Kit.addon.AddonName','supplierAddonDetails.SellingPrice')->get();
        // dd($supplierAddonDetails);
        return view('kit.kititems',compact('supplierAddonDetails'));
    }
    public function kitItems($id)
    {
        $supplierAddonDetails = [];
        $supplierAddonDetails = AddonDetails::where('id',$id)->with('AddonName','AddonTypes.brands','SellingPrice','KitItems.addon.AddonDescription',
        // 'KitItems.item.AddonName','KitItems.partNumbers','KitItems.item.AddonSuppliers.Suppliers',
        // old code start
        // 'AddonSuppliers.Suppliers','AddonSuppliers.Kit.addon.AddonName'
        )
        // old code end
        ->first();
        // find model description Spare parts
        $modelDescriptionsId = [];

        $modelDescriptionsId = Addontypes::where('addon_details_id',$id)->pluck('model_number');
        $othrModelDesArr = Addontypes::whereIn('model_number',$modelDescriptionsId)->pluck('addon_details_id');
//        foreach($supplierAddonDetails->KitItems as $oneItem)
//        {
//            $itemAddonDes = '';
//            $itemAddonDes = AddonDescription::where('id',$oneItem->item_id)->select('addon_id','description')->first();
//            if($itemAddonDes != '')
//            {
//                $itemSpIds = [];
//                $itemSpIds = AddonDetails::whereIn('addon_id',$othrModelDesArr)->where('addon_id',$itemAddonDes->addon_id)
//                    ->where('description',$itemAddonDes->description)->pluck('id');
//                // $supplierAddons = SupplierAddons::where('addon_details_id',$itemSpIds->id)->pluck('id');
//                // $leastPrice = PurchasePriceHistory::where('supplier_addon_id',$supplierAddons)
//            }
//            // dd($oneItem->item_id);
//        }

    // dd($modelDescriptions);
        $totalPrice = 0;
        foreach($supplierAddonDetails->KitItems as $oneItem)
        {
            $itemMinPrice= '';
//            $itemMinPrice = SupplierAddons::where('addon_details_id',$oneItem->item_id)->where('status','active')->min('purchase_price_aed');
//            $oneItem->leastPriceSupplier = SupplierAddons::where('addon_details_id',$oneItem->item_id)->where('status','active')
//                                            ->where('purchase_price_aed',$itemMinPrice)->with('Suppliers')->first();
//            $oneItem->allItemSuppliers = SupplierAddons::where('addon_details_id',$oneItem->item_id)->where('status','active')
//                                    ->orderBy('purchase_price_aed','ASC')->with('Suppliers')->get();
//            $oneItem->totalItemPrice =  $itemMinPrice * $oneItem->quantity;
            $totalPrice = $totalPrice + $oneItem->kit_item_total_purchase_price;
        }
        $supplierAddonDetails->totalPrice = $totalPrice;
                // old code start
                $price = '';
//                $price = SupplierAddons::where('addon_details_id',$supplierAddonDetails->id)->where('status','active')->orderBy('purchase_price_aed','ASC')->first();
//                $supplierAddonDetails->LeastPurchasePrices = $price;
                //old code end

        // $supplierAddonDetails = AddonDetails::where('id',$id)->with('AddonName','AddonTypes.brands','SellingPrice','AddonSuppliers.Suppliers',
        // 'AddonSuppliers.Kit.addon.AddonName')->with('LeastPurchasePrices', function($q)
        // {
        //     return $q->where('status','active')->min('purchase_price_aed');
        //     // $q->where('status','active')->ofMany('purchase_price_aed', 'min')->first();
        // })->first();
        // ->with('AddonSuppliers','AddonSuppliers.Suppliers','AddonSuppliers.Kit.addon.AddonName')
        // $supplierAddonDetails = SupplierAddons::where('addon_details_id',$id)->with('Suppliers','Kit.addon.AddonName','supplierAddonDetails.SellingPrice')->get();
        // dd($supplierAddonDetails->KitItems);


        // foreach($supplierAddonDetails->KitItems as $kitItem)
        // {
        //     // dd($kitItem->item_id);
        //     $itemDes = '';
        //     $itemDes = AddonDescription::where('id',$kitItem->item_id)->first();
        //     if($itemDes != '')
        //     {
        //         $sparePartsID = AddonDetails::where('addon_id',$itemDes->addon_id)
        //     }
        // }
        return view('kit.kititems',compact('supplierAddonDetails'));
    }
    public function getCommonKitItems(Request $request) {

        $data = [];
        if($request->selectedAddonModelNumbers) {
            if(count($request->selectedAddonModelNumbers) > 0)
            {
                $kitItemDropdown = Addon::whereIn('addon_type', ['SP'])->pluck('id');
                // get each model description rows
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
                $dataId = AddonDetails::whereIn('addon_id', $kitItemDropdown)
                    ->whereIn('id', $result)->pluck('description');

                $data = AddonDescription::with('Addon')->whereIn('id',$dataId);
                if($request->type == 'ADD_ITEM') {
                    if($request->selectedItems) {
                        $data = $data->whereNotIn('id', $request->selectedItems);
                    }
                }
                $data = $data->get();
            }
        }
        info($data);
        return response($data);
    }
    public function priceStore(Request $request)
    {
        if($request->current_purchase_price != '' && $request->previous_purchase_price != $request->current_purchase_price)
        {
            $existingPurchasePrice = KitPriceHistory::where([
                ['status','=','active'],
                ['addon_details_id','=',$request->addon_details_id]
            ])->first();
            if($existingPurchasePrice != '')
            {
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
        }
        if($request->current_selling_price != '' && $request->previous_selling_price != $request->current_selling_price)
        {
//            $sellingPrice = AddonSellingPrice::where([
//                ['addon_details_id','=',]
//            ])
        }
    }

    public function getPartNumbers(Request $request) {

        $currentSupplierAddon = SupplierAddons::find($request->id);
        $data['item_code'] = $currentSupplierAddon->supplierAddonDetails->addon_code;

        return response($data);
    }
}
