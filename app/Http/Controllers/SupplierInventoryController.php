<?php

namespace App\Http\Controllers;

use App\Models\ColorCode;
use App\Models\MasterModel;
use App\Models\SupplierInventory;
use Barryvdh\DomPDF\Facade\PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class SupplierInventoryController extends Controller
{
    public function index()
    {
        $supplierInventories = SupplierInventory::with('masterModel')
            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
            ->groupBy('master_model_id')
            ->get();

        return view('supplier_inventories.index', compact('supplierInventories'));
    }
    public function create()
    {
//        $deletedRows = [];
//        $newlyAddedRows = [];
//        $updatedRows = [];
        return view('supplier_inventories.edit');
//            ,compact('deletedRows','newlyAddedRows','updatedRows')

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
            $code_nameex = NULL;
            $exteriorColorCodeId = NULL;
            $date = Carbon::now()->format('d-m-Y');
            info($date);
            while (($filedata = fgetcsv($file, 5000, ",")) !== FALSE) {
                $num = count($filedata);
                if ($i > 0 && $num == $numberOfFields)
                {
                    $supplier_id = $request->input('supplier');
                    $whole_sales = $request->input('whole_sales');
                    $country = $request->input('country');

                    $colourcode = $filedata[4];
                    if($colourcode) {
                        $colourcodecount = strlen($colourcode);

                        if ($colourcodecount == 5) {
                            $extcolour = substr($colourcode, 0, 3);
                        }
                        if ($colourcodecount == 4) {
                            $altercolourcode = "0" . $colourcode;
                            $extcolour = substr($altercolourcode, 0, 3);
                        }
                        if($extcolour) {
                            $parentColors = DB::table('color_codes')
                                ->select('parent','id')
                                ->where('code','=', $extcolour)
                                ->where('status','=',ColorCode::EXTERIOR)
                                ->get();

                            foreach ($parentColors as $row)
                            {
                                $code_nameex = $row->parent;
                                $exteriorColorCodeId = $row->id;
                            }
                        }
                    }


                    $colourname = $code_nameex;
                    $uploadFileContents[$i]['model'] = $filedata[0];
                    $uploadFileContents[$i]['sfx'] = $filedata[1];
                    $uploadFileContents[$i]['chasis'] = !empty($filedata[2]) ? $filedata[2] : NULL;
                    $uploadFileContents[$i]['engine_number'] = $filedata[3];
                    $uploadFileContents[$i]['color_code'] = $filedata[4];
                    $uploadFileContents[$i]['color_name'] = $colourname;
                    $uploadFileContents[$i]['exterior_color_code_id'] = $exteriorColorCodeId;
                    $uploadFileContents[$i]['pord_month'] = $filedata[5];
                    $uploadFileContents[$i]['po_arm'] = $filedata[6];
                    $uploadFileContents[$i]['status'] = $filedata[7];
                    if (!empty($filedata[8])) {
                        $filedata[8] = Carbon::createFromFormat('d/m/Y', $filedata[8])->format('Y-m-d');
                    }
                    $uploadFileContents[$i]['eta_import'] = $filedata[8];
                    $uploadFileContents[$i]['supplier'] = $supplier_id;
                    $uploadFileContents[$i]['whole_sales'] = $whole_sales;
                    $uploadFileContents[$i]['country'] = $country;
                    $uploadFileContents[$i]['date_of_entry'] = $date;
                    $uploadFileContents[$i]['veh_status'] = SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY;
                }
                $i++;
            }
            fclose($file);

            $newModels = [];
            $j=0;
            foreach($uploadFileContents as $uploadFileContent){
                $isModelExist = MasterModel::where('model',$uploadFileContent['model'])
                                            ->where('sfx', $uploadFileContent['sfx'])
                                            ->first();
                if (!$isModelExist)
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
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=$filename",
                    "Content-Description: File Transfer"
                );

                $columns = array('Model','SFX');
                $callback = function() use ($newModels, $columns)
                {
                    Session::flash('message', 'Download successful');
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

                return  response()->stream($callback, 200, $headers);
                // show error msg
//                return redirect()->route('supplier-inventories.create')->with('message','Please add new models to master table.');
            } else
            {

                $csvModels = [];
                //comment this or delete this csvModels
                $deletedRows = [];
                $i = 0;
                $count = 0;
                $countblankchasis = [];
                //use the other variable
                $newlyAddedRows = [];
                $updatedRows = [];
                foreach ($uploadFileContents as $uploadFileContent) {
                    $model = MasterModel::where('model', $uploadFileContent['model'])
                        ->where('sfx', $uploadFileContent['sfx'])
                        ->first();
                    $modelId = $model->id;
                    $csvModels[] = $modelId;
                    $supplierInventories = SupplierInventory::where('master_model_id', $modelId)
                        ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                        ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE);

//                      $uniqueCsvModels = array_map("unserialize", array_unique(array_map("serialize", $csvModels)));
////                    foreach ($uniqueCsvModels as $csvModel) {
//                        info("inside forloop");
//                        $groupedModelValues =  array_count_values($csvModels);
//                        // get each pair count in excel
//                        $modelOccuranceCount = $groupedModelValues[$modelId];
//                        // get each pair in database
//                        $modelDbCount = $supplierInventories->count();
//
//                        if ($modelOccuranceCount < $modelDbCount) {
//                            // add to deleted model
//
//                        }
////                    }


                    if ($supplierInventories->count() <= 0) {
                        info("new entry");
                        // model and sfx not existing in Suplr Invtry => new row
                        $newlyAddedRows[$i]['model'] = $uploadFileContent['model'];
                        $newlyAddedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                        $newlyAddedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                        $newlyAddedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                        $newlyAddedRows[$i]['color_code'] = $uploadFileContent['color_code'];

                    } else {
                        if (!empty($uploadFileContent['chasis'])) {

                            // Store the Count into Update the Row with data
                            $supplierInventory = $supplierInventories->where('chasis', $uploadFileContent['chasis'])
                                ->first();

                            $isNullChaisis = SupplierInventory::where('master_model_id', $modelId)
                                ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                                ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                                ->whereNull('chasis')
                                ->first();

                            if (!$supplierInventory) {
                                //adding new row simply
                                if (!empty($isNullChaisis)) {
                                    info("null chasis are updated with new chasis number");
                                    // null chaisis existing => updating row

                                    $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                    $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                    $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                    $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                    $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];

                                } else {
                                    // new chaisis with existing model and sfx => add row ,
                                    $newlyAddedRows[$i]['model'] = $uploadFileContent['model'];
                                    $newlyAddedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                    $newlyAddedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                    $newlyAddedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                    $newlyAddedRows[$i]['color_code'] = $uploadFileContent['color_code'];

                                }
                            } else
                            {
                                info("not found corresponding chasis");
                               $supplierInventory = $supplierInventories->where('engine_number', $uploadFileContent['engine_number'])
                                                    ->first();
                               if (!$supplierInventory)
                               {
                                info("no engine_number matching update row");
                                $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];

                               }else
                               {
                                 $supplierInventory = $supplierInventories->where('color_code', $uploadFileContent['color_code'])
                                                        ->first();
                                 if (!$supplierInventory)
                                 {
                                     info("no clr code matching update row");
                                     $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                     $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                     $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                     $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                     $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];

                                 }else
                                 {
                                     $supplierInventory = $supplierInventories->where('pord_month', $uploadFileContent['pord_month'])
                                         ->first();
                                     if (!$supplierInventory)
                                     {
                                         info("no clr code matching update row");
                                         $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                         $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                         $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                         $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                         $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];
                                     }else{

                                         $supplierInventory = $supplierInventories->where('po_arm', $uploadFileContent['po_arm'])
                                                                ->first();
                                         if (!$supplierInventory) {
                                             info("no po arm matching update row");
                                             $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                             $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                             $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                             $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                             $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];
                                         }else{
                                             $supplierInventory = $supplierInventories->whereDate('eta_import', $uploadFileContent['eta_import'])
                                                                    ->first();
                                             if (!$supplierInventory) {
                                                 info("no eta import matching update row");
                                                 $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                                 $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                                 $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                                 $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                                 $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];
                                             }else{
                                                 $supplierInventory = $supplierInventories->where('status', $uploadFileContent['status'])
                                                                        ->first();
                                                 if (!$supplierInventory) {
                                                     info("no status matching update row");
                                                     $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                                     $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                                     $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                                     $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                                     $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];
                                                 }
                                             }
                                         }
                                     }
                                 }

                               }
                            }
                        } else {
                            info("coming chasis null");
                            $nullChaisisCount = SupplierInventory::where('master_model_id', $modelId)
                                ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                                ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                                ->whereNull('chasis')
                                ->count();
                            $modelSfxValuePair = $uploadFileContent['model']."_".$uploadFileContent['sfx'];
                            $countblankchasis[] = $modelSfxValuePair;
                            $groupedCountValue =  array_count_values($countblankchasis);
                            if($groupedCountValue[$modelSfxValuePair] > $nullChaisisCount)
                            {
                                $newlyAddedRows[$i]['model'] = $uploadFileContent['model'];
                                $newlyAddedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                $newlyAddedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                $newlyAddedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                $newlyAddedRows[$i]['color_code'] = $uploadFileContent['color_code'];

                            }else
                            {
                                $supplierInventory = $supplierInventories->whereNull('chasis')
                                    ->where('engine_number', $uploadFileContent['engine_number'])
                                    ->first();
                                if (!$supplierInventory)
                                {
                                    info("no clr matching update row");
                                    $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                    $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                    $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                    $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                    $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];

                                }else {
                                    $supplierInventory = $supplierInventories->whereNull('chasis')
                                        ->where('color_code', $uploadFileContent['color_code'])
                                        ->first();
                                    if (!$supplierInventory) {
                                        $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                        $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                        $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                        $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                        $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];

                                    } else {
                                        $supplierInventory = $supplierInventories->whereNull('chasis')
                                            ->where('pord_month', $uploadFileContent['pord_month'])
                                            ->first();
                                        if (!$supplierInventory) {
                                            $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                            $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                            $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                            $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                            $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];

                                        }else{
                                            $supplierInventory = $supplierInventories->whereNull('chasis')
                                                ->where('po_arm', $uploadFileContent['po_arm'])
                                                ->first();
                                            if (!$supplierInventory) {
                                                $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                                $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                                $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                                $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                                $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];

                                            }else{
                                                $supplierInventory = $supplierInventories->whereNull('chasis')
                                                    ->whereDate('eta_import', $uploadFileContent['eta_import'])
                                                    ->first();
                                                if (!$supplierInventory) {
                                                    $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                                    $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                                    $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                                    $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                                    $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];
                                                }else{
                                                    $supplierInventory = $supplierInventories->whereNull('chasis')
                                                        ->where('status', $uploadFileContent['status'])
                                                        ->first();
                                                    if (!$supplierInventory) {
                                                        $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                                        $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                                        $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                                        $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                                        $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];
                                                    }
                                                }
                                            }
                                        }

                                    }
                                }

                            }
                            //look after
                        }
                    }
                    $i++;
                }

                    $inventories = SupplierInventory::where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                        ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                        ->get();

                    if ($inventories->count() > 0) {
                        info($csvModels);
                        $deletedModels = SupplierInventory::whereNotIn('master_model_id', $csvModels)
                            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                            ->groupBY('master_model_id')
                            ->pluck('master_model_id');

//                        return $deletedModels;

                        info($deletedModels);
                        info("deleted models");

                        $deletedRows = SupplierInventory::whereIn('master_model_id', $deletedModels)
                            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                            ->get();

//                        foreach ($deletedDatas as $deletedRow)
//                        {
//                            $deletedRows['model'] = $deletedRow->masterModel->model ?? '';
//                            $deletedRows['sfx'] = $deletedRow->masterModel->sfx ?? '';
//                            $deletedRows['chasis'] = $deletedRow->chasis;
//                            $deletedRows['engine_number'] = $deletedRow->engine_number;
//                            $deletedRows['color_code'] = $deletedRow->color_code;
//                        }
//                        info($deletedRows);
                    }


//                    $deletedSupplierInventoriesIds = $deletedSupplierInventories->pluck('id');

//                    foreach ($deletedSupplierInventories as $deletedSupplierInventory)
//                    {
////                        $supplierInventory = SupplierInventory::find($deletedSupplierInventories->id);
//                        $deletedSupplierInventory->veh_status = SupplierInventory::VEH_STATUS_DELETED;
//                        $deletedSupplierInventory->save();
//                    }

                $preivousDatas = SupplierInventory::where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)->get();
                foreach ($preivousDatas as $preivousData)
                {
                    $preivousData->upload_status = SupplierInventory::UPLOAD_STATUS_INACTIVE;
                    $preivousData->save();
                }
                foreach ($uploadFileContents as $uploadFileContent) {
                    $model = MasterModel::where('model', $uploadFileContent['model'])
                        ->where('sfx', $uploadFileContent['sfx'])
                        ->first();

                    $supplierInventory = new SupplierInventory();
                    $supplierInventory->master_model_id = $model->id;
                    $supplierInventory->chasis          = $uploadFileContent['chasis'];
                    $supplierInventory->engine_number   = $uploadFileContent['engine_number'];
                    $supplierInventory->color_code      = $uploadFileContent['color_code'];
                    $supplierInventory->exterior_color_code_id = $uploadFileContent['exterior_color_code_id'];
                    $supplierInventory->pord_month      = $uploadFileContent['pord_month'];
                    $supplierInventory->po_arm          = $uploadFileContent['po_arm'];
                    $supplierInventory->eta_import      = $uploadFileContent['eta_import'];
                    $supplierInventory->supplier        = $uploadFileContent['supplier'];
                    $supplierInventory->whole_sales	    = $uploadFileContent['whole_sales'];
                    $supplierInventory->country     	= $uploadFileContent['country'];
                    $supplierInventory->status      	= $uploadFileContent['status'];
                    $supplierInventory->date_of_entry   = Carbon::now()->format('d-m-Y');
                    $supplierInventory->upload_status   = SupplierInventory::UPLOAD_STATUS_ACTIVE;
                    $supplierInventory->veh_status      = SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY;
                    $supplierInventory->save();
                }
                  $pdf = PDF::loadView('supplier_inventories.reports', compact('newlyAddedRows','updatedRows','deletedRows'));
                  return $pdf->download('report.pdf');



//                    $filename = 'DeletedModels_Models_'.date('Y_m_d').'.csv';
//                    header("Content-Description: File Transfer");
//                    header("Content-Disposition: attachment; filename=$filename");
//                    header("Content-Type: application/csv;");
//
//                    $file = fopen('php://output', 'w');
//                    $columns = array("Model","SFX","Chaisis","Engine No.","Color code","PROD Month","PO AMS","STATUS","ETA Import");
//                    fputcsv($file, $columns);
//                    foreach($deletedSupplierInventories as $deletedSupplierInventories) {
//                        fputcsv($file, array(
//                            $deletedSupplierInventories->masterModel->model,
//                            $deletedSupplierInventories->masterModel->sfx,
//                            $deletedSupplierInventories->chasis,
//                            $deletedSupplierInventories->engine_number,
//                            $deletedSupplierInventories->color_code,
//                            $deletedSupplierInventories->pord_month,
//                            $deletedSupplierInventories->po_arm,
//                            $deletedSupplierInventories->status,
//                            $deletedSupplierInventories->eta_import,
//                        ));
//                    }
//                    fclose($file);
//                    exit;
                }
                return redirect()->route('supplier-inventories.create')->with('message','supplier inventory updated successfully');
//            }
        }
    }
}
