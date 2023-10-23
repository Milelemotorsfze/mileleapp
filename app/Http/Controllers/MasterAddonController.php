<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use Illuminate\Http\Request;
use App\Http\Controllers\UserActivityController;
class MasterAddonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        $accessories = Addon::where('addon_type', 'P')->select('id','name','created_by','created_at')->orderBy('updated_at','DESC')->get();
        $spareParts = Addon::where('addon_type', 'SP')->select('id','name','created_by','created_at')->orderBy('updated_at','DESC')->get();
        $kits = Addon::where('addon_type', 'K')->orderBy('updated_at','DESC')->get();
        if($request->page != 'EDIT') {
            $addonType = 'P';
        }
        else {
            $addonType = $request->addonType;
        }
        (new UserActivityController)->createActivity('Open Master Addon');
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
        $page = 'EDIT';
        (new UserActivityController)->createActivity('Update Master Addon');
        return redirect()->route('master-addons.index',compact('addonType','page'))->with('success','Addon updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
