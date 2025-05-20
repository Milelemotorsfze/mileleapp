<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\MasterModelLines;
use App\Models\MasterGrades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserActivityController;
use Illuminate\Support\Facades\Validator;

class MasterGradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mastergrades = MasterGrades::orderBy('id','DESC')->get();
        (new UserActivityController)->createActivity('Open Master Model Lines Grades');
        return view('modeldescription.grade.index', compact('mastergrades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::get();
        $masterModelLines = MasterModelLines::get();
        (new UserActivityController)->createActivity('Create Master Model Lines Grades');
        return view('modeldescription.grade.create',compact('brands', 'masterModelLines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'master_grade' => 'required|string|max:255',
            'brands_id' => 'required|exists:brands,id',
            'master_model_lines_id' => 'required|exists:master_model_lines,id',
        ]);
        MasterGrades::create([
        'grade_name' => $request->input('master_grade'), // Master grade name
        'model_line_id' => $request->input('master_model_lines_id'), // Related model line ID
        'created_by' => auth()->user()->id, // Created by logged-in user
        ]);
        return redirect()->route('mastergrade.index')->with('success', 'Master Grade created successfully!');
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
       
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
