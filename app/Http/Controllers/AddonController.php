<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Addon;
use App\Models\Brand;
use App\Models\MasterModelLines;
use App\Models\AddonDetails;
use App\Models\AddonTypes;
use DB;
use Validator;


class AddonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $addonMasters = Addon::select('id','name')->get();
        $brandMatsers = Brand::select('id','brand_name')->get();
        $modelLineMasters = MasterModelLines::select('id','brand_id','model_line')->get();
        $addon1 = AddonDetails::with('AddonName','AddonTypes')->orderBy('id', 'DESC')->get();
        $addons = DB::table('addon_details')
                    ->join('addons','addons.id','addon_details.addon_id')
                    ->join('addon_types','addon_types.addon_details_id','addon_details.id')
                    ->select('addons.name','addon_details.id as addon_details_table_id','addon_details.addon_id','addon_details.addon_code','addon_details.purchase_price','addon_details.selling_price','addon_details.currency',
                    'addon_details.lead_time','addon_details.additional_remarks','addon_details.image','addon_types.brand_id','addon_types.model_id')
                    ->orderBy('addon_details.id','DESC')
                    ->get();
                   
        return view('addon.index',compact('addons','addon1','addonMasters','brandMatsers','modelLineMasters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $lastAddonCode = AddonDetails::orderBy('id', 'desc')->first()->addon_code;
      
       
        $addons = Addon::select('id','name')->get();
        $brands = Brand::select('id','brand_name')->get();
        $modelLines = MasterModelLines::select('id','brand_id','model_line')->get();
        return view('addon.create',compact('addons','brands','modelLines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    //    dd($request->all());
        $authId = Auth::id();
        // $this->validate($request, [
        //     'addon_id' => 'required',
        //     'addon_code' => 'required',
        //     'purchase_price' => 'required',
        //     'selling_price' => 'required',
        //     'lead_time' => 'required',
        //     'additional_remarks' => 'required',
        //     'brand' => 'required',
        //     'model' => 'required',
        //     'image' => 'required|max:2048',
        // ]);



        $validator = Validator::make($request->all(), [
            'addon_id' => 'required',
            // 'addon_code' => 'required',
            'purchase_price' => 'required',
            'selling_price' => 'required',
            'lead_time' => 'required',
            'additional_remarks' => 'required',
            'brand' => 'required',
            'model' => 'required',
            'image' => 'required|max:2048',
            // |mimes:jpeg,png,jpg,gif,svg|max:2048',
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







            // $image = $request->image;

            // $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
            // dd($image);
            // $destinationPath = public_path('/thumbnail');
            // $img = Image::make($image->getRealPath());
            
            // $img->resize(100, 100, function ($constraint) {
            //     $constraint->aspectRatio();
            // })->save($destinationPath.'/'.$input['imagename']);
       
            // $destinationPath = public_path('/images');
            // $image->move($destinationPath, $input['imagename']);
       
            // $this->postImage->add($input);
















           

            //output: P00001
            $input = $request->all();
            
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
                // dd($request->brand[2]);
                $inputaddontype['brand_id'] = $request->brand[$i];
                $inputaddontype['model_id'] = $request->model[$i];
                $addon_types = AddonTypes::create($inputaddontype);
            }
            return redirect()->route('addon.index')
                            ->with('success','Addon created successfully');
        }
    //    dd($validator->errors);
    //     // |mimes:csv,txt,xlx,xls,pdf
    //     if ($validator->fails()) {
    //         return redirect(route('addon.create'))->withInput()->withErrors($validator);
    //     } 
       
    }

    /**
     * Display the specified resource.
     */
    public function show(Addon $addon)
    {
        //
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
        // dd($addonDetails);
        $addons = Addon::select('id','name')->get();
        $brands = Brand::select('id','brand_name')->get();
        $modelLines = MasterModelLines::select('id','brand_id','model_line')->get();
        return view('addon.edit',compact('addons','brands','modelLines','addonDetails'));
    }
    public function updateAddonDetails(Request $request, $id)
    {
        // dd($request->image);
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
    // if($request->image)
        $input = $request->all();
        // if(!empty($input['password'])){ 
        //     $input['password'] = Hash::make($input['password']);
        // }else{
        //     $input = Arr::except($input,array('password'));    
        // }
        
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
            // dd($request->brand[2]);
            
        }
        // DB::table('model_has_roles')->where('model_id',$id)->delete();
    
        // $addon->assignRole($request->input('roles'));
    
        return redirect()->route('addon.index')
                        ->with('success','addon updated successfully');
    }
    public function existingImage($id)
    {
        $existingImages = DB::table('addon_details')
                    ->where('addon_details.addon_id',$id)
                    ->join('addons','addons.id','addon_details.addon_id')
                    ->join('addon_types','addon_types.addon_details_id','addon_details.id')
                    ->select('addons.name','addon_details.id','addon_details.addon_id','addon_details.addon_code','addon_details.purchase_price','addon_details.selling_price','addon_details.currency',
                    'addon_details.lead_time','addon_details.additional_remarks','addon_details.image','addon_types.brand_id','addon_types.model_id')
                    ->orderBy('addon_details.id','ASC')
                    ->get();
        return $existingImages;
    }
    public function addonFilters(Request $request) 
    {
        $oldValue = $request->oldValue;
        $addonIds = AddonDetails::with('AddonTypes')->whereHas('AddonTypes', function($q) use($request) {
            if($request->BrandIds)
            {
            $q->whereNotIn('brand_id',$request->BrandIds)->get();
            }
            if($request->BrandIds)
            {
            $q->whereNotIn('brand_id',$request->BrandIds)->get();
            }
        });
        if($request->AddonIds)
        {
            $addonIds = $addonIds->whereNotIn('addon_id',$request->AddonIds);
        }
        $addonIds = $addonIds->pluck('id');
        $data['addonIds'] = $addonIds;
        // dd($oldValue);
        $data['oldValue'] =  $oldValue;
        return response()->json($data);
    }
}
