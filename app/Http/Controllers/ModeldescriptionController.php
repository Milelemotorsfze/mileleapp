<?php

namespace App\Http\Controllers;
use App\Models\Brand;
use App\Models\MasterModelLines;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserActivityController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ModeldescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modelLines = MasterModelLines::orderBy('id','DESC')->get();
        (new UserActivityController)->createActivity('Open Master Model Lines Description');
        return view('modeldescription.index', compact('modelLines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $masterModelLines = MasterModelLines::get();
        $brands = Brand::get();
        return view('modeldescription.create',compact('masterModelLines', 'brands'));
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
