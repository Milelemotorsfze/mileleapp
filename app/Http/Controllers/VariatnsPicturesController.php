<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Varaint;
use App\Models\AvailableColour;
use App\Models\VariantsPicture;
class VariatnsPicturesController extends Controller
{
    public function index()
    {
        $rows = DB::table('varaints as v')
              ->join('available_colour as ac', 'v.id', '=', 'ac.varaint_id')
              ->leftJoin('variants_pictures as vp', 'ac.id', '=', 'vp.available_colour_id')
              ->whereNull('vp.id')
              ->select('v.*', 'ac.*')
              ->get();
        $rowwithpictures = DB::table('varaints as v')
              ->join('available_colour as ac', 'v.id', '=', 'ac.varaint_id')
              ->leftJoin('variants_pictures as vp', 'ac.id', '=', 'vp.available_colour_id')
              ->whereNotNull('vp.id')
              ->select('v.*', 'ac.*')
              ->get();
            return view('variants.index', compact('rows', 'rowwithpictures'));  
    }
}
