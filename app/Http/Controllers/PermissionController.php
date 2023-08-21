<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Modules;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::all();
        return view('permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $modules = Modules::all();
        return view('permissions.create', compact('modules'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $name = $request->name;
        $request->name = Str::slug($request->name);
//        $this->replace($input);
       // dd($request->name);
        $this->validate($request, [
            'name' => 'required|unique:permissions,name',
            'module_id' => 'required'
        ]);

        $isExist = Permission::where('name', $request->name)->first();
        if($isExist) {
            return redirect()->back()->with('error','Permission is already existing');
        }

        $permission = new Permission();

        $permission->module_id = $request->module_id;
        $permission->slug_name = $name;
        $permission->name = $request->name;
        $permission->guard_name =  'web';
        $permission->description = $request->description;
        $permission->save();

        return redirect()->route('permissions.index')->with('success','Permissions Created Successfully.');
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
        $permission = Permission::find($id);
        $modules = Modules::all();
        return view('permissions.edit', compact('modules','permission'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'module_id' => 'required'
        ]);

        $permission = Permission::find($id);
        $permission->module_id = $request->module_id;
        $permission->description = $request->description;
        $permission->save();

        return redirect()->route('permissions.index')->with('success','Permissions Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
