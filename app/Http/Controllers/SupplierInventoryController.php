<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ColorCode;
use App\Models\MasterModel;
use App\Models\SupplierInventory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class SupplierInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supplierInventories = SupplierInventory::with('masterModel')
            ->where('veh_status', SupplierInventory::status)
            ->groupBy('master_model_id')
            ->get();
        return view('supplier_inventories.index', compact('supplierInventories'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('supplier_inventories.edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//        $request->validate([
//            'file' => 'required|file|max:102400'
//        ]);
//        return $request->file('file');
//
        if ($request->file('file')) {
            $file = $request->file('file');
            $fileName = time().'.'.$file->getClientOriginalExtension();
            $destinationPath = "inventory";
            $file->move($destinationPath,$fileName);
//            info("ok");
            $file = fopen("inventory/".$fileName, "r");
//            info("file");
//            info($file);
            $i = 0;
            $numberOfFields = 9;
            $uploadFileContents = [];

            while (($filedata = fgetcsv($file, 5000, ",")) !== FALSE) {
//                info($filedata);
//                info("file data");
                $num = count($filedata);
                // Skip first row & check number of fields
                if ($i > 0 && $num == $numberOfFields) {
                    // Key names are the insert table field names - name, email, city, and status
                    $supplier_id = $request->input('supplier');
                    $whole_sales = $request->input('whole_sales');
                    $country = $request->input('country');
                    $date = Carbon::now()->format('d-m-Y :H-i-s');
                    $colourcode = $filedata[4];
                    $colourcodecount = strlen($colourcode);
                        if ($colourcodecount == 5) {
                            $extcolour = substr($colourcode, 0, 3);
                        }
                        if ($colourcodecount == 4) {
                            $altercolourcode = "0" . $colourcode;
                            $extcolour = substr($altercolourcode, 0, 3);
                        }
                    $code_nameex = "ALL";
                    $parentColors = DB::table('color_codes')
                    ->select('parent')
                    ->where('code','=', $extcolour)
                    ->where('status','=',ColorCode::EXTERIOR)
                    ->get();

                        foreach ($parentColors as $row) {
                            $code_nameex = $row->parent;
                        }

                    $colourname = $code_nameex;
                    $uploadFileContents[$i]['model'] = $filedata[0];
                    $uploadFileContents[$i]['sfx'] = $filedata[1];
                    $uploadFileContents[$i]['chasis'] = $filedata[2];
                    $uploadFileContents[$i]['engine_number'] = $filedata[3];
                    $uploadFileContents[$i]['color_code'] = $filedata[4];
                    $uploadFileContents[$i]['color_name'] = $colourname;
                    $uploadFileContents[$i]['pord_month'] = $filedata[5];
                    $uploadFileContents[$i]['po_arm'] = $filedata[6];
                    $uploadFileContents[$i]['status'] = $filedata[7];
                    $uploadFileContents[$i]['eta_import'] = $filedata[8];
                    $uploadFileContents[$i]['supplier_id'] = $supplier_id;
                    $uploadFileContents[$i]['whole_sales'] = $whole_sales;
                    $uploadFileContents[$i]['country'] = $country;
                    $uploadFileContents[$i]['date'] = $date;
                    $uploadFileContents[$i]['uniques'] = $i;
                    $uploadFileContents[$i]['veh_status'] = "suppiler inventory";
                }
                $i++;
            }
            fclose($file);
            $newModels = [];
            foreach($uploadFileContents as $uploadFileContent){
                $isModelExist = MasterModel::where('model',$uploadFileContent['model'])
                    ->first();
                if(!$isModelExist)
                {
                    $newModels[] = $uploadFileContent['model'];
                }
            }
            if(count($newModels) > 0) {
                $filename = 'New_Models_'.date('Y_m_d').'.csv';
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=$filename",
                    "Content-Description: File Transfer"
                );

                $columns = array('Model');
                $callback = function() use ($newModels, $columns)
                {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);
                    foreach($newModels as $newmodel) {
                        info($newmodel);
                        fputcsv($file, array(
                            $newmodel
                        ));
                    }
                    fclose($file);
                };

                return Response::stream($callback, 200, $headers);
            }else {
                return redirect()->back()->with('message','supplier updated successfully');
            }

        }
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
