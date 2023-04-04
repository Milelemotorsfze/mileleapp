<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Addon;
use App\Models\Brand;
use App\Models\MasterModel;
use App\Models\AddonDetails;
use App\Models\AddonTypes;
use DB;

class AddonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $addons = DB::table('addon_details')
                    ->join('addon_types','addon_types.addon_details_id','addon_details.id')
                    ->select('addon_details.id','addon_details.addon_id','addon_details.addon_code','addon_details.purchase_price','addon_details.selling_price','addon_details.currency',
                    'addon_details.lead_time','addon_details.additional_remarks','addon_details.image','addon_types.brand_id','addon_types.model_id')
                    ->orderBy('addon_details.id','ASC')
                    ->get();
        return view('addon.index',compact('addons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $addons = Addon::select('id','name')->get();
        $brands = Brand::select('id','brand_name')->get();
        $models = MasterModel::select('model')->get();
        return view('addon.create',compact('addons','brands','models'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
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
            'image' => 'required|max:2048',
        ]);
        // |mimes:csv,txt,xlx,xls,pdf
      
        $fileName = auth()->id() . '_' . time() . '.'. $request->image->extension();  

        $type = $request->image->getClientMimeType();
        $size = $request->image->getSize();

        $request->image->move(public_path('addon_image'), $fileName);
        $input = $request->all();
        $input['currency'] = 'AED';
        $input['created_by'] = $authId;
        $input['image'] = $fileName;
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
        //
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
}
