<?php

namespace App\Http\Controllers;

use App\Models\ParentColour;
use Illuminate\Http\Request;

class ParentColourController extends Controller
{
    public function index()
    {
        $parentColours = ParentColour::all();
        return view('parentColours.index', compact('parentColours'));
    }


    public function create()
    {
        $parentColours = ParentColour::all();
        dd($parentColours);
        return view('colours.create', compact('parentColours'));
    }

    public function getParentColour($id)
    {
        $parentColour = ParentColour::findOrFail($id);
        return response()->json($parentColour);
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|unique:parent_colours,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $parentColour = new ParentColour();
        $parentColour->name = $request->name;
        $parentColour->created_by = auth()->user()->id;
        $parentColour->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Parent Colour added successfully!',
            'id' => $parentColour->id,
            'name' => $parentColour->name
        ]);

        return redirect()->route('parentColours.index')->with('success', 'Parent Colour created successfully.');
    }

    public function edit(ParentColour $parentColour)
    {
        return view('parentColours.edit', compact('parentColour'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:parent_colours,name,' . $id,
        ]);

        $parentColour = ParentColour::findOrFail($id);
        $parentColour->name = $request->name;
        $parentColour->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Parent Colour updated successfully!'
        ]);
    }

    public function fetch()
    {
        $parentColours = ParentColour::select('id', 'name')->get();
        return response()->json($parentColours);
    }

    public function destroy(Request $request, $id)
    {
        $parentColour = ParentColour::findOrFail($id);
        $parentColour->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Parent Colour deleted successfully.'
        ]);
    }
}
