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

    public function editAddonDetails($id)
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
        if($addonDetails->is_all_brands == 'no')
        {
            $existingBrandModel = AddonTypes::where('addon_details_id',$id)->groupBy('brand_id')->with('brands')->get();
            foreach($existingBrandModel as $data)
            {
                array_push($existingBrandId,$data->brand_id);
                $jsonmodelLine = [];
                $data->ModelLine = AddonTypes::where([
                    ['addon_details_id','=',$id],
                    ['brand_id','=',$data->brand_id]
                    ])->groupBy('model_id')->with('modelLines')->get();
                    $data->ModelLine->modeldes = [];
                if($data->is_all_model_lines == 'no')
                {
                    foreach($data->ModelLine as $mo)
                    {
                        $mo->allDes = MasterModelDescription::where('model_line_id',$mo->model_id)->get();
                        $mo->modeldes = AddonTypes::where([
                            ['addon_details_id','=',$id],
                            ['brand_id','=',$mo->brand_id],
                            ['model_id','=',$mo->model_id],
                            ])->pluck('model_number');
                            $mo->modeldes = json_decode($mo->modeldes);
                    }
                }
                $modelLinesData = AddonTypes::where([
                                                    ['addon_details_id','=',$id],
                                                    ['brand_id','=',$data->brand_id]
                                                    ])->pluck('model_id');
                $jsonmodelLine = json_decode($modelLinesData);
                $data->modelLinesData = $jsonmodelLine;
                $data->ModalLines = MasterModelLines::where('brand_id',$data->brand_id)->get();
            }
        }
        // dd($existingBrandModel);
        $brands = Brand::whereNotIn('id',$existingBrandId)->select('id','brand_name')->get();
        $modelLines = MasterModelLines::select('id','brand_id','model_line')->get();
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
        $kitItemiD = KitCommonItem::where('addon_details_id',$addonDetails->id)->pluck('item_id');
        $a = [];
        $a = Addon::whereIn('addon_type',['SP'])->pluck('id');
        $aa = [];
        $aa = AddonDetails::whereIn('addon_id',$a)->pluck('id');
        $itemDropdown = [];
        $itemDropdown = AddonDetails::whereIn('id',$aa)->whereNotIn('id',$kitItemiD)->with('AddonName')->get();
        return view('kit.edit',compact('addons','brands','modelLines','addonDetails','suppliers','kitItemDropdown','supplierAddons','existingBrandModel','kitItems','itemDropdown','count'));
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
    public function kitItems($id)
    {
        $supplierAddonDetails = [];
        // AddonSuppliersUsed
        // one addon- multiple suppliers - suppliers cannot be repeated - supplier with kit needed
        $supplierAddonDetails = AddonDetails::where('id',$id)->with('AddonName','AddonTypes.brands','SellingPrice','AddonSuppliers.Suppliers',
        'AddonSuppliers.Kit.addon.AddonName')->first();

                $price = '';
                $price = SupplierAddons::where('addon_details_id',$supplierAddonDetails->id)->where('status','active')->orderBy('purchase_price_aed','ASC')->first();
                $supplierAddonDetails->LeastPurchasePrices = $price;

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
    public function getCommonKitItems(Request $request) {

        $data = [];
        if($request->selectedAddonModelNumbers) {
            if(count($request->selectedAddonModelNumbers) > 0)
            {
                $kitItemDropdown = Addon::whereIn('addon_type',['SP'])->pluck('id');
                $availableModelNumbers = AddonTypes::pluck('model_number')->toArray();
                $commonItems = array_intersect($request->selectedAddonModelNumbers, $availableModelNumbers);

                if(count($commonItems) == $request->count) {
                    $addonDetailIds = AddonTypes::whereIn('model_number', $request->selectedAddonModelNumbers)
                        ->groupBy('addon_details_id')->pluck('addon_details_id');
                    $data = AddonDetails::with('AddonName')->whereIn('addon_id', $kitItemDropdown)
                        ->whereIn('id', $addonDetailIds)
                        ->get();
                }
            }
        }

        return response($data);
    }
}
