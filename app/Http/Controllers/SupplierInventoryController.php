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
    public function index()
    {
        $supplierInventories = SupplierInventory::with('masterModel')
            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
            ->groupBy('master_model_id')
            ->get();

        return view('supplier_inventories.index', compact('supplierInventories'));
    }
    public function create()
    {
        return view('supplier_inventories.edit');
    }
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

            if(count($newModels) > 0)
            {
                $filename = 'New_Models_'.date('Y_m_d').'.csv';
                $columns = array('Model','SFX');
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

                return redirect()->route('supplier-inventories.create')->with('message','Please add new models to master table.');
            } else
            {
                $csvModels = [];
                foreach ($uploadFileContents as $uploadFileContent)
                {
                    $model = MasterModel::where('model', $uploadFileContent['model'])
                        ->where('sfx', $uploadFileContent['sfx'])
                        ->first();
                    $modelId = $model->id;
                    $csvModels[] = $modelId;
                    $inventories = SupplierInventory::where('master_model_id', $modelId);

                    if ($inventories->count() > 0)
                    {
                        DB::beginTransaction();

                        $etaImport = Carbon::createFromFormat('d/m/Y', $uploadFileContent['eta_import'])->format('Y-m-d');
                        $supplierInventories = SupplierInventory::where('master_model_id', $modelId)
                                                ->where('chasis', $uploadFileContent['chasis']);
                        if ($supplierInventories->count() > 1) {
                            $supplierInventories = $supplierInventories->where('engine_number', $uploadFileContent['engine_number']);

                        }
                        if($supplierInventories->count() > 1) {
                            $supplierInventories = $supplierInventories->where('color_code', $uploadFileContent['color_code']);

                        }
                        if($supplierInventories->count() > 1) {
                            $supplierInventories = $supplierInventories->where('pord_month', $uploadFileContent['pord_month']);

                        }
                        if($supplierInventories->count() > 1) {
                            $supplierInventories = $supplierInventories->where('po_arm', $uploadFileContent['po_arm']);

                        }
                        if($supplierInventories->count() > 1) {
                            $supplierInventories = $supplierInventories->where('eta_import', $etaImport);

                        }
                        if($supplierInventories->count() > 1 ) {
                            $supplierInventory = $supplierInventories->orderBy('id','DESC')->first();

                            $supplierInventory->chasis          = $uploadFileContent['chasis'];
                            $supplierInventory->engine_number   = $uploadFileContent['engine_number'];
                            $supplierInventory->color_code      = $uploadFileContent['color_code'];
                            $supplierInventory->color_name      = $uploadFileContent['color_name'];
                            $supplierInventory->status	        = $uploadFileContent['status'];
                            $supplierInventory->pord_month      = $uploadFileContent['pord_month'];
                            $supplierInventory->po_arm          = $uploadFileContent['po_arm'];
                            $supplierInventory->eta_import      = $etaImport;
                            $supplierInventory->save();
                        }
                        if($supplierInventories->count() == 1) {
                            $supplierInventory  = $supplierInventories->first();
                            $supplierInventory->chasis          = $uploadFileContent['chasis'];
                            $supplierInventory->engine_number   = $uploadFileContent['engine_number'];
                            $supplierInventory->color_code      = $uploadFileContent['color_code'];
                            $supplierInventory->color_name      = $uploadFileContent['color_name'];
                            $supplierInventory->status	        = $uploadFileContent['status'];
                            $supplierInventory->pord_month      = $uploadFileContent['pord_month'];
                            $supplierInventory->po_arm          = $uploadFileContent['po_arm'];
                            $supplierInventory->eta_import      = $etaImport;
                            $supplierInventory->save();
                        }
                        DB::commit();

                    }else {

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
                        $supplierInventory->veh_status      = SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY;
                        $supplierInventory->save();
                     }

                }

                $deletedModels = MasterModel::with('supplierInventories')
                    ->whereHas('supplierInventories',function ($query) use ($csvModels) {
                        $query->whereNotIn('master_model_id', $csvModels)
                            ->groupBY('master_model_id');
                    })
                    ->pluck( 'Model','sfx');

                // status changed to deleted

                $deletedSupplierInventories = SupplierInventory::whereIn('master_model_id', $deletedModels)->pluck('id');

                foreach ($deletedSupplierInventories as $deletedSupplierInventory) {
                    $supplierInventory = SupplierInventory::find($deletedSupplierInventory);
                    info($supplierInventory);
                    $supplierInventory->veh_status = SupplierInventory::VEH_STATUS_DELETED;
                }

                $filename = 'DeletedModels_Models_'.date('Y_m_d').'.csv';
                header("Content-Description: File Transfer");
                header("Content-Disposition: attachment; filename=$filename");
                header("Content-Type: application/csv;");

                $file = fopen('php://output', 'w');
                $columns = array("models","SFX");
                fputcsv($file, $columns);
                foreach($deletedModels as $key =>  $deletedModel) {
                    fputcsv($file, array(
                        $key,
                        $deletedModel,
                    ));
                }
                fclose($file);
                exit;

                return redirect()->route('supplier-inventories.create')->with('message','supplier updated successfully');
            }
        }
    }
}
