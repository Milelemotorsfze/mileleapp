<?php

namespace App\Http\Controllers;

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

        if (request()->ajax()) {
            $modelYearRules = ModelYearCalculationRule::all();
            return DataTables::of($modelYearRules)
                ->editColumn('created_at', function($query) {
                    return Carbon::parse($query->created_at)->format('d M Y');
                })
                ->toJson();
        }

        $html = $builder->columns([
            ['data' => 'id', 'name' => 'id','title' => 'S.No'],
            ['data' => 'name', 'name' => 'name','title' => 'Name'],
            ['data' => 'value', 'name' => 'value','title' => 'Value'],
            ['data' => 'created_at', 'name' => 'created_at','title' => 'Created At'],

        ]);
        return view('pages.model-year-settings.rules', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }
}
