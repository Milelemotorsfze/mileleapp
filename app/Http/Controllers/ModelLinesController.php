<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\MasterModelLines;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModelLinesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modelLines = MasterModelLines::orderBy('id','DESC')->get();
        return view('model-lines.index', compact('modelLines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::all();
        return view('model-lines.create',compact('brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'model_line' => 'required|unique:master_model_lines,model_line',
            'brand_id' => 'required'

        ]);

        $modelLine = new MasterModelLines();
        $modelLine->brand_id = $request->brand_id;
        $modelLine->model_line = $request->model_line;
        $modelLine->created_by = Auth::id();
        $modelLine->save();

        return redirect()->route('model-lines.index')->with('success','Model Line Created Successfully.');
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
        $modelLine = MasterModelLines::findOrFail($id);
        $brands = Brand::all();

        return view('model-lines.edit', compact('modelLine','brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'model_line' => 'required|unique:master_model_lines,model_line,'.$id,
            'brand_id' => 'required'
        ]);

        $modelLine = MasterModelLines::findOrFail($id);
        $modelLine->brand_id = $request->brand_id;
        $modelLine->model_line = $request->model_line;
        $modelLine->updated_by = Auth::id();
        $modelLine->save();

        return redirect()->route('model-lines.index')->with('success','Model Line Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
