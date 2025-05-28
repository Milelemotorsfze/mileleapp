<?php

namespace App\Http\Controllers;

use App\Http\Controllers\UserActivityController;
use App\Models\Brand;
use App\Models\MasterGrades;
use App\Models\MasterModelDescription;
use App\Models\MasterModelLines;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MasterGradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mastergrades = MasterGrades::with('modelDescriptions')->orderBy('id', 'DESC')->get();
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

        return view('modeldescription.grade.create', compact('brands', 'masterModelLines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'brands_id' => ['required', 'exists:brands,id'],
            'master_grade' => [
                'required',
                'string',
                'max:255',
                Rule::unique('master_vehicles_grades', 'grade_name')->where(function ($query) use ($request) {
                    return $query->where('model_line_id', $request->input('master_model_lines_id'));
                }),
            ],
            'master_model_lines_id' => ['required', 'exists:master_model_lines,id'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

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
        $gradeIsUsed = MasterModelDescription::where('master_vehicles_grades_id', $id)->exists();

        if ($gradeIsUsed) {
            return redirect()->route('mastergrade.index');
        }

        $grade = MasterGrades::with('modelLine.brand')->findOrFail($id);
        $brands = Brand::get();

        return view('modeldescription.grade.edit', compact('grade', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'brands_id' => ['required', 'exists:brands,id'],
            'master_grade' => [
                'required',
                'string',
                'max:255',
                Rule::unique('master_vehicles_grades', 'grade_name')->ignore($id)
                    ->where(function ($query) use ($request) {
                        return $query->where('model_line_id', $request->input('master_model_lines_id'));
                    }),
            ],
            'master_model_lines_id' => ['required', 'exists:master_model_lines,id'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        MasterGrades::where('id', $id)->update([
            'grade_name' => $request->input('master_grade'),
            'model_line_id' => $request->input('master_model_lines_id'),
            'created_by' => auth()->user()->id,
        ]);

        return redirect()->route('mastergrade.index')->with('success', 'Master Grade updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
