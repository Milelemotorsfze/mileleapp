<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Varaint;
use App\Models\AvailableColour;
use App\Models\VariantPicture;
use App\Models\VariantsReel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
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
              ->groupBy('v.name')
              ->get();
        $reels = DB::table('varaints as v')
              ->join('available_colour as ac', 'v.id', '=', 'ac.varaint_id')
              ->leftJoin('variants_reels as vs', 'ac.id', '=', 'vs.available_colour_id')
              ->whereNull('vs.id')
              ->select('v.*', 'ac.*')
              ->get();
            return view('variants.index', compact('rows', 'rowwithpictures', 'reels'));  
    }
    public function edit($id)
    {
        $data = AvailableColour::find($id);
        return view('variants.add_pictures', ['data' => $data, 'id' => $id]);
    }
    public function editreels($id)
    {
        $data = AvailableColour::find($id);
        return view('variants.add_reels', ['data' => $data, 'id' => $id]);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        if ($request->hasFile('images')) {
            $uploadedImagePaths = [];
            foreach ($validatedData['images'] as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('variantimages'), $filename);
                VariantPicture::create([
                    'image_path' => 'variantimages/' . $filename,
                    'available_colour_id'  => $request->available_colour_id
                ]);
                $uploadedImagePaths[] = 'variantimages/' . $filename;
            }
            return redirect()->back()->with('success', 'Variant pictures uploaded successfully.')
                ->with('uploadedImagePaths', $uploadedImagePaths);
        }
        return redirect()->back()->with('error', 'No variant pictures were uploaded.');
    }

    public function uploadingreal(Request $request)
    {
        $validatedData = $request->validate([
            'video' => 'required|mimetypes:video/mp4,video/quicktime|max:50000',
        ]);
        $path = Storage::disk('public')->putFile('videos', $request->file('video'));
        $variantReel = new VariantsReel();
        $variantReel->reel_path = $path;
        $variantReel->save();
        return response()->json([
            'path' => $path,
            'message' => 'Video uploaded successfully!',
        ]);
    }
}
