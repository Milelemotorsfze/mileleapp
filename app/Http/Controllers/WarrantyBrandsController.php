<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WarrantyBrands;
use App\Models\WarrantyPriceHistories;
use App\Models\WarrantyPriceHistory;
use App\Models\WarrantySellingPriceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WarrantyBrandsController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'price' => 'required_without:selling_price',
            'selling_price' => 'required_without:price'
        ]);

        DB::beginTransaction();
        $warrantyBrand = WarrantyBrands::findOrFail($id);
        $message1 = '';
        $message2 = '';
        if($request->price) {
            if($warrantyBrand->price != $request->price) {

                $warrantyPriceHistory = new WarrantyPriceHistory();
                $warrantyPriceHistory->warranty_brand_id = $id;
                $warrantyPriceHistory->old_price = $warrantyBrand->price;
                $warrantyPriceHistory->updated_price = $request->price;
                $warrantyPriceHistory->updated_by = Auth::id();
                $warrantyPriceHistory->save();

                $warrantyBrand->price = $request->price;
                $warrantyBrand->updated_by = Auth::id();
                $warrantyBrand->save();
            }
            $message1 = 'Warranty Price Updated Successfully.';
        }
       if($request->selling_price)
       {
           if($warrantyBrand->selling_price != $request->selling_price)
           {
               $warrantySellingPriceHistory = new WarrantySellingPriceHistory();
               $warrantySellingPriceHistory->warranty_brand_id = $id;
               $warrantySellingPriceHistory->old_price = $warrantyBrand->selling_price ?? '';
               $warrantySellingPriceHistory->updated_price = $request->selling_price;
               $warrantySellingPriceHistory->created_by = Auth::id();
               $warrantySellingPriceHistory->status_updated_by = Auth::id();
               $warrantySellingPriceHistory->status = 'pending';
               $warrantySellingPriceHistory->save();
           }
           $message2 = ' Selling Price send for Approval';

       }

        DB::commit();

        return redirect()->route('warranty.show',  $warrantyBrand->warranty_premiums_id)->with('success',$message1.$message2);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $warrantyBrand = WarrantyBrands::findOrFail($id);
        $warrantyBrand->delete();

        return response(true);
    }
    public function updateSellingPrice(Request $request)
    {
        $warrantyPriceHistory = WarrantySellingPriceHistory::find($request->id);
        $status = $request->status;

        if($status == 'approved')
        {
           $warrantyBrand = WarrantyBrands::find($warrantyPriceHistory->warranty_brand_id);
           $warrantyBrand->selling_price = $request->updated_price;
           $warrantyBrand->save();

        }
        $warrantyPriceHistory->status = $request->status;
        $warrantyPriceHistory->status_updated_by = Auth::id();
        $warrantyPriceHistory->save();

        return response(true);
    }
}
