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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MasterGradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            if (!Auth::user()->hasPermissionForSelectedRole('master-grade-list')) {
                $errorMsg = "Sorry! You don't have permission to access this page";
                return view('hrm.notaccess', compact('errorMsg'));
            }

            $mastergrades = MasterGrades::with('modelDescriptions')->orderBy('id', 'DESC')->get();
            (new UserActivityController)->createActivity('Open Master Grades');

            return view('modeldescription.grade.index', compact('mastergrades'));
        } catch (\Exception $e) {
            Log::error('Failed to load Master Grades index', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            abort(500, 'Something went wrong while loading the master grades.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            if (!Auth::user()->hasPermissionForSelectedRole('create-master-grade')) {
                $errorMsg = "Sorry! You don't have permission to access this page";
                return view('hrm.notaccess', compact('errorMsg'));
            }

            $brands = Brand::get();
            $masterModelLines = MasterModelLines::get();
            (new UserActivityController)->createActivity('Create Master Model Lines Grades');

            return view('modeldescription.grade.create', compact('brands', 'masterModelLines'));
        } catch (\Exception $e) {
            Log::error('Failed to open create Master Grade page', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            abort(500, 'Something went wrong while loading the create page.');
        }
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
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        try {
            DB::beginTransaction();
    
            MasterGrades::create([
                'grade_name' => $request->input('master_grade'),
                'model_line_id' => $request->input('master_model_lines_id'),
                'created_by' => auth()->user()->id,
            ]);
    
            DB::commit();
            return redirect()->route('mastergrade.index')->with('success', 'Master Grade created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
    
            // Log the error
            Log::error('Failed to create Master Grade', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'input' => $request->all()
            ]);
    
            return redirect()->back()->with('error', 'An error occurred while creating the Master Grade.')->withInput();
        }
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
        try {
            $gradeIsUsed = MasterModelDescription::where('master_vehicles_grades_id', $id)->exists();

            if ($gradeIsUsed) {
                Log::info('Attempt to edit used Master Grade blocked', [
                    'grade_id' => $id,
                    'user_id' => auth()->id()
                ]);
                return redirect()->route('mastergrade.index');
            }

            $grade = MasterGrades::with('modelLine.brand')->findOrFail($id);
            $brands = Brand::get();
            $modelLines = MasterModelLines::where('brand_id', $grade->modelLine->brand->id)->get();

            return view('modeldescription.grade.edit', compact('grade', 'brands', 'modelLines'));
        } catch (\Exception $e) {
            Log::error('Failed to load edit page for Master Grade', [
                'error' => $e->getMessage(),
                'grade_id' => $id,
                'user_id' => auth()->id()
            ]);
            abort(500, 'Something went wrong while editing the master grade.');
        }
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
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        try {
            DB::beginTransaction();
    
            MasterGrades::where('id', $id)->update([
                'grade_name' => $request->input('master_grade'),
                'model_line_id' => $request->input('master_model_lines_id'),
                'created_by' => auth()->user()->id,
            ]);
    
            DB::commit();
            return redirect()->route('mastergrade.index')->with('success', 'Master Grade updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
    
            // Log the error
            Log::error('Failed to update Master Grade', [
                'error' => $e->getMessage(),
                'grade_id' => $id,
                'user_id' => auth()->id(),
                'input' => $request->all()
            ]);
    
            return redirect()->back()->with('error', 'An error occurred while updating the Master Grade.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
