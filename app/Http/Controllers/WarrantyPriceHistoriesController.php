<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WarrantyPriceHistory;
use App\Models\WarrantySellingPriceHistory;
use Illuminate\Http\Request;

class WarrantyPriceHistoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $priceHistories = WarrantyPriceHistory::where('warranty_brand_id', $request->id)->get();
        $pendingSellingPriceHistories = WarrantySellingPriceHistory::where('warranty_brand_id', $request->id)
                                            ->where('status', 'pending')->get();
        $approvedSellingPriceHistories = WarrantySellingPriceHistory::where('warranty_brand_id', $request->id)
                                            ->where('status', 'approve')->get();
        $rejectedSellingPriceHistories = WarrantySellingPriceHistory::where('warranty_brand_id', $request->id)
                                            ->where('status', 'reject')->get();

        return view('warranty.price_histories.index', compact('priceHistories','pendingSellingPriceHistories',
        'approvedSellingPriceHistories','rejectedSellingPriceHistories'));
    }
    public function approveSellingPrice() {

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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
