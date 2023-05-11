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
use Illuminate\Support\Facades\Auth;

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
              ->leftJoin('variants_reels as vs', 'ac.id', '=', 'vs.available_colour_id')
              ->where(function($query) {
                  $query->whereNotNull('vs.id')
                        ->orWhereNotNull('vp.id');
              })
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
        if ($data->variantPicture()->exists()) {
        $variantsPictures = $data->variantPicture()->get();
        return view('variants.add_pictures', ['data' => $data, 'id' => $id, 'variantsPictures' => $variantsPictures]);
        } else {
        return view('variants.add_pictures', ['data' => $data, 'id' => $id]);
        }
    }
    public function editreels($id)
    {
        $data = AvailableColour::find($id);
        if ($data->VariantsReel()->exists()) {
            $variantsreelss = $data->VariantsReel()->get();
            $variantsreelss = VariantsReel::where('available_colour_id', $id)->get();
            $videos = [];
            $reels = [];
            foreach ($variantsreelss as $reel) {
                $videoUrl = $reel->video_path;
                $reelUrl = $reel->reel_path;
                $videoId = substr(parse_url($videoUrl, PHP_URL_QUERY), 2);
                $reelId = substr(parse_url($reelUrl, PHP_URL_PATH), 8);
                $embedCode = '<iframe  src="https://www.youtube.com/embed/' . $videoId . '" frameborder="1" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
                $embedCodereel = '<iframe src="https://www.youtube.com/embed/' . $reelId . '" frameborder="1" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
                if($videoUrl == null) {
                    $embedCode = null;
                }
                if($reelUrl == null) {
                    $embedCodereel = null;
                }
                $videos[] = $embedCode;
                $reels[] = $embedCodereel;
            }
            return view('variants.add_reels', [
                'data' => $data,
                'id' => $id,
                'variantsreelss' => $variantsreelss,
                'videos' => $videos,
                'reel' => $reels,
            ]);
            } else {
            return view('variants.add_reels', ['data' => $data, 'id' => $id]);
            }
    }
    public function store(Request $request)
{
    $validatedData = $request->validate([
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        'feature_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    if ($request->hasFile('feature_image')) {
        $feature_image_path = time() . '_' . uniqid() . '.' . $request->file('feature_image')->getClientOriginalExtension();
        $request->file('feature_image')->move(public_path('variantimages/feature_images/'), $feature_image_path);
    } else {
        return redirect()->back()->with('error', 'No feature image was uploaded.');
    }

    if ($request->hasFile('images')) {
        $uploadedImagePaths = [];
        foreach ($validatedData['images'] as $image) {
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('variantimages'), $filename);
            VariantPicture::create([
                'image_path' => 'variantimages/' . $filename,
                'status' => 'variantimages/feature_images/' . $feature_image_path,
                'available_colour_id'  => $request->available_colour_id
            ]);
            $uploadedImagePaths[] = 'variantimages/' . $filename;
        }
        return redirect()->back()->with('success', 'Variant pictures uploaded successfully.')
            ->with('uploadedImagePaths', $uploadedImagePaths);
    } else {
        return redirect()->back()->with('error', 'No variant pictures were uploaded.');
    }
}
    public function uploadingreal(Request $request)
    {
        $data = [];
        $available_colour_id = $request->input('available_colour_id');
        $reel_paths = $request->input('reel_path');
        $video_paths = $request->input('video_path');
        for ($i = 0; $i < count($reel_paths); $i++) {
            $data[] = [
                'available_colour_id' => $available_colour_id,
                'reel_path' => $reel_paths[$i],
                'video_path' => $video_paths[$i],
                'created_by' => Auth::id()
            ];
        }
        VariantsReel::insert($data);
        return redirect()->back()->with('success', 'Videos added successfully');        
    }
    public function destroy(Request $request, $variant_picture)
    {
        $id = $variant_picture;
        $picture = VariantPicture::find($id);
        
        if(!$picture) {
            return redirect()->back()->with('error', 'Picture not found');
        }
    
        $picture->delete();
    
        return redirect()->back()->with('success', 'Picture deleted successfully');
    }
    public function deleteVideo($id)
{
    $video = VariantsReel::find($id);
    if ($video->reel_path === null) {
    $video->delete();
    return redirect()->back()->with('success', 'Video deleted successfully');
    } 
    else {
        $video->video_path = null;
        $video->save();
        return redirect()->back()->with('success', 'Video deleted successfully');
    }
}

public function deleteReel($id)
{
    $video = VariantsReel::find($id);
    if ($video->video_path === null) {
    $video->delete();
    return redirect()->back()->with('success', 'Reel deleted successfully');
    } 
    else {
        $video->reel_path = null;
        $video->save();
        return redirect()->back()->with('success', 'Reel deleted successfully');
    }
}
}
