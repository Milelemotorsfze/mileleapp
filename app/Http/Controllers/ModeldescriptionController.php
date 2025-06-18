<?php

namespace App\Http\Controllers;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use App\Models\MasterModelLines;
use App\Models\MasterGrades;
use App\Models\MasterModelDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserActivityController;
use Illuminate\Support\Facades\Validator;
use Exception;

class ModeldescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check if user has the permission
        if (!Auth::user()->hasPermissionForSelectedRole('view-model-description-list')) {
            $errorMsg ="Sorry ! You don't have permission to access this page";
            return view('hrm.notaccess',compact('errorMsg'));
        }

        $MasterModelDescription = MasterModelDescription::orderBy('updated_at','DESC')->get();
        (new UserActivityController)->createActivity('Open Master Model Lines Description');
        return view('modeldescription.index', compact('MasterModelDescription'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user has the permission
        if (!Auth::user()->hasPermissionForSelectedRole('create-model-description')) {
            $errorMsg ="Sorry ! You don't have permission to access this page";
            return view('hrm.notaccess',compact('errorMsg'));
        }

        $masterModelLines = MasterModelLines::get();
        $brands = Brand::get();
        return view('modeldescription.create',compact('masterModelLines', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    // Validate the request
    $validator = Validator::make($request->all(), [
        'steering' => 'required|string',
        'brands_id' => 'required|exists:brands,id',
        'master_model_lines_id' => 'required|exists:master_model_lines,id',
        'grade' => 'nullable|string',
        'fuel_type' => 'required|string',
        'gearbox' => 'nullable|string',
        'drive_train' => 'nullable|string',
        'window_type' => 'nullable|string',
        'model_description' => 'required|string|unique:master_model_descriptions,model_description'
    ],
    [
        'model_description.unique' => 'Model detail is already existing !'
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    try {
        // Use a database transaction
        DB::transaction(function () use ($request) {
            $description = new MasterModelDescription();
            $description->steering = $request->input('steering');
            $description->model_line_id = $request->input('master_model_lines_id');
            $description->master_vehicles_grades_id = $request->input('grade');
            $description->engine = $request->input('engine');
            $description->fuel_type = $request->input('fuel_type');
            $description->transmission = $request->input('gearbox');
            $description->drive_train = $request->input('drive_train');
            $description->window_type = $request->input('window_type');
            $description->specialEditions = $request->input('specialEditions');
            $description->others = $request->input('others');
            $description->created_by = Auth::id();
            $description->model_description = $request->input('model_description');
            $description->save();
        });

        // Redirect with success message if everything is fine
        return redirect()->route('modeldescription.index')->with('success', 'Master Model Description created successfully.');
    } catch (Exception $e) {
        // Log the exception (optional)
        \Log::error('Error creating Master Model Description: ' . $e->getMessage());

        // Redirect back with error message
        return redirect()->back()->with('error', 'Failed to create Master Model Description. Please try again.');
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    
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
        $modelDescription = MasterModelDescription::find($id);
        $modelDescription->delete();

        return response(true);

    }
    public function getGrades($modelId)
    {
    $grades = MasterGrades::where('model_line_id', $modelId)->get(['id', 'grade_name']);
    return response()->json($grades);
    }
    public function getModelDetails($model_line_id)
{
    $models = DB::table('master_model_descriptions')
        ->leftJoin('master_vehicles_grades', 'master_model_descriptions.master_vehicles_grades_id', '=', 'master_vehicles_grades.id')
        ->where('master_model_descriptions.model_line_id', $model_line_id)
        ->select(
            'master_model_descriptions.id',
            'master_model_descriptions.model_description',
            'master_model_descriptions.steering',
            'master_model_descriptions.engine',
            'master_model_descriptions.fuel_type',
            'master_model_descriptions.transmission',
            'master_model_descriptions.window_type',
            'master_model_descriptions.drive_train',
            'master_model_descriptions.specialEditions',
            'master_model_descriptions.others',
            'master_vehicles_grades.grade_name as grade_name'
        )
        ->get();
    
    if ($models->isEmpty()) {
        return response()->json(['success' => false, 'message' => 'No details found for the selected model.'], 404);
    }
    return response()->json($models);
}
    }
