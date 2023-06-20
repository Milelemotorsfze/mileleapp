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
        $addon1 = AddonDetails::with('AddonName','AddonTypes.brands','AddonTypes.modelLines','LeastPurchasePrices','SellingPrice');
        if($data != 'all')
        {
            $addon1 = $addon1->where('addon_type_name',$data);
        }
        $addon1 = $addon1->orderBy('id', 'DESC')->get();
        $addonMasters = Addon::select('id','name')->orderBy('name', 'ASC')->get();
        $brandMatsers = Brand::select('id','brand_name')->orderBy('brand_name', 'ASC')->get();
        $modelLineMasters = MasterModelLines::select('id','brand_id','model_line')->orderBy('model_line', 'ASC')->get();
        $addons = DB::table('addon_details');
        if($data != 'all')
        {
            $addons = $addons->where('addon_details.addon_type_name',$data);
        }
        $addons= $addons->join('addons','addons.id','addon_details.addon_id')
                    ->join('addon_types','addon_types.addon_details_id','addon_details.id')
                    ->join('brands','brands.id','addon_types.brand_id')
                    ->join('master_model_lines','master_model_lines.id','addon_types.model_id')
                    // ->join('supplier_addons','supplier_addons.addon_details_id','addon_details.id')
                    // ->where('supplier_addons.status', '=', 'active')
                    // ->where('')
                    ->select('addons.name',
                    'addon_details.id as addon_details_table_id','addon_details.addon_id','addon_details.addon_code',
                    'addon_details.payment_condition','addon_details.lead_time',
                    'addon_details.additional_remarks','addon_details.image','addon_details.is_all_brands',
                    'addon_types.brand_id','addon_types.model_id','addon_types.is_all_model_lines','brands.brand_name',
                    'master_model_lines.model_line','addon_details.status')
                    ->orderBy('addon_details.id','ASC')
                    ->get()
                    ->toArray();

                    $addons3 = DB::table('addon_details');
                    if($data != 'all')
                    {
                        $addons3 = $addons3->where('addon_details.addon_type_name',$data);
                    }
                     $addons3= $addons3->join('addons','addons.id','addon_details.addon_id')
                                ->join('addon_types','addon_types.addon_details_id','addon_details.id')
                                ->join('brands','brands.id','addon_types.brand_id')
                                ->where('addon_types.is_all_model_lines','yes')
                                ->select('addons.name',
                                'addon_details.id as addon_details_table_id','addon_details.addon_id','addon_details.addon_code',
                                'addon_details.payment_condition','addon_details.lead_time',
                                'addon_details.additional_remarks','addon_details.image','addon_details.is_all_brands',
                                'addon_types.brand_id','addon_types.model_id','addon_types.is_all_model_lines','brands.brand_name',
                                'addon_details.status')
                                ->orderBy('addon_details.id','ASC')
                                ->get()
                                ->toArray();

                    $addons2 = DB::table('addon_details');
                    if($data != 'all')
                    {
                        $addons2 = $addons2->where('addon_details.addon_type_name',$data);
                    }
                     $addons2= $addons2
                     ->join('addons','addons.id','addon_details.addon_id')
                                ->where('addon_details.is_all_brands','yes')
                                ->select('addons.name','addon_details.id as addon_details_table_id','addon_details.addon_id','addon_details.addon_code','addon_details.payment_condition',
                                'addon_details.lead_time','addon_details.additional_remarks','addon_details.image','addon_details.is_all_brands','addon_details.status')
                                ->orderBy('addon_details.id','ASC')
                                ->get()->toArray();
                                $addons= array_merge($addons,$addons3);
                                $addons= array_merge($addons,$addons2);
        // dd($addons);
        return view('addon.index',compact('addons','addon1','addonMasters','brandMatsers','modelLineMasters','data','content'));
    }
    // {

    //     $addon1 = AddonDetails::with('AddonName','AddonTypes.brands','AddonTypes.modelLines');
    //     if($data != 'all')
    //     {
    //         $addon1 = $addon1->where('addon_type_name',$data);
    //     }
    //     $addon1 = $addon1->orderBy('id', 'ASC')->get();
    //     $addonMasters = Addon::select('id','name')->orderBy('name', 'ASC')->get();
    //     $brandMatsers = Brand::select('id','brand_name')->orderBy('brand_name', 'ASC')->get();
    //     $modelLineMasters = MasterModelLines::select('id','brand_id','model_line')->orderBy('model_line', 'ASC')->get();
    //     // $addons = AddonDetails::with('AddonTypes.brands','AddonTypes.modelLines','AddonTypes.brands')
    //     $addons = DB::table('addon_details');
    //     if($data != 'all')
    //     {
    //         $addons = $addons->where('addon_details.addon_type_name',$data);
    //     }
    //      $addons= $addons->join('addons','addons.id','addon_details.addon_id')
    //                 ->join('addon_types','addon_types.addon_details_id','addon_details.id')
    //                 ->join('brands','brands.id','addon_types.brand_id')
    //                 ->join('master_model_lines','master_model_lines.id','addon_types.model_id')
    //                 ->select('addons.name',
    //                 'addon_details.id as addon_details_table_id','addon_details.addon_id','addon_details.addon_code','addon_details.purchase_price',
    //                 'addon_details.payment_condition','addon_details.lead_time',
    //                 'addon_details.additional_remarks','addon_details.image','addon_details.is_all_brands',
    //                 'addon_types.brand_id','addon_types.model_id','addon_types.is_all_model_lines','brands.brand_name',
    //                 'master_model_lines.model_line','addon_details.status')
    //                 ->orderBy('addon_details.id','ASC')
    //                 ->get();
    //                 ->toArray();

    //                 $addons3 = DB::table('addon_details');
    //                 if($data != 'all')
    //                 {
    //                     $addons3 = $addons3->where('addon_details.addon_type_name',$data);
    //                 }
    //                  $addons3= $addons3->join('addons','addons.id','addon_details.addon_id')
    //                             ->join('addon_types','addon_types.addon_details_id','addon_details.id')
    //                             ->join('brands','brands.id','addon_types.brand_id')
    //                             ->where('addon_types.is_all_model_lines','yes')
    //                             ->select('addons.name',
    //                             'addon_details.id as addon_details_table_id','addon_details.addon_id','addon_details.addon_code','addon_details.purchase_price',
    //                             'addon_details.payment_condition','addon_details.lead_time',
    //                             'addon_details.additional_remarks','addon_details.image','addon_details.is_all_brands',
    //                             'addon_types.brand_id','addon_types.model_id','addon_types.is_all_model_lines','brands.brand_name',
    //                             'addon_details.status')
    //                             ->orderBy('addon_details.id','ASC')
    //                             ->get()
    //                             ->toArray();

    //                 $addons2 = DB::table('addon_details');
    //                 if($data != 'all')
    //                 {
    //                     $addons2 = $addons2->where('addon_details.addon_type_name',$data);
    //                 }
    //                  $addons2= $addons2
    //                  ->join('addons','addons.id','addon_details.addon_id')
    //                             ->where('addon_details.is_all_brands','yes')
    //                             ->select('addons.name','addon_details.id as addon_details_table_id','addon_details.addon_id','addon_details.addon_code','addon_details.purchase_price','addon_details.payment_condition',
    //                             'addon_details.lead_time','addon_details.additional_remarks','addon_details.image','addon_details.is_all_brands','addon_details.status')
    //                             ->orderBy('addon_details.id','ASC')
    //                             ->get()->toArray();
    //                             $addons= array_merge($addons,$addons3);
    //                             $addons= array_merge($addons,$addons2);

    //     return view('addon.index',compact('addons','addon1','addonMasters','brandMatsers','modelLineMasters','data'));
    // }
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
        // "addon_type" => "SP"
        // "addon_code" => "SP1"
        // "purchase_price" => "25"
        // "lead_time" => "44"
        // "payment_condition" => "435"
        // "fixing_charges_included" => "no"
        // "fixing_charge_amount" => "3453"
        // "part_number" => "5345"
        // "additional_remarks" => "hfh"
        // "model" => array:2 [▶]
        // "model_number" => array:2 [▶]
        // "br" => array:1 [▶]
        // "kitSupplierAndPrice" => array:1 [▶]
        // "supplierAndPrice" => array:2 [▶]
        // "image" => Illuminate\Http\UploadedFile {#1506 ▶}
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
                $createsellingPriceInput['status'] = 'active';
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
                                        $CreateSupAddPri = SupplierAddons::create($supPriInput);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Addon $addon)
    {
        //
    }
    public function editAddonDetails($id)
    {
        $addonDetails = AddonDetails::where('id',$id)->with('AddonTypes','AddonName')->first();
        $addons = Addon::select('id','name')->get();
        $brands = Brand::select('id','brand_name')->get();
        $modelLines = MasterModelLines::select('id','brand_id','model_line')->get();
        $suppliers = Supplier::select('id','supplier')->get();
        $kitItemDropdown = Addon::whereIn('addon_type',['P','SP'])->pluck('id');
        $kitItemDropdown = AddonDetails::whereIn('addon_id', $kitItemDropdown)->with('AddonName')->get();
        return view('addon.edit',compact('addons','brands','modelLines','addonDetails','suppliers','kitItemDropdown'));
    }
    public function updateAddonDetails(Request $request, $id)
    {
        $authId = Auth::id();
        $this->validate($request, [
            'addon_id' => 'required',
            'addon_code' => 'required',
            'purchase_price' => 'required',
            'lead_time' => 'required',
            'additional_remarks' => 'required',
            'brand' => 'required',
            'model' => 'required',
            'image' => 'max:2048',
        ]);
        $input = $request->all();
        $input['updated_by'] = $authId;
        $addonDetails = AddonDetails::find($id);
        $addonDetails->update($input);
        $inputaddontype['addon_details_id'] = $addonDetails->id;
        $inputaddontype['created_by'] = $authId;
        for($i=0; $i<count($request->brand); $i++)
        {
            if($request->addon_details_id[$i] == NULL)
            {
                $inputaddontype['brand_id'] = $request->brand[$i];
                $inputaddontype['model_id'] = $request->model[$i];
                $addon_types = AddonTypes::create($inputaddontype);
            }
            else
            {
                $inputaddontype['brand_id'] = $request->brand[$i];
                $inputaddontype['model_id'] = $request->model[$i];
                $addonDetails = AddonTypes::find($request->addon_details_id[$i]);
                $addonDetails->update($inputaddontype);
            }
        }
        return redirect()->route('addon.index')
                        ->with('success','addon updated successfully');
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
                    $lastAddonCodeNumber = substr($lastAddonCodeNo, 1, 5);
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

// dd($request->BrandIds);

        $addonIds = AddonDetails::with('AddonTypes')->whereHas('AddonTypes', function($q) use($request) {

            if($request->BrandIds)
            {
                // if($request->BrandIds == 'yes')
                // {
                //     $addonIds = $addonIds->where('is_all_brands','yes');
                // }
                // else
                // {
                    $q->whereIn('brand_id',$request->BrandIds);
                // }
            }
            if($request->ModelLineIds)
            {
            $q->whereIn('model_id',$request->ModelLineIds);
            }
        });
        // if(in_array('yes',$request->BrandIds))
        //     {
        //         $addonIds = $addonIds->where('is_all_brands','yes');
        //         // $addonIds = $addonIds->where('is_all_brands','yes');
        //     }
        if($request->AddonIds)
        {
            $addonIds = $addonIds->whereIn('addon_id',$request->AddonIds);
        }
        if($request->Data != 'all')
        {
            $addonIds = $addonIds->where('addon_type_name',$request->Data);
        }
        $addonIds = $addonIds->pluck('id');
        $data['addonsBox'] = $addonIds;
        $addons = DB::table('addon_details')->whereIn('addon_details.id',$addonIds);
        if($data != 'all')
        {
            $addons = $addons->where('addon_details.addon_type_name',$data);
        }
         $addons= $addons->join('addons','addons.id','addon_details.addon_id')
                    ->join('addon_types','addon_types.addon_details_id','addon_details.id')
                    ->join('brands','brands.id','addon_types.brand_id')
                    ->join('master_model_lines','master_model_lines.id','addon_types.model_id')
                    ->select('addons.name',
                    'addon_details.id as addon_details_table_id','addon_details.addon_id','addon_details.addon_code',
                    'addon_details.payment_condition','addon_details.lead_time',
                    'addon_details.additional_remarks','addon_details.image','addon_details.is_all_brands',
                    'addon_types.brand_id','addon_types.model_id','addon_types.is_all_model_lines','brands.brand_name',
                    'master_model_lines.model_line','addon_details.status')
                    ->orderBy('addon_details.id','ASC')
                    ->get()
                    ->toArray();

                    $addons3 = DB::table('addon_details')->whereIn('addon_details.id',$addonIds);
                    if($data != 'all')
                    {
                        $addons3 = $addons3->where('addon_details.addon_type_name',$data);
                    }
                     $addons3= $addons3->join('addons','addons.id','addon_details.addon_id')
                                ->join('addon_types','addon_types.addon_details_id','addon_details.id')
                                ->join('brands','brands.id','addon_types.brand_id')
                                ->where('addon_types.is_all_model_lines','yes')
                                ->select('addons.name',
                                'addon_details.id as addon_details_table_id','addon_details.addon_id','addon_details.addon_code',
                                'addon_details.payment_condition','addon_details.lead_time',
                                'addon_details.additional_remarks','addon_details.image','addon_details.is_all_brands',
                                'addon_types.brand_id','addon_types.model_id','addon_types.is_all_model_lines','brands.brand_name',
                                'addon_details.status')
                                ->orderBy('addon_details.id','ASC')
                                ->get()
                                ->toArray();

                    $addons2 = DB::table('addon_details')->whereIn('addon_details.id',$addonIds);
                    if($data != 'all')
                    {
                        $addons2 = $addons2->where('addon_details.addon_type_name',$data);
                    }
                     $addons2= $addons2
                     ->join('addons','addons.id','addon_details.addon_id')
                                ->where('addon_details.is_all_brands','yes')
                                ->select('addons.name','addon_details.id as addon_details_table_id','addon_details.addon_id','addon_details.addon_code','addon_details.payment_condition',
                                'addon_details.lead_time','addon_details.additional_remarks','addon_details.image','addon_details.is_all_brands','addon_details.status')
                                ->orderBy('addon_details.id','ASC')
                                ->get()->toArray();
                                $addons= array_merge($addons,$addons3);
                                $addons= array_merge($addons,$addons2);
                                $data['addonsTable'] = $addons;
// $data['']
// dd()
        // dd($data);
        // $addons = DB::table('addon_details')->join('addons','addons.id','addon_details.addon_id')
        //             ->join('addon_types','addon_types.addon_details_id','addon_details.id')
        //             ->join('brands','brands.id','addon_types.brand_id')
        //             ->join('master_model_lines','master_model_lines.id','addon_types.model_id');
        //             if($request->Data)
        //             {
        //                 $addons= $addons->where('addon_details.addon_type_name',$request->Data);
        //             }
        //             if($request->BrandIds)
        //             {
        //                 $addons= $addons->whereIn('addon_types.brand_id',$request->BrandIds);
        //             }
        //             if($request->ModelLineIds)
        //     {
        //         $addons= $addons->whereIn('addon_types.model_id',$request->ModelLineIds);
        //     }
        //     if($request->AddonIds)
        //     {
        //         $addons= $addons->whereIn('addon_details.addon_id',$request->AddonIds);
        //     }
        //             $addons= $addons->select('addons.name','addon_details.id as addon_details_table_id','addon_details.addon_id','addon_details.addon_code','addon_details.purchase_price','addon_details.payment_condition',
        //             'addon_details.lead_time','addon_details.additional_remarks','addon_details.image','addon_details.is_all_brands','addon_types.brand_id','addon_types.model_id','addon_types.is_all_model_lines','brands.brand_name',
        //             'master_model_lines.model_line','addon_details.status')
        //             ->orderBy('addon_details.id','ASC')
        //             ->get();
        //   $data['addons1']

        return response()->json($data);



        // $addon1 = AddonDetails::with('AddonName','AddonTypes.brands','AddonTypes.modelLines');
        // if($data != 'all')
        // {
        //     $addon1 = $addon1->where('addon_type_name',$data);
        // }
        // $addon1 = $addon1->orderBy('id', 'ASC')->get();
        // $addonMasters = Addon::select('id','name')->orderBy('name', 'ASC')->get();
        // $brandMatsers = Brand::select('id','brand_name')->orderBy('brand_name', 'ASC')->get();
        // $modelLineMasters = MasterModelLines::select('id','brand_id','model_line')->orderBy('model_line', 'ASC')->get();
        // // $addons = AddonDetails::with('AddonTypes.brands','AddonTypes.modelLines','AddonTypes.brands')
        // $addons = DB::table('addon_details')
        //             ->join('addons','addons.id','addon_details.addon_id')
        //             ->join('addon_types','addon_types.addon_details_id','addon_details.id')
        //             ->join('brands','brands.id','addon_types.brand_id')
        //             ->join('master_model_lines','master_model_lines.id','addon_types.model_id')
        //             ->select('addons.name','addon_details.id as addon_details_table_id','addon_details.addon_id','addon_details.addon_code','addon_details.purchase_price','addon_details.payment_condition',
        //             'addon_details.lead_time','addon_details.additional_remarks','addon_details.image','addon_details.is_all_brands','addon_types.brand_id','addon_types.model_id','addon_types.is_all_model_lines','brands.brand_name',
        //             'master_model_lines.model_line','addon_details.status')
        //             ->orderBy('addon_details.id','ASC')
        //             ->get();
        // return view('addon.index',compact('addons','addon1','addonMasters','brandMatsers','modelLineMasters'));
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
    public function addonView($id)
    {
        $addonDetails = AddonDetails::where('id',$id)->with('AddonTypes','AddonName','AddonSuppliers.Suppliers')->first();
        $addons = Addon::select('id','name')->get();
        $brands = Brand::select('id','brand_name')->get();
        $modelLines = MasterModelLines::select('id','brand_id','model_line')->get();
        return view('addon.show',compact('addonDetails','addons','brands','modelLines'));
    }
    public function brandModels($id)
    {
        $data = MasterModelLines::where('brand_id',$id)->select('id','model_line')->get();
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
        $supplierAddonDetails = AddonDetails::where('id',$id)->with('AddonName','AddonTypes.brands','SellingPrice','LeastPurchasePrices','AddonSuppliers.Suppliers','AddonSuppliers.Kit.addon.AddonName')->first();
        // 
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
            $oldSellingPrice->status = 'inactive';
            $oldSellingPrice->updated_by = $authId;
            $oldSellingPrice->save();
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
}
