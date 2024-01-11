<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\ModelYearCalculationRule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class ModelYearCalculationRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $modelYearRules = ModelYearCalculationRule::all();
            return DataTables::of($modelYearRules)
                ->editColumn('created_at', function($query) {
                    return Carbon::parse($query->created_at)->format('d M Y');
                })
                ->addColumn('action', function(ModelYearCalculationRule $modelYearCalculationRule) {
                    return view('model-year-settings.rules.action',compact('modelYearCalculationRule'));
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        $html = $builder->columns([
            ['data' => 'id', 'name' => 'id','title' => 'S.No'],
            ['data' => 'name', 'name' => 'name','title' => 'Name'],
            ['data' => 'value', 'name' => 'value','title' => 'Value'],
            ['data' => 'created_at', 'name' => 'created_at','title' => 'Created At'],
            ['data' => 'action', 'name' => 'action','title' => 'Action'],

        ]);
        return view('model-year-settings.rules.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('model-year-settings.rules.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:model_year_calculation_rules',
            'value' => 'required|numeric'
        ]);

        $modelYearRule = new ModelYearCalculationRule();
        $modelYearRule->name = $request->name;
        $modelYearRule->value = $request->value;
        $modelYearRule->save();

        return redirect()->route('model-year-calculation-rules.index')->with('success', "Model Year Calculation Rule created successfully.");
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
        $modelYearCalculationRule = ModelYearCalculationRule::findOrFail($id);

        return view('model-year-settings.rules.edit', compact('modelYearCalculationRule'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|unique:model_year_calculation_rules,name,'.$id,
            'value' => 'required|numeric'
        ]);

        $modelYearCalculationRule = ModelYearCalculationRule::findOrFail($id);

        $modelYearCalculationRule->name = $request->name;
        $modelYearCalculationRule->value = $request->value;
        $modelYearCalculationRule->save();

        return redirect()->route('model-year-calculation-rules.index')->with('success', "Model Year Calculation Rule updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
