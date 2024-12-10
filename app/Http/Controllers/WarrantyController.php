<?php

namespace App\Http\Controllers;

use App\Models\BrandRegion;
use App\Models\Supplier;
use App\Models\WarrantyPriceHistory;
use App\Models\WarrantySellingPriceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterWarrantyPolicies;
use App\Models\Brand;
use App\Models\WarrantyPremiums;
use App\Models\WarrantyBrands;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UserActivityController;
class WarrantyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $premiums = WarrantyPremiums::with('PolicyName')
                    ->orderBy('id','desc')->get();
        return view('warranty.index', compact('premiums'));
    }
    public function list() {
        $warrantyBrands = WarrantyBrands::with('brand','country','premium')
            ->orderBy('id','desc')->get();
        return view('warranty.list', compact('warrantyBrands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $policyNames = MasterWarrantyPolicies::select('id','name')->get();
        $brands = Brand::select('id','brand_name')->get();
        $brandRegions = BrandRegion::select('id','name')->get();
        $suppliers = Supplier::with('supplierTypes')
            ->whereHas('supplierTypes', function ($query) {
                $query->where('supplier_type', Supplier::SUPPLIER_TYPE_WARRANTY);
            })
            ->get();
        return view('warranty.create', compact('policyNames','brands','suppliers','brandRegions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $authId = Auth::id();
        $input = $request->all();
        $input['created_by'] = $authId;
        $input['extended_warranty_milage'] =  $input['is_open_milage'] == 'no' ? $input['extended_warranty_milage'] : NULL;
        $premium = WarrantyPremiums::create($input);
        if($request->brandPrice) {
            $inputbrandPrice['created_by'] = $authId;
            $inputbrandPrice['warranty_premiums_id'] = $premium->id;
            if(count($request->brandPrice) > 0) {
                foreach($request->brandPrice as $brandPrice) {
                    $inputbrandPrice['price'] = $brandPrice['purchase_price'];
                    $inputbrandPrice['selling_price'] = $brandPrice['selling_price'];
                    $inputbrandPrice['brand_region_id'] = $brandPrice['country'];
                    if(isset($brandPrice['brands'])) {
                        if(count($brandPrice['brands']) > 0) {
                            foreach($brandPrice['brands'] as $brandData) {
                                $inputbrandPrice['brand_id'] = $brandData;
                                $inputbrandPrice['is_selling_price_approved'] = '0';
                                $createBrandPrice = WarrantyBrands::create($inputbrandPrice);

                                $priceHistory = new WarrantyPriceHistory();
                                $priceHistory->warranty_brand_id  = $createBrandPrice->id;
                                $priceHistory->updated_price = $brandPrice['purchase_price'];
                                $priceHistory->created_by = Auth::id();
                                $priceHistory->updated_by = Auth::id();
                                $priceHistory->save();
                                if($brandPrice['selling_price']) {
                                    $sellingPriceHistory = new WarrantySellingPriceHistory();
                                    $sellingPriceHistory->warranty_brand_id = $createBrandPrice->id;
                                    $sellingPriceHistory->updated_price = $brandPrice['selling_price'];
                                    $sellingPriceHistory->updated_by = Auth::id();
                                    $sellingPriceHistory->created_by = Auth::id();
                                    $sellingPriceHistory->status = 'pending';
                                    $sellingPriceHistory->save();
                                }
                            }
                        }
                    }
                }
            }
        }
        (new UserActivityController)->createActivity('Warranty Created');
        return redirect()->route('warranty.index')->with('success','Warranty created successfully');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $premium = WarrantyPremiums::findOrFail($id);
        $warrantyBrands = WarrantyBrands::where('warranty_premiums_id',$id)->get();
        return view('warranty.show', compact('premium','warrantyBrands'));
    }
    public function view()
    {
        $warrantyBrands = WarrantyBrands::groupBy('warranty_premiums_id','selling_price')->get();
        return view('warranty.sales_view.show', compact('warrantyBrands'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $premium = WarrantyPremiums::where('id',$id)->with('PolicyName')->first();
        $existingBrands =  WarrantyBrands::where('warranty_premiums_id',$id)->groupBy(['price','selling_price','is_selling_price_approved'])->get();
        foreach($existingBrands as $existingBrand)
        {
            $vars = [];
            $vars = WarrantyBrands::where('warranty_premiums_id',$id)->where('price',$existingBrand->price)->where('selling_price',$existingBrand->selling_price)
            ->where('is_selling_price_approved',$existingBrand->is_selling_price_approved)->pluck('brand_id');
            $existingBrand->brand = Brand::whereIn('id',$vars)->select('id','brand_name')->get();
        }
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
        $isOpenMilage = $premium->is_open_milage;
        $brandRegions = BrandRegion::select('id','name')->get();
        return view('warranty.edit', compact('premium','brands','policyNames','suppliers','warrantyBrands',
            'alreadyAddedBrands','isOpenMilage','existingBrands','brandRegions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
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
        $NotNelete = [];
        if($request->brandPrice)
        {
            $inputbrandPrice['updated_by'] = Auth::id();
            $inputbrandPrice['warranty_premiums_id'] = $id;
            if(count($request->brandPrice) > 0)
            {
                $existingBrands = [];
                $existingBrands2 = WarrantyBrands::where('warranty_premiums_id',$id)->select('brand_id')->get();
                foreach( $existingBrands2 as $existingBrands1)
                {
                    array_push($existingBrands,$existingBrands1->brand_id);
                }
                foreach($request->brandPrice as $brandPrice)
                {
                    if(isset($brandPrice['brands']))
                    {
                        if(count($brandPrice['brands']) > 0)
                        {
                            foreach($brandPrice['brands'] as $brandData)
                            {
                                array_push($NotNelete,$brandData);
                                if(in_array($brandData, $existingBrands))
                                {
                                    $update =  WarrantyBrands::where('brand_id',$brandData)->where('warranty_premiums_id',$id)->first();
                                    $oldPrice = $update->price;
                                    $oldSellingPrice = $update->selling_price;
                                    $update->updated_by = Auth::id();
                                    $update->brand_id = $brandData;
                                    $update->price =  $brandPrice['purchase_price'];
                                    $update->brand_region_id =  $brandPrice['country'];
                                    if($brandPrice['selling_price'] != '')
                                    {
                                        $update->selling_price =  $brandPrice['selling_price'];
                                    }
                                    $update->update();

                                    if($oldPrice != $update->price)
                                    {
                                        $priceHistory = new WarrantyPriceHistory();
                                        $priceHistory->warranty_brand_id  = $update->id;
                                        $priceHistory->updated_price = $brandPrice['purchase_price'];
                                        $priceHistory->created_by = Auth::id();
                                        $priceHistory->updated_by = Auth::id();
                                        $priceHistory->save();
                                    }

                                    if($brandPrice['selling_price']) {
                                        if($oldSellingPrice != $update->selling_price)
                                        {
                                            $sellingPriceHistory = new WarrantySellingPriceHistory();
                                            $sellingPriceHistory->warranty_brand_id = $update->id;
                                            $sellingPriceHistory->updated_price = $brandPrice['selling_price'];
                                            $sellingPriceHistory->updated_by = Auth::id();
                                            $sellingPriceHistory->created_by = Auth::id();
                                            $sellingPriceHistory->status = 'pending';
                                            $sellingPriceHistory->save();
                                        }
                                    }
                                }
                                else
                                {
                                    $inputbrandPrice['price'] = $brandPrice['purchase_price'];
                                    $inputbrandPrice['selling_price'] = $brandPrice['selling_price'];
                                    $inputbrandPrice['brand_id'] = $brandData;
                                    $inputbrandPrice['brand_region_id'] =  $brandPrice['country'];

                                    $createBrandPrice = WarrantyBrands::create($inputbrandPrice);

                                    $priceHistory = new WarrantyPriceHistory();
                                    $priceHistory->warranty_brand_id  = $createBrandPrice->id;
                                    $priceHistory->updated_price = $brandPrice['purchase_price'];
                                    $priceHistory->created_by = Auth::id();
                                    $priceHistory->updated_by = Auth::id();
                                    $priceHistory->save();

                                    if($brandPrice['selling_price']) {
                                        $sellingPriceHistory = new WarrantySellingPriceHistory();
                                        $sellingPriceHistory->warranty_brand_id = $createBrandPrice->id;
                                        $sellingPriceHistory->updated_price = $brandPrice['selling_price'];
                                        $sellingPriceHistory->updated_by = Auth::id();
                                        $sellingPriceHistory->created_by = Auth::id();
                                        $sellingPriceHistory->status = 'pending';
                                        $sellingPriceHistory->save();
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $newExiBrands2 = [];
            $newExiBrands = WarrantyBrands::where('warranty_premiums_id',$id)->pluck('brand_id');
            foreach($newExiBrands as $newExiBrands1)
            {
                array_push($newExiBrands2,$newExiBrands1);
            }
            $differenceArray = array_diff($newExiBrands2, $NotNelete);
            $delete = WarrantyBrands::whereIn('brand_id',$differenceArray)->where('warranty_premiums_id',$id)->get();
            foreach($delete as $del)
            {
                $deletehistory = WarrantyPriceHistory::where('warranty_brand_id',$del->id)->get();
                foreach($deletehistory as $deletehistory1)
                {
                    $deletehistory1->delete();
                }
                $delSelinghisry = WarrantySellingPriceHistory::where('warranty_brand_id',$del->id)->get();
                foreach($delSelinghisry as $delSelinghisry1)
                {
                    $delSelinghisry1->delete();
                }
                $del = $del->delete();
            }
        }
        (new UserActivityController)->createActivity('Warranty Updated');
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
            (new UserActivityController)->createActivity('Warranty Deleted');
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
        // dynamic field removal for warranty brands

        // if($request->id) {
        //     $id = $request->id;
        //     $alreadyAddedBrandIds = WarrantyBrands::where('warranty_premiums_id',$id)->pluck('brand_id');
        //     $data = $data->whereNotIn('id', $alreadyAddedBrandIds);
        // }
        $data = $data->get();
        return response()->json($data);
    }

    public function getBrandForWarranty(Request $request) {
        $existingWarrantyBrands = WarrantyBrands::with('premium')
            ->whereHas('premium', function ($query) use($request){
                $query->where('warranty_policies_id',$request->warranty_policies_id)
                    ->where('vehicle_category1',$request->vehicle_category1)
                    ->where('vehicle_category2',$request->vehicle_category2)
                    ->where('supplier_id',$request->supplier_id);
                })
            ->where('brand_region_id', $request->brand_region_id)
            ->pluck('brand_id');

        $availableWarrantyBrands = Brand::whereNotIn('id',$existingWarrantyBrands);
        if($request->selectedBrands) {
            $availableWarrantyBrands = $availableWarrantyBrands->whereNotIn('id',$request->selectedBrands);
        }
        $availableWarrantyBrands = $availableWarrantyBrands->get();
        return response($availableWarrantyBrands);

    }
    public function statusChange(Request $request)
    {
        $warranty = WarrantyPremiums::find($request->id);
        $warranty->status = $request->status;

        $warranty->save();
        return response($warranty, 200);
    }

}
