<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Addon;
use App\Models\Brand;
use App\Models\MasterModelLines;
use App\Models\AddonDetails;
use App\Models\AddonTypes;
use App\Models\Supplier;
use App\Models\SupplierAddons;
use App\Models\MasterModelDescription;
use App\Models\KitItems;
use App\Models\AddonSellingPrice;
use App\Models\PurchasePriceHistory;
use App\Models\SupplierType;
use DB;
use Validator;
use Intervention\Image\Facades\Image;


class AddonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($data)
    {
        $content = 'addon';
        $addonMasters = Addon::select('id','name')->orderBy('name', 'ASC')->get();
        $brandMatsers = Brand::select('id','brand_name')->orderBy('brand_name', 'ASC')->get();
        $modelLineMasters = MasterModelLines::select('id','brand_id','model_line')->orderBy('model_line', 'ASC')->get();

        $addon1 = AddonDetails::with('AddonName','AddonTypes.brands','AddonTypes.modelLines','AddonTypes.modelDescription','SellingPrice',
        'PendingSellingPrice');

        if($data != 'all')
        {
            $addon1 = $addon1->where('addon_type_name',$data);
        }
        $addon1 = $addon1->orderBy('id', 'DESC')->get();
        foreach($addon1 as $addon)
        {
            $price = '';
            $price = SupplierAddons::where('addon_details_id',$addon->id)->where('status','active')->orderBy('purchase_price_aed','ASC')->first();
            $addon->LeastPurchasePrices = $price;
        }
        return view('addon.index',compact('addon1','addonMasters','brandMatsers','modelLineMasters','data','content'));

    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kitItemDropdown = Addon::whereIn('addon_type',['P','SP'])->pluck('id');
        $kitItemDropdown = AddonDetails::whereIn('addon_id', $kitItemDropdown)->with('AddonName')->get();
        $addons = Addon::whereIn('addon_type',['P','SP','K','W'])->select('id','name')->orderBy('name', 'ASC')->get();
        $brands = Brand::select('id','brand_name')->get();
        $modelLines = MasterModelLines::select('id','brand_id','model_line')->get();
        $suppliers = Supplier::select('id','supplier')->get();
        return view('addon.create',compact('addons','brands','modelLines','suppliers','kitItemDropdown'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $authId = Auth::id();
//         $validator = Validator::make($request->all(), [
//             'addon_id' => 'required',
//             'addon_code' => 'required',
//             'addon_type' => 'required',
//             'fixing_charges_included' => 'required'
//             // 'purchase_price' => 'required',
//             // 'lead_time' => 'required',
//             // 'additional_remarks' => 'required',
//             // 'brand' => 'required',
//             // 'model' => 'required',
//             // 'image' => 'nullable|image|mimes:svg,jpeg,png,jpg,gif,bmp,tiff,jpe',
//             //|max:2048',
//             // nullable|image|max:1000
//             // mimes:jpeg,png,jpg,gif
//             // 'mimes:jpeg,bmp,png'
//             // mimes:jpg,jpeg,png,bmp,tiff
//             // max:4096'
//             // Use mimetypes: rule with image/jpeg that covers 3 extension variations for the jpeg format: jpg jpeg jpe.

// // Use image rule which covers jpeg, png, bmp, gif, or svg including jpeg's extension variations
//         ]);

//         if ($validator->fails())
//         {
//             dd('hi');
//             // return redirect(route('addon.create'))->withInput()->withErrors($validator);
//         }
//          else
//         {
    $input = $request->all();
    if($request->image)
    {
        $fileName = auth()->id() . '_' . time() . '.'. $request->image->extension();
        $type = $request->image->getClientMimeType();
        $size = $request->image->getSize();
        $request->image->move(public_path('addon_image'), $fileName);
        $input['image'] = $fileName;
    }

            $input['addon_id'] = $request->addon_id;
            $input['currency'] = 'AED';
            $input['created_by'] = $authId;

            // $lastAddonCode = AddonDetails::orderBy('id', 'desc')->first()->addon_code;
            // $lastAddonCodeNumber = substr($lastAddonCode, 1, 5);
            // $newAddonCodeNumber =  $lastAddonCodeNumber+1;
            // $newAddonCode = "P".$newAddonCodeNumber;
            // $input['addon_code'] = $newAddonCode;
            $masterAddonByType = Addon::where('addon_type',$request->addon_type)->pluck('id');
            if(count($masterAddonByType) > 0)
            {
                $lastAddonCode = AddonDetails::whereIn('addon_id',$masterAddonByType)->orderBy('id', 'desc')->first();
                if($lastAddonCode != '')
                {
                    $lastAddonCodeNo =  $lastAddonCode->addon_code;
                    $lastAddonCodeNumber = substr($lastAddonCodeNo, 1, 5);
                    if($request->addon_type == 'SP')
                    {
                        $lastAddonCodeNumber = substr($lastAddonCodeNo, 2, 5);
                    }
                   else{
                    $lastAddonCodeNumber = substr($lastAddonCodeNo, 1, 5);
                   }
                    $newAddonCodeNumber =  $lastAddonCodeNumber+1;
                    $input['addon_code'] = $request->addon_type.$newAddonCodeNumber;
                }
                else
                {
                    $input['addon_code'] = $request->addon_type."1";
                }
            }
            else
            {
                $input['addon_code'] = $request->addon_type."1";
            }
            // dd($input);
            $input['addon_type_name'] = $request->addon_type;
            $input['addon_id']= $request->addon_id;
            $addon_details = AddonDetails::create($input);
            if($request->selling_price != '')
            {
                $createsellingPriceInput['addon_details_id'] = $addon_details->id;
                $createsellingPriceInput['selling_price'] = $request->selling_price;
                $createsellingPriceInput['status'] = 'pending';
                $createsellingPriceInput['created_by'] = $authId;
                AddonSellingPrice::create($createsellingPriceInput);
            }
            if($request->addon_type == 'SP')
            {
                if($request->brand)
                {
                    if(count($request->brand) > 0)
                    {
                        $createAddType['created_by'] = $authId;
                        $createAddType['addon_details_id'] = $addon_details->id;
                        foreach($request->brand as $brandData)
                        {
                            if($brandData['brand_id'] == 'allbrands')
                            {
                                $addon_details->is_all_brands = 'yes';
                                $addon_details->update();
                            }
                            else
                            {
                                $createAddType['brand_id'] = $brandData['brand_id'];
                                if(isset($brandData['model']))
                                {
                                    if(count($brandData['model']) > 0)
                                    {
                                        foreach($brandData['model'] as $brandModelDta)
                                        {
                                            if($brandModelDta['model_id'])
                                            {
                                                if($brandModelDta['model_id'] == 'allmodellines')
                                                {
                                                    $createAddType['is_all_model_lines'] = 'yes';
                                                    $creBranModelDes = AddonTypes::create($createAddType);
                                                }
                                                // else
                                                // {
                                                //     $createAddType['model_id'] = $brandModelDta['model_id'];
                                                //     if(isset($brandModelDta['model_number']))
                                                //     {
                                                //         foreach($brandModelDta['model_number'] as $modelDescr)
                                                //         {
                                                //             $createAddType['model_number'] = $modelDescr;
                                                //             $creBranModelDes = AddonTypes::create($createAddType);
                                                //         }
                                                //     }
                                                //     else
                                                //     {
                                                //         $creBranModelDes = AddonTypes::create($createAddType);
                                                //     }
                                                // }
                                                elseif(isset($brandModelDta['model_number']))
                                                {
                                                    $createAddType['model_id'] = $brandModelDta['model_id'];
                                                    foreach($brandModelDta['model_number'] as $modelDescr)
                                                    {
                                                        $createAddType['model_number'] = $modelDescr;
                                                        $creBranModelDes = AddonTypes::create($createAddType);
                                                    }
                                                }
                                                else
                                                {
                                                    $createAddType['model_number'] = NULL;
                                                    $createAddType['model_id'] = $brandModelDta['model_id'];
                                                    $creBranModelDes = AddonTypes::create($createAddType);
                                                }
                                            }
                                        }
                                    }
                                }
                                else
                                {
                                    $createAddType['is_all_model_lines'] = 'yes';
                                    $creBranModelDes = AddonTypes::create($createAddType);
                                }
                            }
                        }
                    }
                }
            }
            else
            {
                if($request->brandModel)
                {
                    if(count($request->brandModel) > 0 )
                    {
                        $inputaddontype['addon_details_id'] = $addon_details->id;
                        $inputaddontype['created_by'] = $authId;
                        foreach($request->brandModel as $brandModel)
                        {
                            if($brandModel['brand_id'] == 'allbrands')
                            {
                                $addon_details->is_all_brands = 'yes';
                                $addon_details->update();
                            }
                            else
                            {
                                $inputaddontype['brand_id'] = $brandModel['brand_id'];
                                if(isset($brandModel['modelline_id']))
                                {
                                    foreach($brandModel['modelline_id'] as $modelline_id)
                                    {
                                        if($modelline_id == 'allmodellines')
                                        {
                                            $inputaddontype['is_all_model_lines'] = 'yes';
                                        }
                                        else
                                        {
                                            $inputaddontype['model_id'] = $modelline_id;
                                        }
                                        $addon_types = AddonTypes::create($inputaddontype);
                                    }
                                }
                                else
                                {
                                    $inputaddontype['is_all_model_lines'] = 'yes';
                                    $addon_types = AddonTypes::create($inputaddontype);
                                }
                            }
                        }
                    }
                }
            }
            if($request->addon_type == 'K')
            {
                if($request->kitSupplierAndPrice)
                {
                    if(count($request->kitSupplierAndPrice) > 0 )
                    {
                        $supPriInput['created_by'] = $authId;
                        foreach($request->kitSupplierAndPrice as $kitSupplierAndPriceData)
                        {
                            $supPriInput['supplier_id'] = $kitSupplierAndPriceData['supplier_id'];
                            $supPriInput['addon_details_id'] = $addon_details->id;
                            $supPriInput['purchase_price_aed'] = $kitSupplierAndPriceData['supplier_addon_purchase_price_in_aed'];
                            $supPriInput['purchase_price_usd'] = $kitSupplierAndPriceData['supplier_addon_purchase_price_in_usd'];
                            if($supPriInput['purchase_price_aed'] != '')
                            {
                                $CreateSupAddPri = SupplierAddons::create($supPriInput);
                                $supPriInput['supplier_addon_id'] = $CreateSupAddPri->id;
                                $createHistrory = PurchasePriceHistory::create($supPriInput);
                            }
                            if(count($kitSupplierAndPriceData['item']) > 0)
                            {
                                $createkit['created_by'] = $authId;
                                $createkit['supplier_addon_id'] = $CreateSupAddPri->id;
                                foreach($kitSupplierAndPriceData['item'] as $kitItemData)
                                {
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
            else
            {
                if($request->supplierAndPrice)
                {
                    if(count($request->supplierAndPrice) > 0)
                    {
                        foreach($request->supplierAndPrice as $supplierAndPrice1)
                        {
                            $supPriInput['addon_details_id'] = $addon_details->id;
                            $supPriInput['purchase_price_aed'] = $supplierAndPrice1['addon_purchase_price_in_aed'];
                            $supPriInput['purchase_price_usd'] = $supplierAndPrice1['addon_purchase_price_in_usd'];
                            $supPriInput['created_by'] = $authId;

                            if($supplierAndPrice1['supplier_id'])
                            {
                                if(count($supplierAndPrice1['supplier_id']) > 0)
                                {
                                    foreach($supplierAndPrice1['supplier_id'] as $suppl1)
                                    {
                                        $supPriInput['supplier_id'] = $suppl1;
                                        if($supPriInput['purchase_price_aed'] != '')
                                        {
                                            $CreateSupAddPri = SupplierAddons::create($supPriInput);
                                            $supPriInput['supplier_addon_id'] = $CreateSupAddPri->id;
                                            $createHistrory = PurchasePriceHistory::create($supPriInput);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $data = 'all';
            return redirect()->route('addon.list', $data)
                            ->with('success','Addon created successfully');
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(Addon $addon)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Addon $addon)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Addon $addon)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $addonDetails = AddonDetails::findOrFail($id);
        DB::beginTransaction();
            AddonTypes::where('addon_details_id', $id)->delete();
            AddonSellingPrice::where('addon_details_id', $id)->delete(); 
            $supplierAddons = SupplierAddons::where('addon_details_id', $id)->get();
            foreach($supplierAddons as $supplierAddon)
            {
                PurchasePriceHistory::where('supplier_addon_id', $supplierAddon->id)->delete(); 
            }
            SupplierAddons::where('addon_details_id', $id)->delete();
            $addonDetails->delete();
        DB::commit();
        return response(true);
    }
    public function editAddonDetails($id)
    {
        // AddonSuppliersUsed
        // one addon - multiple suppliers - suppliers cannot repeat
        $addonDetails = AddonDetails::where('id',$id)->with('AddonTypes','AddonName','AddonSuppliers','SellingPrice','PendingSellingPrice')->first();
        $price = '';
        $price = SupplierAddons::where('addon_details_id',$addonDetails->id)->where('status','active')->orderBy('purchase_price_aed','ASC')->first();
        $addonDetails->LeastPurchasePrices = $price;
        $addons = Addon::select('id','name')->get();
        $brands = Brand::select('id','brand_name')->get();
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
        $kitItemDropdown = Addon::whereIn('addon_type',['P','SP'])->pluck('id');
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
        return view('addon.edit.edit',compact('addons','brands','modelLines','addonDetails','suppliers','kitItemDropdown','supplierAddons'));
    }
    public function updateAddonDetails(Request $request, $id)
    {
        // dd($request->all());
        $authId = Auth::id();
        $addon_details = AddonDetails::find($id);
        if($request->image)
        {
            $fileName = auth()->id() . '_' . time() . '.'. $request->image->extension();
            $type = $request->image->getClientMimeType();
            $size = $request->image->getSize();
            $request->image->move(public_path('addon_image'), $fileName);
            $addon_details->image = $fileName;
        }
        $addon_details->addon_id = $request->addon_id;
        $addon_details->updated_by = $authId;
        $addon_details->addon_type_name = $request->addon_type_hiden;
        $addon_details->addon_code = $request->addon_code;
        $addon_details->payment_condition = $request->payment_condition;
        $addon_details->lead_time = $request->lead_time;
        $addon_details->additional_remarks = $request->additional_remarks;
        $addon_details->is_all_brands = $request->additional_remarks;
        $addon_details->fixing_charges_included = $request->fixing_charges_included;
        if($request->fixing_charges_included == 'no')
        {
            $addon_details->fixing_charge_amount = $request->fixing_charge_amount;
        }
        else
        {
            $addon_details->fixing_charge_amount = NULL;
        }
        if($request->addon_type_hiden == 'SP')
        {
            $addon_details->part_number = $request->part_number;
        }
        else
        {
            $addon_details->part_number = NULL;
        }
        $addon_details->update();
            if($request->addon_type == 'SP')
            {
                if($request->brand)
                {
                    if(count($request->brand) > 0)
                    {
                        $createAddType['created_by'] = $authId;
                        $createAddType['addon_details_id'] = $addon_details->id;
                        foreach($request->brand as $brandData)
                        {
                            if($brandData['brand_id'] == 'allbrands')
                            {
                                $addon_details->is_all_brands = 'yes';
                                $addon_details->update();
                            }
                            else
                            {
                                $createAddType['brand_id'] = $brandData['brand_id'];
                                if(isset($brandData['model']))
                                {
                                    if(count($brandData['model']) > 0)
                                    {
                                        foreach($brandData['model'] as $brandModelDta)
                                        {
                                            if($brandModelDta['model_id'])
                                            {
                                                if($brandModelDta['model_id'] == 'allmodellines')
                                                {
                                                    $createAddType['is_all_model_lines'] = 'yes';
                                                    $creBranModelDes = AddonTypes::create($createAddType);
                                                }
                                                else
                                                {
                                                    $createAddType['model_id'] = $brandModelDta['model_id'];
                                                    if(isset($brandModelDta['model_number']))
                                                    {
                                                        foreach($brandModelDta['model_number'] as $modelDescr)
                                                        {
                                                            $createAddType['model_number'] = $modelDescr;
                                                            $creBranModelDes = AddonTypes::create($createAddType);
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $creBranModelDes = AddonTypes::create($createAddType);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                else
                                {
                                    $createAddType['is_all_model_lines'] = 'yes';
                                    $creBranModelDes = AddonTypes::create($createAddType);
                                }
                            }
                        }
                    }
                }
            }
            else
            {
                if($request->brandModel)
                {
                    if(count($request->brandModel) > 0 )
                    {
                        $inputaddontype['addon_details_id'] = $addon_details->id;
                        $inputaddontype['created_by'] = $authId;
                        foreach($request->brandModel as $brandModel)
                        {
                            if($brandModel['brand_id'] == 'allbrands')
                            {
                                $addon_details->is_all_brands = 'yes';
                                $addon_details->update();
                            }
                            else
                            {
                                $inputaddontype['brand_id'] = $brandModel['brand_id'];
                                if(isset($brandModel['modelline_id']))
                                {
                                    foreach($brandModel['modelline_id'] as $modelline_id)
                                    {
                                        if($modelline_id == 'allmodellines')
                                        {
                                            $inputaddontype['is_all_model_lines'] = 'yes';
                                        }
                                        else
                                        {
                                            $inputaddontype['model_id'] = $modelline_id;
                                        }
                                        $addon_types = AddonTypes::create($inputaddontype);
                                    }
                                }
                                else
                                {
                                    $inputaddontype['is_all_model_lines'] = 'yes';
                                    $addon_types = AddonTypes::create($inputaddontype);
                                }
                            }
                        }
                    }
                }
            }
            if($request->addon_type == 'K')
            {
                if($request->kitSupplierAndPrice)
                {
                    if(count($request->kitSupplierAndPrice) > 0 )
                    {
                        $supPriInput['created_by'] = $authId;
                        foreach($request->kitSupplierAndPrice as $kitSupplierAndPriceData)
                        {
                            $supPriInput['supplier_id'] = $kitSupplierAndPriceData['supplier_id'];
                            $supPriInput['addon_details_id'] = $addon_details->id;
                            $supPriInput['purchase_price_aed'] = $kitSupplierAndPriceData['supplier_addon_purchase_price_in_aed'];
                            $supPriInput['purchase_price_usd'] = $kitSupplierAndPriceData['supplier_addon_purchase_price_in_usd'];
                            $CreateSupAddPri = SupplierAddons::create($supPriInput);
                            $supPriInput['supplier_addon_id'] = $CreateSupAddPri->id;
                            $createHistrory = PurchasePriceHistory::create($supPriInput);
                            if(count($kitSupplierAndPriceData['item']) > 0)
                            {
                                $createkit['created_by'] = $authId;
                                $createkit['supplier_addon_id'] = $CreateSupAddPri->id;
                                foreach($kitSupplierAndPriceData['item'] as $kitItemData)
                                {
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
            else
            {
                $NotNelete = [];
                $existingSuppliers = [];
                $existingSuppliers2 = SupplierAddons::where([
                                                        ['addon_details_id','=',$id],
                                                        ['status','=','active'],
                                                    ])->select('supplier_id')->get();
                foreach( $existingSuppliers2 as $existingSuppliers1)
                {
                    array_push($existingSuppliers,$existingSuppliers1->supplier_id);
                }
                if($request->supplierAndPrice)
                {
                    if(count($request->supplierAndPrice) > 0)
                    {
                        foreach($request->supplierAndPrice as $supplierAndPrice1)
                        {
                            if($supplierAndPrice1['supplier_id'])
                            {
                                if(count($supplierAndPrice1['supplier_id']) > 0)
                                {
                                    foreach($supplierAndPrice1['supplier_id'] as $suppl1)
                                    {
                                        array_push($NotNelete,$suppl1);
                                        if(in_array($suppl1, $existingSuppliers))
                                        {
                                            $update =  SupplierAddons::where('supplier_id',$suppl1)->where('addon_details_id',$id)->first();
                                            $oldPrice = $update->purchase_price_aed;
                                            $oldSellingPrice = $update->purchase_price_usd;
                                            $update->updated_by = Auth::id();
                                            $update->supplier_id = $suppl1;
                                            $update->purchase_price_aed =  $supplierAndPrice1['addon_purchase_price_in_aed'];
                                            $update->purchase_price_usd =  $supplierAndPrice1['addon_purchase_price_in_usd'];
                                            $update->update();
                                            if($oldPrice != $update->purchase_price_aed)
                                            {
                                                $oldHistry = PurchasePriceHistory::Where([
                                                    ['supplier_addon_id', '=', $update->id],
                                                    ['status','=','active'],
                                                ])->latest()->first();
                                                if($oldHistry)
                                                {
                                                    $oldHistry->status = 'inactive';
                                                    $oldHistry->updated_by = Auth::id();
                                                    $oldHistry->update();                                                  
                                                }
                                            }
                                        }
                                        else
                                        {
                                            $supPriInput['addon_details_id'] = $addon_details->id;
                                            $supPriInput['purchase_price_aed'] = $supplierAndPrice1['addon_purchase_price_in_aed'];
                                            $supPriInput['purchase_price_usd'] = $supplierAndPrice1['addon_purchase_price_in_usd'];
                                            $supPriInput['created_by'] = $authId;
                                            $supPriInput['supplier_id'] = $suppl1;
                                            $CreateSupAddPri = SupplierAddons::create($supPriInput);
                                            $supPriInput['supplier_addon_id'] = $CreateSupAddPri->id;
                                            $createHistrory = PurchasePriceHistory::create($supPriInput);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $newExiSuppliers2 = [];
                    $newExiSuppliers = SupplierAddons::where([
                                                        ['addon_details_id','=',$id],
                                                        ['status','=','active'],
                                                    ])->pluck('id');
                    foreach($newExiSuppliers as $newExiSuppliers1)
                    {
                        array_push($newExiSuppliers2,$newExiSuppliers1);
                    }
                    $differenceArray = array_diff($newExiSuppliers2, $NotNelete);
                    $delete = SupplierAddons::whereIn('supplier_id',$differenceArray)
                                            ->where([
                                                ['addon_details_id','=',$id],
                                                ['status','=','active'],
                                            ])->get();
                    foreach($delete as $del)
                    {     
                        $deletehistory = PurchasePriceHistory::where('supplier_addon_id',$del->id)->get();
                        foreach($deletehistory as $deletehistory1)
                        {
                            $deletehistory1->delete();
                        }
                        $del = $del->delete();
                    }
                }
            }
            $data = 'all';
            return redirect()->route('addon.list', $data)
                            ->with('success','Addon created successfully');
    }
    public function existingImage($id)
    {
        // $data['relatedAddons'] = DB::table('addon_details')
        //             ->where('addon_details.addon_id',$id)
        //             ->join('addons','addons.id','addon_details.addon_id')
        //             ->join('addon_types','addon_types.addon_details_id','addon_details.id')
        //             ->select('addons.name','addon_details.id','addon_details.addon_id','addon_details.addon_code',
        //             'addon_details.lead_time','addon_details.additional_remarks','addon_details.image','addon_types.brand_id','addon_types.model_id')
        //             ->orderBy('addon_details.id','ASC')
        //             ->get();
        // $data['existingSuppliers'] = SupplierAddons::where('addon_details_id',$id)->select('supplier_id')->get();
        $data['addon_type'] = Addon::where('id',$id)->select('addon_type')->first();
        if($data['addon_type']->addon_type != '')
        {
            $addonType = $data['addon_type']->addon_type;
            $masterAddonByType = Addon::where('addon_type',$addonType)->pluck('id');
            if($masterAddonByType != '')
            {
                $lastAddonCode = AddonDetails::whereIn('addon_id',$masterAddonByType)->orderBy('id', 'desc')->first();
                if($lastAddonCode != '')
                {
                    $lastAddonCodeNo =  $lastAddonCode->addon_code;
                    if($addonType == 'SP')
                    {
                        $lastAddonCodeNumber = substr($lastAddonCodeNo, 2, 5);
                    }
                   else{
                    $lastAddonCodeNumber = substr($lastAddonCodeNo, 1, 5);
                   }
                    $newAddonCodeNumber =  $lastAddonCodeNumber+1;
                    $data['newAddonCode'] = $addonType.$newAddonCodeNumber;
                }
                else
                {
                    $data['newAddonCode'] = $addonType."1";
                }
            }
            else
            {
                $data['newAddonCode'] = $addonType."1";
            }
        }
        else
        {
            $data['newAddonCode'] = "";
        }
        return response()->json($data);
    }
    public function addonFilters(Request $request)
    {
        $addonIds = $addonsTableData = [];
        if($request->Data != 'all')
        {
            $addonIds = AddonDetails::where('addon_type_name',$request->Data);
        }
        else
        {
            $addonIds = AddonDetails::whereIn('addon_type_name',['P','SP','K']);
        }
        if($request->AddonIds)
        {
            $addonIds = $addonIds->whereIn('addon_id',$request->AddonIds);
        }
        if($request->BrandIds)
        {
            if(in_array('yes',$request->BrandIds))
            {
                $addonIds = $addonIds->where('is_all_brands','yes');
            }
            else
            {
                $addonIds = $addonIds->where('is_all_brands','yes');
                $addonIds = $addonIds->orWhereHas('AddonTypes', function($q) use($request)
                {
                    $q = $q->whereIn('brand_id',$request->BrandIds);
                    if($request->ModelLineIds)
                    {
                        if(in_array('yes',$request->ModelLineIds))
                        {
                            $q = $q->orWhere('is_all_model_lines','yes');
                        }
                        else
                        {
                            $q->where( function ($query) use ($request)
                            {
                                $query = $query->whereIn('model_id',$request->ModelLineIds);
                            });
                        }
                    }
                });
            }
        }
        elseif($request->ModelLineIds)
        {
            $addonIds = $addonIds->where('is_all_brands','yes');
            $addonIds = $addonIds->orWhereHas('AddonTypes', function($q) use($request)
            {
                if(!in_array('yes',$request->ModelLineIds))
                {
                    $q = $q->whereIn('model_id',$request->ModelLineIds);
                }
            });
        }
        $addonIds = $addonIds->pluck('id');
        $data['addonsBox'] = $addonIds;

        if(count($addonIds) > 0)
        {
            $addonsTableData = AddonDetails::whereIn('id',$addonIds)
            ->with('AddonTypes', function($q) use($request)
            {
                if($request->BrandIds)
                {
                    $q = $q->whereIn('brand_id',$request->BrandIds);
                }
                if($request->ModelLineIds)
                {
                    $q = $q->whereIn('model_id',$request->ModelLineIds);
                }
                $q = $q->with('brands','modelLines','modelDescription')->get();
            })
            ->with('AddonName','SellingPrice','PendingSellingPrice');
            $addonsTableData = $addonsTableData->orderBy('id', 'DESC')->get();
            foreach($addonsTableData as $addon)
            {
                $price = '';
                $price = SupplierAddons::where('addon_details_id',$addon->id)->where('status','active')->orderBy('purchase_price_aed','ASC')->first();
                $addon->LeastPurchasePrices = $price;
            }
        }

        $data['addonsTable'] = $addonsTableData;
        return response()->json($data);
    }

    public function createMasterAddon(Request $request)
    {
        $authId = Auth::id();
        $validator = Validator::make($request->all(), [
           'name' => 'required',
        ]);
        if ($validator->fails())
        {
            return redirect(route('addon.create'))->withInput()->withErrors($validator);
        }
        else
        {
            $input = $request->all();
            $input['created_by'] = $authId;
            $addons = Addon::create($input);
            return response()->json($addons);
        }
    }
    public function fetchAddonData($id, $quotationId, $VehiclesId)
    {
        $result = DB::table('addon_types')
                ->join('addon_details', 'addon_types.addon_details_id', '=', 'addon_details.id')
                ->join('addons', 'addon_details.addon_id', '=', 'addons.id')
                ->where('addon_types.model_id', '=', $id)
                ->select('*', 'addon_types.id as idp')
                ->get();
        return view('quotation.addone',compact('result', 'quotationId', 'VehiclesId'));
    }
    public function brandModels(Request $request, $id)
    {
        $data = MasterModelLines::where('brand_id',$id)->select('id','model_line');
        if($request->filteredArray) {
            if(count($request->filteredArray) > 0)
            {
                $data = $data->whereNotIn('id', $request->filteredArray);
            }
        }
        $data = $data->get();
        return response()->json($data);
    }
    public function getAddonCodeAndDropdown(Request $request)
    {
        if($request->addon_type)
        {
            $masterAddonByType = Addon::where('addon_type',$request->addon_type)->pluck('id');
            if($masterAddonByType != '')
            {
                $lastAddonCode = AddonDetails::whereIn('addon_id',$masterAddonByType)->orderBy('id', 'desc')->first();
                if($lastAddonCode != '')
                {
                    $lastAddonCodeNo =  $lastAddonCode->addon_code;
                    if($request->addon_type == 'SP')
                    {
                        $lastAddonCodeNumber = substr($lastAddonCodeNo, 2, 5);
                    }
                   else{
                    $lastAddonCodeNumber = substr($lastAddonCodeNo, 1, 5);
                   }
                    $newAddonCodeNumber =  $lastAddonCodeNumber+1;
                    $data['newAddonCode'] = $request->addon_type.$newAddonCodeNumber;
                }
                else
                {
                    $data['newAddonCode'] = $request->addon_type."1";
                }
            }
            else
            {
                $data['newAddonCode'] = $request->addon_type."1";
            }
            $data['addonMasters'] = Addon::whereIn('id',$masterAddonByType)->select('id','name')->orderBy('name', 'ASC')->get();

            $addonType = $request->addon_type;
            if($addonType == 'P'){
                $data['suppliers'] = Supplier::with('supplierTypes')
                    ->whereHas('supplierTypes', function ($query) {
                        $query->where('supplier_type', Supplier::SUPPLIER_TYPE_ACCESSORIES);
                    });
            }else if($addonType == 'SP') {
                $data['suppliers'] = Supplier::with('supplierTypes')
                    ->whereHas('supplierTypes', function ($query) {
                        $query->where('supplier_type', Supplier::SUPPLIER_TYPE_SPARE_PARTS);
                    });
            }
            else if($addonType == 'K') {
                $data['suppliers'] = Supplier::with('supplierTypes')
                    ->whereHas('supplierTypes', function ($query) {
                        $query->whereIn('supplier_type', [Supplier::SUPPLIER_TYPE_SPARE_PARTS, Supplier::SUPPLIER_TYPE_ACCESSORIES]);
                    });
            }

            $data['suppliers'] = $data['suppliers']->get();


        }
        else
        {
            $data['newAddonCode'] = "";
        }
        return response()->json($data);
    }
    public function getModelDescriptionDropdown(Request $request)
    {
        $data['model_description'] = MasterModelDescription::whereIn('model_line_id',$request->model_line_id)->select('id','model_description')->get();
        return response()->json($data);
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
        return view('addon.kititems',compact('supplierAddonDetails'));
    }
    public function statusChange(Request $request)
    {
        $authId = Auth::id();
        $sellingPrice = AddonSellingPrice::find($request->id);
        if($request->status == 'active')
        {
            $oldSellingPrice = AddonSellingPrice::where('addon_details_id',$sellingPrice->addon_details_id)->where('status','active')->first();
            if($oldSellingPrice != '')
            {
                $oldSellingPrice->status = 'inactive';
                $oldSellingPrice->updated_by = $authId;
                $oldSellingPrice->save();
            }
        }
        $sellingPrice->status = $request->status;
        $sellingPrice->status_updated_by = $authId;
        $sellingPrice->save();
        return response($sellingPrice, 200);
    }
    public function UpdateSellingPrice(Request $request, $id)
    {
        $request->validate([
            'selling_price' => 'required'
        ]);
        $authId = Auth::id();
        $data = AddonSellingPrice::find($id);
        $data->selling_price = $request->selling_price;
        $data->updated_by = $authId;
        $data->save();
        return redirect()->back()->with('success','Addon Selling Price Updated successfully.');
    }
    public function getSupplierForAddon(Request $request)
    {
        $data = Supplier::select('id','supplier');

        $addonType = $request->addonType;
        if($addonType == 'P'){
            $data = Supplier::with('supplierTypes')
                ->whereHas('supplierTypes', function ($query) {
                    $query->where('supplier_type', Supplier::SUPPLIER_TYPE_ACCESSORIES);
                })->select('id','supplier');
        }else if($addonType == 'SP') {
            $data = Supplier::with('supplierTypes')
                ->whereHas('supplierTypes', function ($query) {
                    $query->where('supplier_type', Supplier::SUPPLIER_TYPE_SPARE_PARTS);
                })->select('id','supplier');
        }
        else if($addonType == 'K') {
            $data = Supplier::with('supplierTypes')
                ->whereHas('supplierTypes', function ($query) {
                    $query->whereIn('supplier_type', [Supplier::SUPPLIER_TYPE_SPARE_PARTS, Supplier::SUPPLIER_TYPE_ACCESSORIES]);
                })->select('id','supplier');
        }
        if($request->filteredArray)
        {
            if(count($request->filteredArray) > 0)
            {
                $data = $data->whereNotIn('id', $request->filteredArray);
            }
        }
//        if($request->id) {
//            $id = $request->id;
//            $alreadyAddedAddonIds = SupplierAddons::whereHas('AddonSuppliers', function ($query) use($id) {
//                $query->where('supplier_id', $id);
//            })->pluck('addon_id');
//            $data = $data->whereNotIn('id', $alreadyAddedAddonIds);
//        }

        $data = $data->get();
        return response()->json($data);
    }
    public function getSupplierForAddonType(Request $request)
    {
        $addonType = $request->addonType;
        if($addonType == 'P'){
            $data = Supplier::with('supplierTypes')
                ->whereHas('supplierTypes', function ($query) {
                    $query->where('supplier_type', Supplier::SUPPLIER_TYPE_ACCESSORIES);
                });
        }else if($addonType == 'SP') {
            $data = Supplier::with('supplierTypes')
                ->whereHas('supplierTypes', function ($query) {
                    $query->where('supplier_type', Supplier::SUPPLIER_TYPE_SPARE_PARTS);
                });
        }
        else if($addonType == 'K') {
            $data = Supplier::with('supplierTypes')
                ->whereHas('supplierTypes', function ($query) {
                    $query->whereIn('supplier_type', [Supplier::SUPPLIER_TYPE_SPARE_PARTS, Supplier::SUPPLIER_TYPE_ACCESSORIES]);
                });
        }

        $data = $data->get();

        return $data;
    }
    public function createSellingPrice(Request $request, $id)
    {
        $this->validate($request, [
            'selling_price' => 'required',
        ]);
        $authId = Auth::id();

        $input['selling_price'] = $request->selling_price;
        $input['addon_details_id'] = $id;
        $input['created_by'] = $authId;
        $input['status'] = 'pending';
        $createSellingPrice = AddonSellingPrice::create($input);
        $data = 'all';
        return redirect()->route('addon.list', $data)
                        ->with('success','Addon created successfully');
    }

    public function getKitItemsForAddon(Request $request)
    {
        $kitItemDropdown = Addon::whereIn('addon_type',['P','SP'])->pluck('id');
        $data = AddonDetails::select('id','addon_code','addon_id')
                ->whereIn('addon_id', $kitItemDropdown)->with('AddonName');
        if($request->filteredArray)
        {
            if(count($request->filteredArray) > 0)
            {
                $data = $data->whereNotIn('id', $request->filteredArray);
            }
        }
            //        if($request->id) {
            //            $id = $request->id;
            //            $alreadyAddedAddonIds = SupplierAddons::whereHas('AddonSuppliers', function ($query) use($id) {
            //                $query->where('supplier_id', $id);
            //            })->pluck('addon_id');
            //            $data = $data->whereNotIn('id', $alreadyAddedAddonIds);
            //        }
        $data = $data->get();
        return response()->json($data);
    }
    public function addonStatusChange(Request $request)
    {
        $addon = AddonDetails::find($request->id);
        $addon->status = $request->status;

        $addon->save();
        return response($addon, 200);
    }
    public function getModelLinesForAddons(Request $request) {

        $data = MasterModelLines::select('id','model_line');
        info($request->filteredArray);
        if($request->filteredArray)
        {
            if(count($request->filteredArray) > 0)
            {
                info($request->filteredArray);
                $data = $data->whereNotIn('id',$request->filteredArray);
            }
        }
        if($request->id) {
            $id = $request->id;
            $alreadyAddedModelLines = AddonTypes::where('brand_id',$id)->pluck('model_id');
            $data = $data->whereNotIn('id', $alreadyAddedModelLines);
        }
        $data = $data->get();
        info($data);
        return response()->json($data);

    }
    public function getBrandForAddons(Request $request) {

        $data = Brand::select('id','brand_name');
        if($request->filteredArray)
        {
            if(count($request->filteredArray) > 0)
            {
                $data = $data->whereNotIn('id',$request->filteredArray);
            }
        }
//        if($request->id) {
//            $id = $request->id;
//            $alreadyAddedModelLines = AddonTypes::where('brand_id',$id)->pluck('model_id');
//            $data = $data->whereNotIn('id', $alreadyAddedModelLines);
//        }
        $data = $data->get();
        return response()->json($data);

    }
}

