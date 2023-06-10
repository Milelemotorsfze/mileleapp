<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\MasterModelLines;
use App\Models\Varaint;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $variants = Varaint::orderBy('id','DESC')->get();
        return view('variants.list', compact('variants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::all();
        $masterModelLines = MasterModelLines::all();

        return view('variants.create', compact('masterModelLines','brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'string|required|max:255',
            'brands_id' => 'required',
            'master_model_lines_id' => 'required',

        ]);

        $variant = new Varaint();
        $variant->name  = $request->input('name');
        $variant->brands_id  = $request->input('brands_id');
        $variant->master_model_lines_id = $request->input('master_model_lines_id');
        $variant->fuel_type = $request->input('fuel_type');
        $variant->gearbox = $request->input('gearbox');
        $variant->my = $request->input('my');
        $variant->detail = $request->input('detail');
        $variant->seat = $request->input('seat');
        $variant->upholestry = $request->input('upholestry');
        $variant->save();

        return redirect()->route('variants.index')->with('success','Variant added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $variant = Varaint::findOrFail($id);

        return view('variants.show',compact('variant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $brands = Brand::all();
        $masterModelLines = MasterModelLines::all();

        $variant = Varaint::findOrFail($id);
        return view('variants.edit',compact('variant','brands','masterModelLines'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'string|required|max:255',
            'brands_id' => 'required',
            'master_model_lines_id' => 'required',

        ]);

        $variant = Varaint::findOrFail($id);
        $variant->name  = $request->input('name');
        $variant->brands_id  = $request->input('brands_id');
        $variant->master_model_lines_id = $request->input('master_model_lines_id');
        $variant->fuel_type = $request->input('fuel_type');
        $variant->gearbox = $request->input('gearbox');
        $variant->my = $request->input('my');
        $variant->detail = $request->input('detail');
        $variant->seat = $request->input('seat');
        $variant->upholestry = $request->input('upholestry');
        $variant->save();

        return redirect()->route('variants.index')->with('success','Variant updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $variant = Varaint::findOrFail($id);
        $variant->delete();

        return response(true);
    }
}
