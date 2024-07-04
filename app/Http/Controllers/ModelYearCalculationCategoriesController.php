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
        (new UserActivityController)->createActivity('Open the listing of model year calculation categories.');

        $modelYearCategories = ModelYearCalculationCategory::all();
        return view('model-year-settings.categories.index', compact('modelYearCategories'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        (new UserActivityController)->createActivity('Open the create page of model year calculation category.');

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
        (new UserActivityController)->createActivity('Created new model year calculation category.');

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
        (new UserActivityController)->createActivity('Open the create page of model year calculation category.');

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
        (new UserActivityController)->createActivity('Updated new model year calculation category.');

        return redirect()->route('model-year-calculation-categories.index')->with('success', "Model Year Calculation Category updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        (new UserActivityController)->createActivity('Deleted model year calculation category.');
        
        $modelYearCalculationCategory = ModelYearCalculationCategory::findOrFail($id);
        $modelYearCalculationCategory->delete();

        return response(true);
    }
}
