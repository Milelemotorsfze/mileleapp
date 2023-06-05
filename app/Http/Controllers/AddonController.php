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

        $addon1 = AddonDetails::with('AddonName','AddonTypes.brands','AddonTypes.modelLines');
        if($data != 'all')
        {
            $addon1 = $addon1->where('addon_type_name',$data);
        }
        $addon1 = $addon1->orderBy('id', 'ASC')->get();
        $addonMasters = Addon::select('id','name')->orderBy('name', 'ASC')->get();
        $brandMatsers = Brand::select('id','brand_name')->orderBy('brand_name', 'ASC')->get();
        $modelLineMasters = MasterModelLines::select('id','brand_id','model_line')->orderBy('model_line', 'ASC')->get();
        // $addons = AddonDetails::with('AddonTypes.brands','AddonTypes.modelLines','AddonTypes.brands')
        $addons = DB::table('addon_details')
                    ->join('addons','addons.id','addon_details.addon_id')
                    ->join('addon_types','addon_types.addon_details_id','addon_details.id')
                    ->join('brands','brands.id','addon_types.brand_id')
                    ->join('master_model_lines','master_model_lines.id','addon_types.model_id')
                    ->select('addons.name','addon_details.id as addon_details_table_id','addon_details.addon_id','addon_details.addon_code','addon_details.purchase_price','addon_details.selling_price','addon_details.payment_condition','addon_details.currency',
                    'addon_details.lead_time','addon_details.additional_remarks','addon_details.image','addon_details.is_all_brands','addon_types.brand_id','addon_types.model_id','addon_types.is_all_model_lines','brands.brand_name',
                    'master_model_lines.model_line','addon_details.status')
                    ->orderBy('addon_details.id','ASC')
                    ->get();        
        return view('addon.index',compact('addons','addon1','addonMasters','brandMatsers','modelLineMasters','data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kitItemDropdown = Addon::whereIn('addon_type',['P','SP'])->pluck('id');
        $kitItemDropdown = AddonDetails::whereIn('addon_id', $kitItemDropdown)->select('id','addon_code')->get();
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
        // "selling_price" => "124"
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
        $validator = Validator::make($request->all(), [
            // 'addon_id' => 'required',
            // 'addon_code' => 'required',
            // 'purchase_price' => 'required',
            // 'selling_price' => 'required',
            // 'lead_time' => 'required',
            // 'additional_remarks' => 'required',
            // 'brand' => 'required',
            // 'model' => 'required',
            // 'image' => 'nullable|image|mimes:svg,jpeg,png,jpg,gif,bmp,tiff,jpe',
            //|max:2048',
            // nullable|image|max:1000
            // mimes:jpeg,png,jpg,gif
            // 'mimes:jpeg,bmp,png'
            // mimes:jpg,jpeg,png,bmp,tiff 
            // max:4096'
            // Use mimetypes: rule with image/jpeg that covers 3 extension variations for the jpeg format: jpg jpeg jpe.

// Use image rule which covers jpeg, png, bmp, gif, or svg including jpeg's extension variations
        ]);
        
        if ($validator->fails()) 
        {
            return redirect(route('addon.create'))->withInput()->withErrors($validator);
        }
         else 
        {
            $fileName = auth()->id() . '_' . time() . '.'. $request->image->extension();  
            $type = $request->image->getClientMimeType();
            $size = $request->image->getSize();
            $request->image->move(public_path('addon_image'), $fileName);
            $input = $request->all();
            $input['addon_id'] = $request->addon_name;
            $input['currency'] = 'AED';
            $input['created_by'] = $authId;
            $input['image'] = $fileName;
            $lastAddonCode = AddonDetails::orderBy('id', 'desc')->first()->addon_code;
            $lastAddonCodeNumber = substr($lastAddonCode, 1, 5);
            $newAddonCodeNumber =  $lastAddonCodeNumber+1;
            $newAddonCode = "P".$newAddonCodeNumber;
            $input['addon_code'] = $newAddonCode;
            $addon_details = AddonDetails::create($input);
            $inputaddontype['addon_details_id'] = $addon_details->id;
            $inputaddontype['created_by'] = $authId;
            for($i=0; $i<count($request->brand); $i++)
            {
                $inputaddontype['brand_id'] = $request->brand[$i];
                $inputaddontype['model_id'] = $request->model[$i];
                $addon_types = AddonTypes::create($inputaddontype);
            }
            if($request->addon_type == 'K')
            {

            }
            else
            {

            }
            return redirect()->route('addon.index')
                            ->with('success','Addon created successfully');
        }
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
        
        return view('addon.edit',compact('addons','brands','modelLines','addonDetails'));
    }
    public function updateAddonDetails(Request $request, $id)
    {
        $authId = Auth::id();
        $this->validate($request, [
            'addon_id' => 'required',
            'addon_code' => 'required',
            'purchase_price' => 'required',
            'selling_price' => 'required',
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
       
        $data['relatedAddons'] = DB::table('addon_details')
                    ->where('addon_details.addon_id',$id)
                    ->join('addons','addons.id','addon_details.addon_id')
                    ->join('addon_types','addon_types.addon_details_id','addon_details.id')
                    ->select('addons.name','addon_details.id','addon_details.addon_id','addon_details.addon_code','addon_details.purchase_price','addon_details.selling_price','addon_details.currency',
                    'addon_details.lead_time','addon_details.additional_remarks','addon_details.image','addon_types.brand_id','addon_types.model_id')
                    ->orderBy('addon_details.id','ASC')
                    ->get();
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
            $q->whereIn('brand_id',$request->BrandIds);
            }
            if($request->ModelLineIds)
            {
            $q->whereIn('model_id',$request->ModelLineIds);
            }
        });
        if($request->AddonIds)
        {
            $addonIds = $addonIds->whereIn('addon_id',$request->AddonIds);
        }
        if($request->Data)
        {
            $addonIds = $addonIds->where('addon_type_name',$request->Data);
        }
        // $addonIds = $addonIds->pluck('id');
        $data['addons'] = $addonIds->pluck('id');
        // dd($data);
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
        //             ->select('addons.name','addon_details.id as addon_details_table_id','addon_details.addon_id','addon_details.addon_code','addon_details.purchase_price','addon_details.selling_price','addon_details.payment_condition','addon_details.currency',
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
                    $lastAddonCodeNumber = substr($lastAddonCodeNo, 1, 5);
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
}
