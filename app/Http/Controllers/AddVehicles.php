<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AddVehicles extends Controller
{
    public function insert(Request $request)
    {
    //     $masterModelLineids = Varaint::where('brands_id', $request->brand)
    //     ->where('my', $request->my)
    //     ->groupBy('master_model_lines_id')
    //     ->pluck('master_model_lines_id')
    //     ->toArray();
    //    $data = MasterModelLines::whereIn('id', $masterModelLineids)
    //    ->pluck('model_line')
    //    ->toArray();
    //    return $data;    
    }
}
