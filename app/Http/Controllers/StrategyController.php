<?php

namespace App\Http\Controllers;

use App\Models\Strategy;
use App\Http\Requests\StoreStrategyRequest;
use App\Http\Requests\UpdateStrategyRequest;

class StrategyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreStrategyRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Strategy $strategy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $strategies = Strategy::where('lead_source_id', $id)->get();
        return view('calls.createstrategy')->with('id', $id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStrategyRequest $request, Strategy $strategy)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Strategy $strategy)
    {
        //
    }
}
