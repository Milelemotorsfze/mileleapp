<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterWarrantyPolicies;
use App\Models\Brand;
use App\Models\WarrantyPremiums;
use App\Models\WarrantyBrands;
use Illuminate\Support\Facades\DB;

class WarrantyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $premiums = WarrantyPremiums::with('PolicyName')->get();
        return view('warranty.index', compact('premiums'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $policyNames = MasterWarrantyPolicies::select('id','name')->get();
        $brands = Brand::select('id','brand_name')->get();
        $suppliers = Supplier::with('supplierTypes')
            ->whereHas('supplierTypes', function ($query) {
                $query->where('supplier_type', Supplier::SUPPLIER_TYPE_WARRANTY);
            })
            ->get();
        return view('warranty.create', compact('policyNames','brands','suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//         dd($request->all());
        $authId = Auth::id();
        $input = $request->all();
        $input['created_by'] = $authId;
        $premium = WarrantyPremiums::create($input);
        if($request->brandPrice)
        {
            $inputbrandPrice['created_by'] = $authId;
            $inputbrandPrice['warranty_premiums_id'] = $premium->id;
            if(count($request->brandPrice) > 0)
            {
                foreach($request->brandPrice as $brandPrice)
                {
                    $inputbrandPrice['price'] = $brandPrice['purchase_price'];
                    if(isset($brandPrice['brands']))
                    {
                        if(count($brandPrice['brands']) > 0)
                        {
                            foreach($brandPrice['brands'] as $brandData)
                            {
                                $inputbrandPrice['brand_id'] = $brandData;
                                $createBrandPrice = WarrantyBrands::create($inputbrandPrice);
                            }
                        }
                    }
                }
            }
        }
        return redirect()->route('warranty.index')->with('success','Addon created successfully');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $premium = WarrantyPremiums::where('id',$id)->with('PolicyName')->first();
        $brandPrice = WarrantyBrands::where('warranty_premiums_id',$id)->groupBy('price')->get();
        $brands = Brand::select('id','brand_name')->get();
        $policyNames = MasterWarrantyPolicies::select('id','name')->get();
        return view('warranty.edit', compact('premium','brands','policyNames'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $warranty = WarrantyPremiums::findOrFail($id);
        DB::beginTransaction();
            WarrantyBrands::where('warranty_premiums_id', $id)->delete();
            $warranty->delete();
        DB::commit();

        return response(true);
    }
    public function getBranchForWarranty(Request $request)
    {
        $data = Brand::select('id','brand_name');
        if($request->filteredArray)
        {
            if(count($request->filteredArray) > 0)
            {
                $data = $data->whereNotIn('id',$request->filteredArray);
            }
        }
        $data = $data->get();
        return response()->json($data);
    }
    public function updateWarranty(Request $request)
    {
        dd($request->all());
    }
}
