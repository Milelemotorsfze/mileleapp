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
use App\Models\KitCommonItem;
use App\Models\SparePartsNumber;
use App\Models\AddonDescription;
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

        $addon1 = AddonDetails::orderBy('id', 'DESC');

        if($data != 'all')
        {
            $addon1 = $addon1->where('addon_type_name',$data);
        }
        $addon1 = $addon1->orderBy('id', 'ASC')->get();
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
        $addons = Addon::whereIn('addon_type',['P','SP','K','W'])->select('id','name','addon_type')->orderBy('name', 'ASC')->get();
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
        // dd($request->all());
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
//    info($input);
//    dd($input);
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
                $lastAddonCode = AddonDetails::whereIn('addon_id',$masterAddonByType)->withTrashed()->orderBy('id', 'desc')->first();
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
            $input['addon_type_name'] = $request->addon_type;
            $input['addon_id']= $request->addon_id;

            if($request->description != null) {
                $input['description'] = $request->description;
            }else {
                if($request->addon_type == 'P' || $request->addon_type == 'SP')
                {
                    $exisingDescription = AddonDescription::where([
                                                            ['addon_id','=',$request->addon_id],
                                                            ['description','=',$request->description_text]
                    ])->first();
                    if($exisingDescription != '')
                    {
                        $input['description'] = $exisingDescription->id;
                    }
                    else
                    {
                        $createDescription['addon_id'] = $request->addon_id;
                        $createDescription['description'] = $request->description_text;
                        $createdDesc = AddonDescription::create($createDescription);
                        $input['description'] = $createdDesc->id;
                    }
                }
            }


            $addon_details = AddonDetails::create($input);
            if($request->addon_type == 'SP')
            {
                if(count($request->part_number) > 0)
                {
                    foreach($request->part_number as $part_number)
                    {
                        $createPartNum = [];
                        $createPartNum['addon_details_id'] = $addon_details->id;
                        $createPartNum['part_number'] = $part_number;
                        $createPartNumber = SparePartsNumber::create($createPartNum);
                    }
                }
            }
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
//                if($request->addon_type == 'P' OR $request->addon_type == 'K')
//                {
//                    $input['model_year_start'] = NULL;
//                    $input['model_year_end'] = NULL;
//                }
//                else
//                {
//                    if($request->model_year_start != '' && $request->model_year_end != '')
//                    {
//                        if(intval($request->model_year_start) <= intval($request->model_year_end))
//                        {
//                            $input['model_year_start'] = $request->model_year_start;
//                            $input['model_year_end'] = $request->model_year_end;
//                        }
//                        else
//                        {
//                            $input['model_year_start'] = NULL;
//                            $input['model_year_end'] = NULL;
//                        }
//                    }
//                    elseif($request->model_year_start != '' && $request->model_year_end == '')
//                    {
//                        $input['model_year_start'] = $request->model_year_start;
//                        $input['model_year_end'] = $request->model_year_start;
//                    }
//                }
//                info($request->all());
                if($request->brand)
                {
                    if(count($request->brand) > 0)
                    {
                        foreach($request->brand as $brandData)
                        {
                            if($brandData['brand_id'] == 'allbrands')
                            {
                                $addon_details->is_all_brands = 'yes';
                                $addon_details->update();
                            }
                            else
                            {
                                if(isset($brandData['model']))
                                {
                                    if(count($brandData['model']) > 0)
                                    {
                                        foreach($brandData['model'] as $brandModelDta)
                                        {
                                            $createAddType = [];
                                            $createAddType['created_by'] = $authId;
                                            $createAddType['addon_details_id'] = $addon_details->id;
                                            $createAddType['brand_id'] = $brandData['brand_id'];
                                            $createAddType['model_year_start'] = $brandModelDta['model_year_start'];
                                            $createAddType['model_year_end'] = $brandModelDta['model_year_end'];

                                            if($brandModelDta['model_id'])
                                            {
                                                if($brandModelDta['model_id'] == 'allmodellines')
                                                {
                                                    $createAddType['is_all_model_lines'] = 'yes';
                                                    $creBranModelDes = AddonTypes::create($createAddType);
                                                }
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
                                                    $createAddType['model_id'] = $brandModelDta['model_id'];
                                                    $creBranModelDes = AddonTypes::create($createAddType);
                                                }
                                            }
                                        }
                                    }
                                }
                                else
                                {
                                    $createAddType = [];
                                    $createAddType['created_by'] = $authId;
                                    $createAddType['addon_details_id'] = $addon_details->id;
                                    $createAddType['brand_id'] = $brandData['brand_id'];
                                    $createAddType['is_all_model_lines'] = 'yes';

                                    $creBranModelDes = AddonTypes::create($createAddType);
                                }
                            }
                        }
                    }
                }
            }
            elseif ($request->addon_type == 'K') {
                if($request->brand_id)
                {
                    $brandId = $request->brand_id;
                    $addon_details->is_all_brands = 'no';
                    $addon_details->update();
                    if(isset($request->brandModel))
                    {
                        if(count( $request->brandModel) > 0)
                        {
                            foreach($request->brandModel as $key => $brandModelData)
                            {
                                info("inside loop");
                                foreach ($brandModelData['model_number'] as $modelNumber) {
                                    $createAddType = [];
                                    $createAddType['created_by'] = $authId;
                                    $createAddType['addon_details_id'] = $addon_details->id;
                                    $createAddType['brand_id'] = $brandId;
                                    $createAddType['model_id'] = $brandModelData['model_line_id'];
                                    $createAddType['is_all_model_lines'] = 'no';
                                    $createAddType['model_number'] = $modelNumber;
                                    $creBranModelDes = AddonTypes::create($createAddType);

                                }
                            }
                        }
                    }
                    else
                    {
                        $createAddType = [];
                        $createAddType['created_by'] = $authId;
                        $createAddType['addon_details_id'] = $addon_details->id;
                        $createAddType['brand_id'] = $brandId;
                        $createAddType['is_all_model_lines'] = 'no';
                        $creBranModelDes = AddonTypes::create($createAddType);
                    }

                }
            }
            else
            {
                if($request->brandModel)
                {
                    if(count($request->brandModel) > 0 )
                    {
                        foreach($request->brandModel as $brandModel)
                        {
                            if($brandModel['brand_id'] == 'allbrands')
                            {
                                $addon_details->is_all_brands = 'yes';
                                $addon_details->update();
                            }
                            else
                            {
                                if(isset($brandModel['modelline_id']))
                                {
                                    foreach($brandModel['modelline_id'] as $modelline_id)
                                    {
                                        $inputaddontype = [];
                                        $inputaddontype['addon_details_id'] = $addon_details->id;
                                        $inputaddontype['created_by'] = $authId;
                                        $inputaddontype['brand_id'] = $brandModel['brand_id'];
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
                                    $inputaddontype = [];
                                    $inputaddontype['addon_details_id'] = $addon_details->id;
                                    $inputaddontype['created_by'] = $authId;
                                    $inputaddontype['brand_id'] = $brandModel['brand_id'];
                                    $createAddType['is_all_model_lines'] = 'yes';
                                    $addon_types = AddonTypes::create($inputaddontype);
                                }
                            }
                        }
                    }
                }
            }
            if($request->addon_type == 'K')
            {
                if($request->mainItem)
                {
                    if(count($request->mainItem) > 0 )
                    {
                        foreach($request->mainItem as $kitItemData)
                        {
                            $createkit = [];
                            $createkit['created_by'] = $authId;
                            $createkit['item_id'] = $kitItemData['item'];
                            $createkit['addon_details_id'] = $addon_details->id;
                            $createkit['quantity'] = $kitItemData['quantity'];
                            $CreateSupAddPri = KitCommonItem::create($createkit);
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
                                            if(isset($supplierAndPrice1['lead_time']) && $supplierAndPrice1['lead_time_max'])
                                            {
                                                if($supplierAndPrice1['lead_time'] != '' && $supplierAndPrice1['lead_time_max'] != '')
                                                {
                                                    if(intval($supplierAndPrice1['lead_time']) == intval($supplierAndPrice1['lead_time_max']))
                                                    {
                                                        $supPriInput['lead_time_min'] = $supplierAndPrice1['lead_time'];
                                                        $supPriInput['lead_time_max'] = NULL;
                                                    }
                                                    elseif(intval($supplierAndPrice1['lead_time']) < intval($supplierAndPrice1['lead_time_max']))
                                                    {
                                                        $supPriInput['lead_time_min'] = $supplierAndPrice1['lead_time'];
                                                        $supPriInput['lead_time_max'] = $supplierAndPrice1['lead_time_max'];
                                                    }
                                                }
                                                elseif($supplierAndPrice1['lead_time'] != '' && $supplierAndPrice1['lead_time_max'] == '')
                                                {
                                                    $supPriInput['lead_time_min'] = $supplierAndPrice1['lead_time'];
                                                    $supPriInput['lead_time_max'] = NULL;
                                                }
                                                else
                                                {
                                                    $supPriInput['lead_time_min'] = NULL;
                                                    $supPriInput['lead_time_max'] = NULL;
                                                }
                                            }
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

            if($request->addon_type == 'K')
            {
                // return redirect()->route('kit.suppliers', $addon_details->id)
                //                 ->with('success','Kit created successfully');
                return redirect()->route('kit.kitItems', $addon_details->id)
                                ->with('success','Kit created successfully');
            }
            else
            {
                $data = 'all';
                return redirect()->route('addon.list', $data)
                                ->with('success','Addon created successfully');
            }

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
                KitItems::where('supplier_addon_id',$supplierAddon->id)->delete();
                PurchasePriceHistory::where('supplier_addon_id', $supplierAddon->id)->delete();
            }
            KitCommonItem::where('addon_details_id',$id)->delete();
            SupplierAddons::where('addon_details_id', $id)->delete();
            $addonDetails->delete();
        DB::commit();
        return response(true);
    }
    public function editAddonDetails($id)
    {
        // AddonSuppliersUsed
        // one addon - multiple suppliers - suppliers cannot repeat
        $addonDetails = AddonDetails::where('id',$id)->with('partNumbers','AddonTypes','AddonName','AddonSuppliers','SellingPrice','PendingSellingPrice')->first();
        $price = '';
        $price = SupplierAddons::where('addon_details_id',$addonDetails->id)->where('status','active')->orderBy('purchase_price_aed','ASC')->first();
        $addonDetails->LeastPurchasePrices = $price;
        $addons = Addon::select('id','name','addon_type')->get();
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
        $kitItemDropdown = Addon::whereIn('addon_type',['P','SP'])->pluck('id');
        $kitItemDropdown = AddonDetails::whereIn('addon_id', $kitItemDropdown)->with('AddonName')->get();
        $supplierAddons = SupplierAddons::where([
                                            ['addon_details_id', '=', $addonDetails->id],
                                            ['status', '=', 'active'],
                                        ])->groupBy(['purchase_price_aed','purchase_price_usd','lead_time_min','lead_time_max'])
                                        ->select('id','purchase_price_aed','purchase_price_usd','addon_details_id','status','lead_time_min','lead_time_max')
                                        ->get();
        foreach($supplierAddons as $supplierAddon)
        {
            $supplierId = [];
            $supplierId = SupplierAddons::where([
                                            ['purchase_price_aed', '=', $supplierAddon->purchase_price_aed],
                                            ['purchase_price_usd', '=', $supplierAddon->purchase_price_usd],
                                            ['lead_time_min', '=', $supplierAddon->lead_time_min],
                                            ['lead_time_max', '=', $supplierAddon->lead_time_max],
                                        ])->pluck('supplier_id');
            $supplierAddon->suppliers = Supplier::whereIn('id',$supplierId)->select('id','supplier')->get();
        }
        // $descriptions = AddonDetails::where('addon_type_name', $addonDetails->addon_type_name)
        //     ->where('addon_id', $addonDetails->addon_id)
        //     ->whereNotNull('description')->select('id','description')
        //     ->groupBy('description')->get();
        $descriptions = AddonDescription::where('addon_id', $addonDetails->addon_id)->whereNotNull('description')->select('id','description')->get();

        return view('addon.edit.edit',compact('addons','brands','modelLines','addonDetails','suppliers',
            'kitItemDropdown','supplierAddons','existingBrandModel','descriptions'));
    }
    public function updateAddonDetails(Request $request, $id)
    {
        // dd($request->all());
        $request->addon_type = $request->addon_type_hiden;
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
            $deletePartNumbers = SparePartsNumber::where('addon_details_id',$id)->get();
            if(count($deletePartNumbers) > 0)
            {
                foreach($deletePartNumbers as $deletePartNumber)
                {
                    $deletePartNumber->delete();
                }
            }
            if(count($request->part_number) > 0)
            {
                foreach($request->part_number as $part_number)
                {
                    $createPartNum = [];
                    $createPartNum['addon_details_id'] = $addon_details->id;
                    $createPartNum['part_number'] = $part_number;
                    $createPartNumber = SparePartsNumber::create($createPartNum);
                }
            }
        }
        else
        {
            $deletePartNumbers = SparePartsNumber::where('addon_details_id',$id)->get();
            if(count($deletePartNumbers) > 0)
            {
                foreach($deletePartNumbers as $deletePartNumber)
                {
                    $deletePartNumber->delete();
                }
            }
        }

        if($request->description != null) {
            $addon_details->description = $request->description;
        }else {
            if($request->addon_type_hiden == 'P' || $request->addon_type_hiden == 'SP')
            {
                $exisingDescription = AddonDescription::where([
                                                        ['addon_id','=',$request->addon_id],
                                                        ['description','=',$request->description_text]
                ])->first();
                if($exisingDescription != '')
                {
                    $addon_details->description = $exisingDescription->id;
                }
                else
                {
                    $createDescription['addon_id'] = $request->addon_id;
                    $createDescription['description'] = $request->description_text;
                    $createdDesc = AddonDescription::create($createDescription);
                    $addon_details->description = $createdDesc->id;
                }
            }
        }

            // if($request->addon_type_hiden == 'P' OR $request->addon_type_hiden == 'K')
            // {
            //     $addon_details->model_year_start = NULL;
            //     $addon_details->model_year_end = NULL;
            // }
            // else
            // {
            //     if($request->model_year_start != '' && $request->model_year_end != '')
            //     {
            //         if(intval($request->model_year_start) <= intval($request->model_year_end))
            //         {
            //             $addon_details->model_year_start = $request->model_year_start;
            //             $addon_details->model_year_end = $request->model_year_end;
            //         }
            //         else
            //         {
            //             $addon_details->model_year_start = NULL;
            //             $addon_details->model_year_end = NULL;
            //         }
            //     }
            //     elseif($request->model_year_start != '' && $request->model_year_end == '')
            //     {
            //         $addon_details->model_year_start = $request->model_year_start;
            //         $addon_details->model_year_end = $request->model_year_start;
            //     }
            // }
        $addon_details->update();
        $deleteAddonTypes = [];
        $deleteAddonTypes = AddonTypes::where('addon_details_id',$id)->get();
        if(count($deleteAddonTypes) > 0)
        {
            foreach($deleteAddonTypes as $deleteAddonType)
            {
                $deleteAddonType->deleted_by = Auth::id();
                $deleteAddonType->update();
                $deleteAddonType->delete();
            }
        }
            if($request->addon_type == 'SP')
            {
//                dd($request->all());
                if($request->brand)
                {
                    if(count($request->brand) > 0)
                    {
                        foreach($request->brand as $brandData)
                        {
                            if($brandData['brand_id'] == 'allbrands')
                            {
                                $addon_details->is_all_brands = 'yes';
                                $addon_details->update();
                            }
                            else
                            {
                                $addon_details->is_all_brands = 'no';
                                $addon_details->update();
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
                                                    $createAddType = [];
                                                    $createAddType['created_by'] = $authId;
                                                    $createAddType['addon_details_id'] = $addon_details->id;
                                                    $createAddType['brand_id'] = $brandData['brand_id'];
                                                    $createAddType['is_all_model_lines'] = 'yes';
                                                    $createAddType['model_year_start'] = $brandModelDta['model_year_start'];
                                                    $createAddType['model_year_end'] = $brandModelDta['model_year_end'];

                                                    $creBranModelDes = AddonTypes::create($createAddType);
                                                }
                                                else
                                                {
                                                    if(isset($brandModelDta['model_number']))
                                                    {
                                                        foreach($brandModelDta['model_number'] as $modelDescr)
                                                        {
                                                            $createAddType = [];
                                                            $createAddType['created_by'] = $authId;
                                                            $createAddType['addon_details_id'] = $addon_details->id;
                                                            $createAddType['brand_id'] = $brandData['brand_id'];
                                                            $createAddType['model_id'] = $brandModelDta['model_id'];
                                                            $createAddType['model_number'] = $modelDescr;
                                                            $createAddType['model_year_start'] = $brandModelDta['model_year_start'];
                                                            $createAddType['model_year_end'] = $brandModelDta['model_year_end'];
                                                            $creBranModelDes = AddonTypes::create($createAddType);
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $createAddType = [];
                                                        $createAddType['created_by'] = $authId;
                                                        $createAddType['addon_details_id'] = $addon_details->id;
                                                        $createAddType['brand_id'] = $brandData['brand_id'];
                                                        $createAddType['model_id'] = $brandModelDta['model_id'];
                                                        $createAddType['model_year_start'] = $brandModelDta['model_year_start'];
                                                        $createAddType['model_year_end'] = $brandModelDta['model_year_end'];
                                                        $creBranModelDes = AddonTypes::create($createAddType);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                else
                                {
                                    $createAddType = [];
                                    $createAddType['created_by'] = $authId;
                                    $createAddType['addon_details_id'] = $addon_details->id;
                                    $createAddType['brand_id'] = $brandData['brand_id'];
                                    $createAddType['is_all_model_lines'] = 'yes';
                                    $creBranModelDes = AddonTypes::create($createAddType);
                                }
                            }
                        }
                    }
                }
            }
            elseif ($request->addon_type == 'K') {
                if($request->brand_id)
                {
                    $brandId = $request->brand_id;
                    $addon_details->is_all_brands = 'no';
                    $addon_details->update();
                    if(isset($request->brandModel))
                    {
                        if(count($request->brandModel) > 0)
                        {
//                            dd($request->all());
                            // delete existing data

//                            $addonTypes = AddonTypes::where('addon_details_id', $addon_details->id)->delete();
                            // add new model line and numbers
                            foreach($request->brandModel as $key => $brandModelData)
                            {
//                                info("inside loop");
                                foreach ($brandModelData['model_number'] as $modelNumber) {
                                    $createAddType = [];
                                    $createAddType['created_by'] = $authId;
                                    $createAddType['addon_details_id'] = $addon_details->id;
                                    $createAddType['brand_id'] = $brandId;
                                    $createAddType['model_id'] = $brandModelData['model_line_id'];
                                    $createAddType['is_all_model_lines'] = 'no';
                                    $createAddType['model_number'] = $modelNumber;
                                    $creBranModelDes = AddonTypes::create($createAddType);

                                }
                            }
                        }
                    }
                    else
                    {
                        $createAddType = [];
                        $createAddType['created_by'] = $authId;
                        $createAddType['addon_details_id'] = $addon_details->id;
                        $createAddType['brand_id'] = $brandId;
                        $createAddType['is_all_model_lines'] = 'no';
                        $creBranModelDes = AddonTypes::create($createAddType);
                    }

                }
            }
            else
            {
                if($request->brandModel)
                {
                    if(count($request->brandModel) > 0 )
                    {
                        foreach($request->brandModel as $brandModel)
                        {
                            if($brandModel['brand_id'] == 'allbrands')
                            {
                                $addon_details->is_all_brands = 'yes';
                                $addon_details->update();
                            }
                            else
                            {
                                $addon_details->is_all_brands = 'no';
                                $addon_details->update();
                                 // delete exising and create new
                                if(isset($brandModel['modelline_id']))
                                {
                                    foreach($brandModel['modelline_id'] as $modelline_id)
                                    {
                                        $inputaddontype = [];
                                        $inputaddontype['addon_details_id'] = $addon_details->id;
                                        $inputaddontype['created_by'] = $authId;
                                        $inputaddontype['brand_id'] = $brandModel['brand_id'];
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
                                    $inputaddontype = [];
                                    $inputaddontype['addon_details_id'] = $addon_details->id;
                                    $inputaddontype['created_by'] = $authId;
                                    $inputaddontype['brand_id'] = $brandModel['brand_id'];
                                    $createAddType['is_all_model_lines'] = 'yes';
                                    $addon_types = AddonTypes::create($inputaddontype);
                                }
                            }
                        }
                    }
                }
            }
            if($request->addon_type == 'K')
            {
                if($request->mainItem)
                {
                    if(count($request->mainItem) > 0 )
                    {
                        $NotNelete = [];
                        $existingItems = [];
                        $existingItems2 = KitCommonItem::where('addon_details_id',$id)->select('item_id')->get();
                        foreach( $existingItems2 as $existingItems1)
                        {
                            array_push($existingItems,$existingItems1->item_id);
                        }
                        $existingItemSuppliers = [];
                        $existingItemSuppliers = SupplierAddons::where('addon_details_id',$id)->select('id','supplier_id')->get();
                        $existingItemSuppliersId = [];
                        $existingItemSuppliersId = SupplierAddons::where('addon_details_id',$id)->pluck('id');
                        foreach($request->mainItem as $kitItemData)
                        {
                            // update
                            if(in_array($kitItemData['item'], $existingItems))
                            {
                                // update
                                $update =  KitCommonItem::where('item_id',$kitItemData['item'])->where('addon_details_id',$id)->first();
                                $update->updated_by = Auth::id();
                                $update->quantity =  $kitItemData['quantity'];
                                $update->update();
                                array_push($NotNelete,$update->id);
                                if(count($existingItemSuppliers) > 0)
                                {
                                    foreach($existingItemSuppliers as $existingItemSupplier)
                                    {
                                        $updateSuplierKitQuanty = KitItems::where('addon_details_id',$update->item_id)->where('supplier_addon_id',$existingItemSupplier->id)->first();
                                        if($updateSuplierKitQuanty != '')
                                        {
                                            if($updateSuplierKitQuanty->quantity != $update->quantity)
                                            {
                                                $updateSuplierKitQuanty->quantity = $update->quantity;
                                                $updateSuplierKitQuanty->total_price_in_aed = $updateSuplierKitQuanty->unit_price_in_aed * $update->quantity;
                                                $updateSuplierKitQuanty->unit_price_in_usd = $updateSuplierKitQuanty->unit_price_in_aed / 3.6725;
                                                $updateSuplierKitQuanty->total_price_in_usd = $updateSuplierKitQuanty->total_price_in_aed / 3.6725;
                                                $updateSuplierKitQuanty->updated_by = Auth::id();
                                                $updateSuplierKitQuanty->update();
                                                // var totalPriceAED = quantity * unitPriceAED;
                                                // totalPriceAED = totalPriceAED.toFixed(4);
                                                // totalPriceAED = parseFloat(totalPriceAED);
                                                // var unitPriceUSD = unitPriceAED / 3.6725;
                                                // unitPriceUSD = unitPriceUSD.toFixed(4);
                                                // unitPriceUSD = parseFloat(unitPriceUSD);
                                                // var totalPriceUSD = totalPriceAED / 3.6725;
                                                // totalPriceUSD = totalPriceUSD.toFixed(4);
                                                // totalPriceUSD = parseFloat(totalPriceUSD);
                                            }
                                        }
                                    }
                                }
                            }
                            // create
                            else
                            {
                                //create
                                $createkit = [];
                                $createkit['created_by'] = $authId;
                                $createkit['item_id'] = $kitItemData['item'];
                                $createkit['addon_details_id'] = $addon_details->id;
                                $createkit['quantity'] = $kitItemData['quantity'];
                                $CreateSupAddPri = KitCommonItem::create($createkit);
                                array_push($NotNelete,$CreateSupAddPri->id);
                                if(count($existingItemSuppliers) > 0)
                                {
                                    foreach($existingItemSuppliers as $existingItemSupplier)
                                    {
                                        // $supPriInput = [];
                                        // $supPriInput['created_by'] = Auth::id();
                                        // $supPriInput['supplier_id'] = $existingItemSupplier->supplier_id;
                                        // $supPriInput['addon_details_id'] = $addon_details->id;
                                        // $CreateSupAddPri1 = SupplierAddons::create($supPriInput);
                                        $createKitItemSup = [];
                                        $createKitItemSup['addon_details_id'] = $CreateSupAddPri->item_id;
                                        $createKitItemSup['supplier_addon_id'] = $existingItemSupplier->id;
                                        $createKitItemSup['quantity'] = $CreateSupAddPri->quantity;
                                        // $createKitItemSup['unit_price_in_aed'] = '0';
                                        // $createKitItemSup['total_price_in_aed'] = '0';
                                        // $createKitItemSup['unit_price_in_usd'] = '0';
                                        // $createKitItemSup['total_price_in_usd'] = '0';
                                        $createKitItemSup['created_by '] = Auth::id();
                                        $createSupAddKit = KitItems::create($createKitItemSup);
                                        // $supPriInput['supplier_addon_id'] = $existingItemSupplier->id;
                                        // $createHistrory1 = PurchasePriceHistory::create($supPriInput);
                                    }
                                }
                            }
                        }
                        // delete
                        $newExiItems2 = [];
                        $newExiItems = KitCommonItem::where('addon_details_id',$id)->pluck('id');
                        foreach($newExiItems as $newExiItems1)
                        {
                            array_push($newExiItems2,$newExiItems1);
                        }
                        $differenceArray = array_diff($newExiItems2, $NotNelete);
                        $delete = KitCommonItem::whereIn('id',$differenceArray)->get();
                        foreach($delete as $del)
                        {
                            $deleteSupKit = KitItems::where('addon_details_id',$del->item_id)->whereIn('supplier_addon_id',$existingItemSuppliersId)->get();
                            foreach($deleteSupKit as $deleteSupKit1)
                            {
                                $deleteSupKit1->delete();
                            }
                            $deletehistory = PurchasePriceHistory::whereIn('supplier_addon_id',$existingItemSuppliersId)->get();
                            foreach($deletehistory as $deletehistory1)
                            {
                                $deletehistory1->delete();
                            }
                            $del = $del->delete();
                        }

                        // Recalculate Price by item and Quantity
                        $supAddIds = [];
                        $supAddIds = SupplierAddons::where('addon_details_id',$id)->pluck('id');
                        if(count($supAddIds) > 0)
                        {
                            foreach($supAddIds as $supAddId)
                            {
                                $aedSum = '';
                                $usdSum = '';
                                $aedSum = KitItems::where('supplier_addon_id',$supAddId)->sum('total_price_in_aed');
                                $usdSum = KitItems::where('supplier_addon_id',$supAddId)->sum('total_price_in_usd');
                                $sup = SupplierAddons::where('id',$supAddId)->first();
                                if($sup->purchase_price_aed != $aedSum)
                                {
                                    $sup->purchase_price_aed = $aedSum;
                                    $sup->purchase_price_usd = $usdSum;
                                    $sup->updated_by = Auth::id();
                                    $sup->save();
                                    $supPriInput = [];
                                    $supPriInput['created_by'] = Auth::id();
                                    $supPriInput['supplier_id'] = $sup->supplier_id;
                                    $supPriInput['addon_details_id'] = $request->kit_addon_id;
                                    $supPriInput['purchase_price_aed'] =  $aedSum;
                                    $supPriInput['purchase_price_usd'] =  $usdSum;
                                    $supPriInput['supplier_addon_id'] = $sup->id;
                                    $createHistrory = PurchasePriceHistory::create($supPriInput);
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

                                            if($supplierAndPrice1['lead_time'] != '' && $supplierAndPrice1['lead_time_max'] != '')
                                            {
                                                if(intval($supplierAndPrice1['lead_time']) == intval($supplierAndPrice1['lead_time_max']))
                                                {
                                                    $update->lead_time_min = $supplierAndPrice1['lead_time'];
                                                    $update->lead_time_max = NULL;
                                                }
                                                elseif(intval($supplierAndPrice1['lead_time']) < intval($supplierAndPrice1['lead_time_max']))
                                                {
                                                    $update->lead_time_min = $supplierAndPrice1['lead_time'];
                                                    $update->lead_time_max = $supplierAndPrice1['lead_time_max'];
                                                }
                                            }
                                            else
                                            {
                                                $update->lead_time_min = $supplierAndPrice1['lead_time'];
                                                $update->lead_time_max = $supplierAndPrice1['lead_time_max'];
                                            }
                                            $update->update();
                                            if($oldPrice != $update->purchase_price_aed)
                                            {
                                                $createNewHistry['purchase_price_aed'] = $update->purchase_price_aed;
                                                $createNewHistry['purchase_price_usd'] = $update->purchase_price_usd;
                                                $createNewHistry['supplier_addon_id'] = $update->id;
                                                $createNewHistry['status'] = 'active';
                                                $createNewHistry['created_by'] = Auth::id();
                                                $createNewHistry33 = PurchasePriceHistory::create($createNewHistry);
                                            }
                                        }
                                        else
                                        {
                                            $supPriInput['addon_details_id'] = $addon_details->id;
                                            $supPriInput['purchase_price_aed'] = $supplierAndPrice1['addon_purchase_price_in_aed'];
                                            $supPriInput['purchase_price_usd'] = $supplierAndPrice1['addon_purchase_price_in_usd'];
                                            if($supplierAndPrice1['lead_time'] != '' && $supplierAndPrice1['lead_time_max'] != '')
                                            {
                                                if(intval($supplierAndPrice1['lead_time']) == intval($supplierAndPrice1['lead_time_max']))
                                                {
                                                    $supPriInput['lead_time_min'] = $supplierAndPrice1['lead_time'];
                                                    $supPriInput['lead_time_max'] = NULL;
                                                }
                                                elseif(intval($supplierAndPrice1['lead_time']) < intval($supplierAndPrice1['lead_time_max']))
                                                {
                                                    $supPriInput['lead_time_min'] = $supplierAndPrice1['lead_time'];
                                                    $supPriInput['lead_time_max'] = $supplierAndPrice1['lead_time_max'];
                                                }
                                            }
                                            else
                                            {
                                                $supPriInput['lead_time_min'] = $supplierAndPrice1['lead_time'];
                                                $supPriInput['lead_time_max'] = $supplierAndPrice1['lead_time_max'];
                                            }
                                            $supPriInput['created_by'] = $authId;
                                            $supPriInput['supplier_id'] = $suppl1;
                                            $CreateSupAddPri = SupplierAddons::create($supPriInput);
                                            $supPriInput['supplier_addon_id'] = $CreateSupAddPri->id;
                                            $createHistrory2 = PurchasePriceHistory::create($supPriInput);
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
            // if($request->addon_type == 'K')
            // {
            //     return redirect()->route('kit.editsuppliers', $id)
            //                     ->with('success','Kit created successfully');
            // }

            if($request->addon_type == 'K')
            {
                // return redirect()->route('kit.suppliers', $addon_details->id)
                //                 ->with('success','Kit created successfully');
                return redirect()->route('kit.kitItems', $id)
                ->with('success','Kit created successfully');
            }else if( $request->kit_id != '') {
                return redirect()->route('kit.kitItems', $request->kit_id)
                    ->with('success','Kit created successfully');
            }
            else
            {
                $data = 'all';
            return redirect()->route('addon.list', $data)
                            ->with('success','Addon created successfully');
            }
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
                $lastAddonCode = AddonDetails::whereIn('addon_id',$masterAddonByType)->withTrashed()->orderBy('id', 'desc')->first();
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
        if($request->addon_type == 'K') {
            $validator = Validator::make($request->all(), [
                'kit_year' => 'required',
                'kit_km' => 'required',

            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);
        }
        if ($validator->fails())
        {
            return redirect(route('addon.create'))->withInput()->withErrors($validator);
        }
        else
        {
            $input = $request->all();
            $input['created_by'] = $authId;
            if($request->addon_type == 'K') {
                $input['name'] = 'Kit: '.$request->kit_year.' year | '.$request->kit_km.'KM';
            }

            $isExisting = Addon::where('name', $input['name'])
                ->where('addon_type', $request->addon_type)->first();
            if($isExisting) {
                $addons['error'] =  "This Addon is Already Existing";
            }else{
                $addons = Addon::create($input);
            }
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
                $lastAddonCode = AddonDetails::whereIn('addon_id',$masterAddonByType)->withTrashed()->orderBy('id', 'desc')->first();
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
        $supplierAddonDetails = AddonDetails::where('id',$id)->with('partNumbers','AddonName','AddonTypes.brands','SellingPrice','AddonSuppliers.Suppliers',
        'AddonSuppliers.Kit.addon.AddonName')->first();
// dd($supplierAddonDetails);
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
        $kitItemDropdown = Addon::whereIn('addon_type',['SP'])->pluck('id');
        $data = AddonDetails::select('id','addon_code','addon_id','description')
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
        if($request->filteredArray)
        {
            if(count($request->filteredArray) > 0)
            {
                $data = $data->whereNotIn('id',$request->filteredArray);
            }
        }
        if($request->id) {
            $id = $request->id;
            $alreadyAddedModelLines = AddonTypes::where('brand_id',$id)->pluck('model_id');
            $data = $data->whereNotIn('id', $alreadyAddedModelLines);
        }
        $data = $data->get();
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
    public function getAddonDescription(Request $request) {
        $descriptions = AddonDescription::where('addon_id', $request->addon_id)
                                        ->whereNotNull('description')->select('id','description')
                                        ->get();
        return response($descriptions);

    }
    public function getUniqueAccessories(Request $request) {

        $description = null;
        if($request->description != null) {
            $description = $request->description;
        }elseif ($request->newDescription != null) {
            $description = $request->newDescription;
        }

        if($request->brand == 'allbrands') {
            $isExisting = AddonDetails::where('is_all_brands', 'yes')
                ->where('addon_id', $request->addon_id)
                ->where('description', $description)
                ->where('addon_type_name', $request->addonType);
           $data['is_all_brands'] = $isExisting->count();
        }else{

            $existingAddonDetailIds = AddonDetails::where('addon_id', $request->addon_id)
                                    ->where('description', $description)
                                    ->where('addon_type_name', $request->addonType);

            if($request->id) {
                $existingAddonDetailIds = $existingAddonDetailIds->whereNot('id',$request->id);
            }
            $existingAddonDetailIds = $existingAddonDetailIds->pluck('id');

            $isExisting = AddonTypes::whereIn('addon_details_id', $existingAddonDetailIds)
                                ->where('brand_id', $request->brand);
            if($isExisting) {
                if($request->model_line == 'allmodellines') {
                    $isExisting = $isExisting->where('is_all_model_lines','yes');
                }else{
                    $modelLineArray = [];
                    if($request->model_line != null) {
                        $modelLineArray = $request->model_line;
                    }
                    $isExisting = $isExisting->whereIn('model_id',$modelLineArray);
                    if($isExisting && $request->addonType == 'P') {
                        $modelLines = $isExisting->get();
                        $models = [];
                        foreach ($modelLines as $modelLine) {
                            $models[] = $modelLine->modelLines->model_line ?? '';
                        }
                        $data['model_line'] = implode(",", $models);
                    }
                }
            }
        }
        if($isExisting) {
            $data['count'] = $isExisting->count();
        }else{
            $data['count'] = 0;
        }

        $data['index'] = $request->index;
        return response($data);

    }
    public function getUniqueSpareParts(Request $request) {

            $description = null;
            if($request->description != null) {
                $description = $request->description;
            }elseif ($request->newDescription != null) {
                $description = $request->newDescription;
            }

            $existingAddonDetailIds = AddonDetails::where('addon_id', $request->addon_id)
                ->where('description', $description)
                // ->where('part_number', $request->part_number)
                ->where('addon_type_name', $request->addonType);

            if($request->id) {
                $existingAddonDetailIds = $existingAddonDetailIds->whereNot('id',$request->id);
            }
            if($request->part_number && $request->part_number != '')
            {
                $existingAddonDetailIds = $existingAddonDetailIds->whereHas('partNumbers', function($q) use($request)
                {
                    $q = $q->where('part_number',$request->part_number);
                });
            }
            $existingAddonDetailIds = $existingAddonDetailIds->pluck('id');



            $isExisting = AddonTypes::whereIn('addon_details_id', $existingAddonDetailIds)
                                ->where('brand_id', $request->brand);
            if($isExisting) {
                $isExisting = $isExisting->where('model_id', $request->model_line);
                if($isExisting ) {
                    $modelNumber = [];
                    if($request->model_number != null) {
                        $modelNumber = $request->model_number;
                    }
                    $isExisting = $isExisting->whereIn('model_number', $modelNumber);
                    $modelNumbers = $isExisting->get();
                    $models = [];
                    foreach ($modelNumbers as $modelNumber) {
                        $models[] = $modelNumber->modelDescription->model_description ?? '';
                    }
                    $data['model_number'] = implode(",", $models);
                }
            }

        if($isExisting) {

            $data['count'] = $isExisting->count();
        }else{
            $data['count'] = 0;
        }

        $data['i'] = $request->i;
        $data['j'] = $request->j;

        return response($data);

    }
    public function getUniqueKits(Request $request) {

        $existingAddonDetailIds = AddonDetails::where('addon_id', $request->addon_id)
                                ->where('addon_type_name', $request->addonType);
        // if edit page
        if($request->id) {

            $existingAddonDetailIds = $existingAddonDetailIds->whereNot('id',$request->id);
        }
        $existingAddonDetailIds = $existingAddonDetailIds->pluck('id');

        $isExisting = AddonTypes::whereIn('addon_details_id', $existingAddonDetailIds)
                                ->where('brand_id', $request->brand);
        if($isExisting) {
            $isExisting = $isExisting->where('model_id', $request->model_line);
            if($isExisting ) {
                $modelNumber = [];
                if($request->model_number != null) {
                    $modelNumber = $request->model_number;
                }
                $isExisting = $isExisting->whereIn('model_number', $modelNumber);
                $modelNumbers = $isExisting->get();
                $models = [];
                foreach ($modelNumbers as $modelNumber) {
                    $models[] = $modelNumber->modelDescription->model_description ?? '';
                }
                $data['model_number'] = implode(",", $models);
            }
        }

        if($isExisting) {

            $data['count'] = $isExisting->count();
        }else{
            $data['count'] = 0;
        }
        $data['index'] = $request->index;

        return response($data);

    }
    public function getUniqueAddonDescription(Request $request) {
        if($request->description) {
            $isExist = AddonDetails::where('addon_type_name', $request->addonType)
                ->where('addon_id', $request->addon_id)
                ->where('description', $request->description)
                ->count();
        }else{
            $isExist = 0;
        }

        return response($isExist);

    }

}

