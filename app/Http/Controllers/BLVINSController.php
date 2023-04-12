<?php

namespace App\Http\Controllers;

use App\Models\blfrom;
use Illuminate\Http\Request;

class BLVINSController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('blform.index');
        $bldata = BLForm::all();
    }
    public function create()
    {
        return view('blform.create');
    }
    public function insertData(Request $request)
    {
        $BLFormNumber = $request->input('bl_number');
        $vinsNumbers = $request->input('vins_numbers');

        $data = new BlVinsdata();
        $data->bl_number = $BLFormNumber;
        $data->vins_numbers = $vinsNumbers;
        $data->save();

        return response()->json(['success' => true]);
    }
}
