<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::orderBy('id','DESC')->get();
        return view('brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'brand_name' => 'required',
        ]);

        $brand = new Brand();
        $brand->brand_name = $request->brand_name;
        $brand->created_by = Auth::id();
        $brand->save();

        return redirect()->route('brands.index')->with('success','Brand Created Successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        $brand = Brand::findOrFail($brand->id);

        return view('brands.edit',compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $this->validate($request, [
            'brand_name' => 'required',
        ]);

        $brand = Brand::findOrFail($brand->id);
        $brand->brand_name = $request->brand_name;
        $brand->updated_by = Auth::id();
        $brand->save();

        return redirect()->route('brands.index')->with('success','Brand Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        //
    }
}
