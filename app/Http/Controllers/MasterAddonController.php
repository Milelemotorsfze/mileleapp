<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use Illuminate\Http\Request;

class MasterAddonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $addonType = 'P';
        $accessories = Addon::where('addon_type', 'P')->select('id','name','created_by','created_at')->orderBy('id','DESC')->get();
        $spareParts = Addon::where('addon_type', 'SP')->select('id','name','created_by','created_at')->orderBy('id','DESC')->get();
        $kits = Addon::where('addon_type', 'K')->orderBy('id','DESC')->get();

        return view('masterAddon.index', compact('accessories','spareParts','kits','addonType'));
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
       $addon = Addon::findOrFail($id);

       return view('masterAddon.edit', compact('addon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $addon = Addon::find($id);
        $addonType = $addon->addon_type;
        if($addon->addon_type == 'K') {

            $name = 'Kit: ' .$request->kit_year .'year | '.$request->kit_km.'KM';
            $addon->name = $name;
            $addon->kit_year = $request->kit_year;
            $addon->kit_km = $request->kit_km;

        }else{
            $addon->name = $request->name;
        }

        $addon->save();

        return redirect()->route('master-addons.index',compact('addonType'))->with('success','Addon updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
