<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WarrantyPriceHistory;
use App\Models\WarrantySellingPriceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarrantyPriceHistoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $priceHistories = WarrantyPriceHistory::where('warranty_brand_id', $request->id)->get();


        return view('warranty.price_histories.purchase_price.index', compact('priceHistories'));
    }
    public function listSellingPrices(Request $request)
    {
        $pendingSellingPriceHistories = WarrantySellingPriceHistory::where('warranty_brand_id', $request->id)
            ->where('status', 'pending')->get();
        $approvedSellingPriceHistories = WarrantySellingPriceHistory::where('warranty_brand_id', $request->id)
            ->where('status', 'approved')->get();
        $rejectedSellingPriceHistories = WarrantySellingPriceHistory::where('warranty_brand_id', $request->id)
            ->where('status', 'rejected')->get();

        return view('warranty.price_histories.selling_price.index', compact('pendingSellingPriceHistories',
            'approvedSellingPriceHistories','rejectedSellingPriceHistories'));
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
        $request->validate([
            'selling_price' => 'required'
        ]);

        $warrantyPriceHistory = WarrantySellingPriceHistory::findOrFail($id);
        $warrantyPriceHistory->updated_price = $request->selling_price;
        $warrantyPriceHistory->updated_by = Auth::id();
        $warrantyPriceHistory->save();

        return redirect()->back()->with('success','Selling Price Updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
