<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\MasterModelLines;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserActivityController;
use Illuminate\Support\Facades\Validator;

class ModelLinesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modelLines = MasterModelLines::orderBy('id','DESC')->get();
        (new UserActivityController)->createActivity('Open Master Model Lines');
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
        $validator = Validator::make($request->all(), [

            'model_line' => 'required|unique:master_model_lines,model_line',
            'brand_id' => 'required'
            ],
            [
                'brand_id.required' => 'Please Choose any Brand!'
            ]);
        if ($validator->fails() ) {

            if($request->request_from == 'Quotation') {
                info("inside condition");
                $data['error'] = $validator->messages()->first();
                return response($data);
            }else{
                return redirect()->back()->withErrors($validator);

            }

        }
        $modelLine = new MasterModelLines();
        $modelLine->brand_id = $request->brand_id;
        $modelLine->model_line = $request->model_line;
        $modelLine->created_by = Auth::id();
        $modelLine->save();
        (new UserActivityController)->createActivity('New Master Model Line Created');

        if($request->request_from == 'Quotation') {
            return response($modelLine);
        }

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
        (new UserActivityController)->createActivity('Master Model Line Updated');
        return redirect()->route('model-lines.index')->with('success','Model Line Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function StoreModellineOrBrand(Request $request) {
        $this->validate($request, [
            'brand' => 'required|unique:master_brands,brand',
            'model_line' => 'required|unique:master_model_lines,model_line'
        ]);


    }
}
