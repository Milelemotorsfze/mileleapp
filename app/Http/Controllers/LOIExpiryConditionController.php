<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LOIExpiryCondition;

class LOIExpiryConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loiExpiryConditions = LOIExpiryCondition::all();
        return view('loi-expiry-conditions.index', compact('loiExpiryConditions'));
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
        $loiExpiryCondition = LOIExpiryCondition::find($id);
        $loiExpiryCondition->expiry_duration_year = $request->expiry_duration_year;
        $loiExpiryCondition->updated_by = Auth::id();
        $loiExpiryCondition->save();
        
        return redirect()->back()->with('success', "LOI expiry Condition updated Successfully.");
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
