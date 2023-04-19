<?php

namespace App\Http\Controllers;

use App\Models\ColorCode;
use App\Models\MasterModel;
use App\Models\SupplierInventory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

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
        $request->validate([
            'file' => 'required|file|max:102400'
        ]);

        if ($request->file('file')) {
            $file = $request->file('file');
            $fileName = time().'.'.$file->getClientOriginalExtension();
            $destinationPath = "inventory";
            $file->move($destinationPath,$fileName);
            $file = fopen("inventory/".$fileName, "r");
            $i = 0;
            $numberOfFields = 9;
            $uploadFileContents = [];

            while (($filedata = fgetcsv($file, 5000, ",")) !== FALSE) {
                $num = count($filedata);
                if ($i > 0 && $num == $numberOfFields)
                {
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

                    foreach ($parentColors as $row)
                    {
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
                    $uploadFileContents[$i]['supplier'] = $supplier_id;
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
            $j=0;
            foreach($uploadFileContents as $uploadFileContent){
                $isModelExist = MasterModel::where('model',$uploadFileContent['model'])->first();
                if(!$isModelExist)
                {
                    $newModels[$j]['model'] = $uploadFileContent['model'];
                    $newModels[$j]['sfx'] = $uploadFileContent['sfx'];
                }
                $j++;
            }

            $newModels = array_map("unserialize", array_unique(array_map("serialize", $newModels)));
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
                        fputcsv($file, array(
                            $newmodel['model'],
                            $newmodel['sfx'],
                        ));
                    }
                    fclose($file);
                };
                return Response::stream($callback, 200, $headers);
            }else
            {
                foreach($uploadFileContents as $uploadFileContent){
                    $model = MasterModel::where('model', $uploadFileContent['model'])
                        ->where('sfx', $uploadFileContent['sfx'])
                        ->first();
                    $modelId = $model->id;
                    info("modelid".$modelId);
                    $inventories = SupplierInventory::where('master_model_id', $modelId);
                    if ($inventories->count() > 0)
                    {
                        info("existing".$model->id);

                        DB::beginTransaction();
                        $data = [
                            'master_model_id' => $modelId,
                            'engine_number'  =>  $uploadFileContent['engine_number'],
                            'chasis'		 =>  $uploadFileContent['chasis'],
                            'color_code'	 =>  $uploadFileContent['color_code'],
                            'color_name'     =>  $uploadFileContent['color_name'],
                            'pord_month'	 =>  $uploadFileContent['pord_month'],
                            'po_arm'		 =>  $uploadFileContent['po_arm'],
                            'status'		 =>  $uploadFileContent['status'],
                            'eta_import'	 =>  $uploadFileContent['eta_import']
                        ];

                        $supplierInventory = $inventories->where('chasis', $uploadFileContent['chasis'])
                                                                ->orwhere('engine_number', $uploadFileContent['engine_number'])
                                                                ->orwhere('color_code', $uploadFileContent['color_code'])
                                                                ->orwhere('pord_month', $uploadFileContent['pord_month'])
                                                                ->orwhere('po_arm', $uploadFileContent['po_arm'])
                                                                ->orwhere('eta_import', $uploadFileContent['eta_import']);
                            info("".$supplierInventory->get());

//                        if ($supplierInventory->count() > 1)
//                        {
//                            $supplierInventory = $supplierInventory->orderBy('id','DESC')->first();
//                            info("lastest update".$supplierInventory->id);
//                            $supplierInventory->update($data);
//
//                        }else{
//                            $supplierInventory = $supplierInventory->first();
//                            info("update".$supplierInventory->id);
//                            $supplierInventory->update($data);
//                        }
                        $supplierInventory = $supplierInventory->first();
                        $supplierInventory->update($data);

                        DB::commit();

                    }else {
                        INFO("NEW MODEL".$model->id);

                        $supplierInventory = new SupplierInventory();
                        $supplierInventory->master_model_id = $model->id;
                        $supplierInventory->chasis          = $uploadFileContent['chasis'];
                        $supplierInventory->engine_number   = $uploadFileContent['engine_number'];
                        $supplierInventory->color_code      = $uploadFileContent['color_code'];
                        $supplierInventory->pord_month      = $uploadFileContent['pord_month'];
                        $supplierInventory->po_arm          = $uploadFileContent['po_arm'];
                        $supplierInventory->eta_import      = $uploadFileContent['eta_import'];
                        $supplierInventory->supplier        =  $uploadFileContent['supplier'];
                        $supplierInventory->whole_sales	    = $uploadFileContent['whole_sales'];
                        $supplierInventory->country     	= $uploadFileContent['country'];
                        $supplierInventory->date            = $uploadFileContent['date'];
                        $supplierInventory->uniques         = $uploadFileContent['uniques'];
                        $supplierInventory->veh_status      = SupplierInventory::status;
                        $supplierInventory->save();
                     }

                }

                return redirect()->route('supplier-inventories.create')->with('message','supplier updated successfully');
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
