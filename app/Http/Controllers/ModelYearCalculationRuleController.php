<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\ModelYearCalculationCategory;
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
        $modelYearRules = ModelYearCalculationRule::all();

        return view('model-year-settings.rules.index', compact('modelYearRules'));
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
        $modelYearCategories = ModelYearCalculationCategory::where('model_year_rule_id', $id)->delete();

        $modelYearCalculationRule = ModelYearCalculationRule::findOrFail($id);
        $modelYearCalculationRule->delete();

        return response(true);
    }
}
