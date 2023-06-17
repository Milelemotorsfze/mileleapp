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
        return redirect()->route('warranty.index')->with('success','Warranty created successfully');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $premium = WarrantyPremiums::findOrFail($id);
        $warrantBrands = WarrantyBrands::where('warranty_premiums_id',$id)->get();
        return view('warranty.show', compact('premium','warrantBrands'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $premium = WarrantyPremiums::where('id',$id)->with('PolicyName')->first();
        $alreadyAddedBrandIds = WarrantyBrands::where('warranty_premiums_id',$id)->pluck('brand_id');
        $warrantyBrands = WarrantyBrands::where('warranty_premiums_id',$id)->get();
        $brands = Brand::select('id','brand_name')
                ->whereNotIn('id',$alreadyAddedBrandIds)->get();
        $policyNames = MasterWarrantyPolicies::select('id','name')->get();
        $suppliers = Supplier::with('supplierTypes')
            ->whereHas('supplierTypes', function ($query) {
                $query->where('supplier_type', Supplier::SUPPLIER_TYPE_WARRANTY);
            })
            ->get();
        $alreadyAddedBrands = Brand::whereIn('id', $alreadyAddedBrandIds)->get();
//        $alreadyAddedBrandsList = [];

        return view('warranty.edit', compact('premium','brands','policyNames','suppliers','warrantyBrands','alreadyAddedBrands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
//        dd($request->all());
        $this->validate($request, [
            'warranty_policies_id' => 'required',
            'supplier_id' => 'required',
            'vehicle_category1' => 'required',
            'vehicle_category2' => 'required',
            'eligibility_year' => 'required',
            'eligibility_milage' => 'required',
            'extended_warranty_period' => 'required',
            'claim_limit_in_aed' => 'required'
        ]);

        $input = $request->all();
        $input['updated_by'] = Auth::id();
        $premium = WarrantyPremiums::findorFail($id);
        $premium->update($input);
        if($request->brandPrice)
        {
            $inputbrandPrice['created_by'] = Auth::id();
            $inputbrandPrice['warranty_premiums_id'] = $id;
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
        return redirect()->route('warranty.index')->with('success','Warranty updated successfully');
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
    public function statusChange(Request $request)
    {
        $warranty = WarrantyPremiums::find($request->id);
        $warranty->status = $request->status;

        $warranty->save();
        return response($warranty, 200);
    }

}
