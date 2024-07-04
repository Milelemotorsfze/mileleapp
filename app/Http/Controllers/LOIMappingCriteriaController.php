<?php

namespace App\Http\Controllers;

use App\Models\LOIMappingCriteria;
use Illuminate\Http\Request;

class LOIMappingCriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        (new UserActivityController)->createActivity('View LOI Mapping Month / Year');

        $loiMappingCriterias = LOIMappingCriteria::all();
        return view('loi-mapping-criterias.index', compact('loiMappingCriterias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        (new UserActivityController)->createActivity('Open Create LOI Mapping Month / Year Page');

        return view('loi-mapping-criterias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        (new UserActivityController)->createActivity('Open Create LOI Mapping Month / Year Page');

        $this->validate($request, [
            'name' => 'required',
            'value_type' => 'required',
            'value' => 'required',
            'order' => 'required|unique:loi_mapping_criterias,order',
        ],
        [
        'order.unique' => 'Priority Number is already existing!'
        ]);

        $value = $request->value;
        if($request->value_type == 'Month') {
            if($value > 12) {
                return redirect()->back()->with('error', "Please enter valid Month");
            }
        }

        $isExist = LOIMappingCriteria::where('value_type' , $request->value_type)
            ->where('value', $request->value)
            ->first();

        if($isExist) {
            return redirect()->back()->with('error', "This Combination is already existing");
        }

        $loiMappingCriteria = new LOIMappingCriteria();
        $loiMappingCriteria->name = $request->name;
        $loiMappingCriteria->value = $request->value;
        $loiMappingCriteria->order = $request->order;
        $loiMappingCriteria->value_type = $request->value_type;
        $loiMappingCriteria->country = $request->country;
        $loiMappingCriteria->save();

        return redirect()->route('loi-mapping-criterias.index')->with('success',"LOI Mapping Criteria added successfully.");

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
        (new UserActivityController)->createActivity('Open Edit LOI Mapping Month / Year Page');

        $loiMappingCriteria = LOIMappingCriteria::find($id);

        return view('loi-mapping-criterias.edit', compact('loiMappingCriteria'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        (new UserActivityController)->createActivity('Update LOI Mapping Criteria.');

        $loiMappingCriteria = LOIMappingCriteria::find($id);

        $this->validate($request, [
            'name' => 'required',
            'value_type' => 'required',
            'value' => 'required',
            'order' => 'required|unique:loi_mapping_criterias,order,'.$id,
        ],
        [
        'order.unique' => 'Priority Number is already existing!'
        ]);
        $value = $request->value;
        if($request->value_type == 'Month') {
            if($value > 12) {
                return redirect()->back()->with('error', "Please enter valid Month");
            }
        }
        $isExist = LOIMappingCriteria::where('value_type' , $request->value_type)
            ->whereNot('id', $id)
            ->where('value', $request->value)
            ->first();

        if($isExist) {
            return redirect()->back()->with('error', "This Combination is already existing");
        }

        $loiMappingCriteria->name = $request->name;
        $loiMappingCriteria->value = $request->value;
        $loiMappingCriteria->order = $request->order;
        $loiMappingCriteria->value_type = $request->value_type;
        $loiMappingCriteria->country = $request->country;
        $loiMappingCriteria->save();

        return redirect()->route('loi-mapping-criterias.index')->with('success',"LOI Mapping Criteria updated successfully.");

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        (new UserActivityController)->createActivity('Deleted LOI Mapping Criteria.');

        $loiMappingCriteria = LOIMappingCriteria::find($id);
        $loiMappingCriteria->delete();

        return response(true);
    }
}
