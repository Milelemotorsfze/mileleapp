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
        $kitItemDropdown = Addon::whereIn('addon_type',['P','SP'])->pluck('id');
        $kitItemDropdown = AddonDetails::whereIn('addon_id', $kitItemDropdown)->with('AddonName')->get();
        $addons = Addon::whereIn('addon_type',['K'])->select('id','name')->orderBy('name', 'ASC')->get();
        $brands = Brand::select('id','brand_name')->get();
        $modelLines = MasterModelLines::select('id','brand_id','model_line')->get();
        $masterAddonByType = Addon::where('addon_type','K')->pluck('id');
        if($masterAddonByType != '')
        {
            $lastAddonCode = AddonDetails::whereIn('addon_id',$masterAddonByType)->orderBy('id', 'desc')->first();
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
                                $createkit['item_id'] = $CreateSupAddPri->id;
                                $createkit['addon_details_id'] = $kitItemData['kit_item_id'];
                                $createkit['quantity'] = $kitItemData['quantity'];
                                $createkit['unit_price_in_aed'] = $kitItemData['unit_price_in_aed'];
                                $createkit['total_price_in_aed'] = $kitItemData['total_price_in_aed'];
                                $createkit['unit_price_in_usd'] = $kitItemData['unit_price_in_usd'];
                                $createkit['total_price_in_usd'] = $kitItemData['total_price_in_usd'];
                                $CreateSupAddPri = KitItems::create($createkit);
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
        //
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
        // $kitItemDropdown = AddonDetails::whereIn('id', $kitItemDropdown)->with('AddonName')->get();
        $suppliers = Supplier::select('id','supplier')->get();
        return view('kit.suppliers',compact('suppliers','kitItemDropdown','id'));
    }
}
