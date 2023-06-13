<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterWarrantyPolicies;
use App\Models\Brand;
class WarrantyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('warranty.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $policyNames = MasterWarrantyPolicies::select('id','name')->get();
        $brands = Brand::select('id','brand_name')->get();
        return view('warranty.create', compact('policyNames','brands'));
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
