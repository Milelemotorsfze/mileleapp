<?php

namespace App\Http\Controllers;
use App\Models\MasterModelDescription;

use Illuminate\Http\Request;

class ModelDescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modelDescriptions = MasterModelDescription::orderBy('id','DESC')->get();
        (new UserActivityController)->createActivity('Open Master Descriptions');
        return view('model-descriptions.index', compact('modelDescriptions'));
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
