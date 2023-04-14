<?php

namespace App\Http\Controllers;

use App\Models\blformvins;
use Illuminate\Http\Request;

class BLVINSController extends Controller
{   
    public function blformvins(Request $request)
    {
        $data = new Data;
        $data->bl_number = $request->bl_number;
        $data->vin_number = $request->vin_number;
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'VIN Added Successfully'
        ]);
    }

}