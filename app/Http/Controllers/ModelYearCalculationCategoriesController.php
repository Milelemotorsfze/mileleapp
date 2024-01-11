<?php

namespace App\Http\Controllers;

use App\Models\ModelYearCalculationCategory;
use App\Models\ModelYearCalculationRule;
use Illuminate\Http\Request;
use Yajra\DataTables\Html\Builder;

class ModelYearCalculationCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Builder $builder)
    {
       $modelYearCategories = ModelYearCalculationCategory::all();
        return view('model-year-settings.categories.index', compact('modelYearCategories'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $modelYearCalculationRules = ModelYearCalculationRule::all();

        return view('model-year-settings.categories.create', compact('modelYearCalculationRules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:model_year_calculation_categories',
            'model_year_rule_id' => 'required'
        ]);

        $modelYearCalculationCategory = new ModelYearCalculationCategory();
        $modelYearCalculationCategory->name = $request->name;
        $modelYearCalculationCategory->model_year_rule_id = $request->model_year_rule_id;
        $modelYearCalculationCategory->save();

        return redirect()->route('model-year-calculation-categories.index')->with('success', "Model Year Calculation Category created successfully.");
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
        $modelYearCalculationCategory = ModelYearCalculationCategory::findOrFail($id);
        $modelYearCalculationRules = ModelYearCalculationRule::all();

        return view('model-year-settings.categories.edit', compact('modelYearCalculationCategory','modelYearCalculationRules' ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|unique:model_year_calculation_categories,name'.$id,
            'model_year_rule_id' => 'required'
        ]);

        $modelYearCalculationCategory = ModelYearCalculationCategory::findOrFail($id);

        $modelYearCalculationCategory->name = $request->name;
        $modelYearCalculationCategory->model_year_rule_id = $request->model_year_rule_id;
        $modelYearCalculationCategory->save();

        return redirect()->route('model-year-calculation-categories.index')->with('success', "Model Year Calculation Category updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $modelYearCalculationCategory = ModelYearCalculationCategory::findOrFail($id);
        $modelYearCalculationCategory->delete();

        return response(true);
    }
}
