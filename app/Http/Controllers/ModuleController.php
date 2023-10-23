<?php

namespace App\Http\Controllers;

use App\Models\Modules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserActivityController;
class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modules = Modules::orderBy('id','DESC')->get();
        (new UserActivityController)->createActivity('Open Modules Listing');
        return view('modules.index', compact('modules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('modules.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:modules,name',
        ]);

        $module = new Modules();
        $module->name = $request->name;
        $module->save();
        (new UserActivityController)->createActivity('Modules Created');
        return redirect()->route('modules.index')->with('success','Module Created Successfully.');
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
        $module = Modules::find($id);

        return view('modules.edit', compact('module'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $module = Modules::find($id);

        $this->validate($request, [
            'name' => 'required|unique:modules,name,'.$module->id,
        ]);

        $module->name = $request->name;
        $module->save();
        (new UserActivityController)->createActivity('Modules Updated');
        return redirect()->route('modules.index')->with('success','Module Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
