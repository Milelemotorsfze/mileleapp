<?php

namespace App\Http\Controllers;


use App\Models\BlForm;
use Illuminate\Http\Request;

class BLformController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('blform.index');
        $bldata = BlForm::all();
        $vinsdata = VinsData::all();
    }
    public function create()
    {
        return view('blform.create');
    }
    public function store()
    {
    }
    public function show()
    {
        foreach ($vinsdata as $vinsdata) {
            echo $vinsdata->vin_numbers;
        }
    }
    public function storeData(Request $request)
    {
        $blnumber = $request->input('bl_number');
        $vinsdata = $request->input('vin_number');
        DB::table('bl_vinsdata')->insert([
            'bl_number' => $blnumber,
            'vin_numbers' => $vinsdata,
        ]);
        return response()->json(['success' => true]);
    }
}
