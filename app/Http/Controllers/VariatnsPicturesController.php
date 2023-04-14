<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Varaint;
use App\Models\AvailableColour;
use App\Models\VariantPicture;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\Blob;
use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;
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
    public function edit($id)
    {
        $data = AvailableColour::find($id);
        return view('variants.add_pictures', compact('data'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'images.*' => 'image|max:20480',
        ]);
        $blobClient = BlobRestProxy::createBlobService(config('azure.storage_connection_string'));
        $containerName = config('azure.storage_container_name');
    
        $paths = [];
    
        foreach ($validated['images'] as $image) {
            // Generate unique file name
            $fileName = time() . '-' . $image->getClientOriginalName();
            // Upload file to Azure Blob Storage
            $options = new CreateBlockBlobOptions();
            $options->setContentType($image->getClientMimeType());
            $blobClient->createBlockBlob($containerName, $fileName, fopen($image->getRealPath(), 'r'), $options);
            // Save file path to local database
            $path = 'https://' . config('azure.storage_connection_string') . '/' . $containerName . '/' . $fileName;
            array_push($paths, $path);
        }
        // Save paths to VariantPicture model
        foreach ($paths as $path) {
            VariantPicture::create([
                'image_path' => $path,
                'available_colour_id' => $request->available_colour_id,
            ]);
        }
        return redirect()->route('variant_pictures.index')->with('success', 'Images uploaded successfully!');
    }
}
