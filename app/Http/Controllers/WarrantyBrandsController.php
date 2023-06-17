<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WarrantyBrands;
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
            'price' => 'required',
        ]);
        $warrantBrand = WarrantyBrands::findOrFail($id);
        $warrantBrand->price = $request->price;
        $warrantBrand->updated_by = Auth::id();
        $warrantBrand->save();

        return redirect()->route('warranty.show',  $warrantBrand->warranty_premiums_id)->with('success','Warranty updated successfully');
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
}
