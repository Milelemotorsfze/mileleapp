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
        return view('supplier_inventories.edit');

    }
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:102400',
            'supplier' => 'required',
            'whole_sales' => 'required'

        ]);


        if ($request->file('file')) {
            info("file is getting");
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
            $date = Carbon::now()->format('Y-m-d');
            while (($filedata = fgetcsv($file, 5000, ",")) !== FALSE) {
                $num = count($filedata);
                if ($i > 0 && $num == $numberOfFields)
                {
                    $supplier_id = $request->input('supplier');
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
                    $uploadFileContents[$i]['steering'] = $filedata[0];
                    $uploadFileContents[$i]['model'] = $filedata[1];
                    $uploadFileContents[$i]['sfx'] = $filedata[2];
                    $uploadFileContents[$i]['chasis'] = !empty($filedata[3]) ? $filedata[3] : NULL;
                    $uploadFileContents[$i]['engine_number'] = $filedata[4];
                    $uploadFileContents[$i]['color_code'] = $filedata[5];
                    $uploadFileContents[$i]['color_name'] = $colourname;
                    $uploadFileContents[$i]['exterior_color_code_id'] = $exteriorColorCodeId;
                    $uploadFileContents[$i]['pord_month'] = $filedata[6];
                    $uploadFileContents[$i]['po_arm'] = $filedata[7];
                    if (!empty($filedata[7])) {
                        $filedata[7] = Carbon::createFromFormat('d/m/Y', $filedata[7])->format('Y-m-d');
                    }
                    $uploadFileContents[$i]['eta_import'] = $filedata[8];
                    $uploadFileContents[$i]['supplier'] = $supplier_id;
                    $uploadFileContents[$i]['whole_sales'] = $request->whole_sales;
                    $uploadFileContents[$i]['country'] = $country;
                    $uploadFileContents[$i]['date_of_entry'] = $date;
                    $uploadFileContents[$i]['veh_status'] = SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY;
                }
                $i++;
            }
            fclose($file);

            $newModels = [];
            $newModelsWithSteering = [];
            $j=0;
            foreach($uploadFileContents as $uploadFileContent){
                $isModelExist = MasterModel::where('model',$uploadFileContent['model'])
                                            ->where('sfx', $uploadFileContent['sfx'])
                                            ->first();
                $isModelWithSteeringExist = MasterModel::where('model',$uploadFileContent['model'])
                    ->where('sfx', $uploadFileContent['sfx'])
                    ->where('steering', $uploadFileContent['steering'])
                    ->first();
                if(!$isModelWithSteeringExist)
                {
                    $newModelsWithSteering[$j]['steering'] = $uploadFileContent['steering'];
                    $newModelsWithSteering[$j]['model'] = $uploadFileContent['model'];
                    $newModelsWithSteering[$j]['sfx'] = $uploadFileContent['sfx'];
                }
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
                if(!$request->has('is_add_new'))
                {
                    info("not checked");
                    $deletedRows = [];
                    $i = 0;
                    $countblankchasis = [];
                    $newlyAddedRows = [];
                    $updatedRows = [];
                    $updatedRowsIds = [];
                    foreach ($uploadFileContents as $uploadFileContent) {
                        $model = MasterModel::where('model', $uploadFileContent['model'])
                            ->where('sfx', $uploadFileContent['sfx'])
                            ->first();
                        $modelId = $model->id;
                        $supplierInventories = SupplierInventory::where('master_model_id', $modelId)
                            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                            ->where('supplier', $request->supplier)
                            ->where('whole_sales', $request->whole_sales);
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
                                        // null chaisis existing => updating row
                                        $updatedRowsIds[] = $isNullChaisis->id;
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
                                    $supplierInventoryOne = $supplierInventories->where('engine_number', $uploadFileContent['engine_number'])->first();
                                    if (!$supplierInventoryOne)
                                    {
                                        // chasis existing our system so get corresponding inventory when engine number is not matching
                                        $updatedRowsIds[] = $supplierInventory->id;
                                        $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                        $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                        $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                        $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                        $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];

                                    }else
                                    {
                                        $supplierInventoryTwo = $supplierInventories->where('color_code', $uploadFileContent['color_code'])->first();
                                        if (!$supplierInventoryTwo)
                                        {
                                            $updatedRowsIds[] = $supplierInventoryOne->id;
                                            $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                            $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                            $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                            $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                            $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];
                                        }else
                                        {
                                            $supplierInventoryThree = $supplierInventories->where('pord_month', $uploadFileContent['pord_month'])->first();
                                            if (!$supplierInventoryThree)
                                            {
                                                info($supplierInventoryTwo);
                                                info("no clr code not matching update row");
                                                $updatedRowsIds[] = $supplierInventoryTwo->id;
                                                $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                                $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                                $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                                $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                                $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];
                                            }else{

                                                $supplierInventoryFour = $supplierInventories->where('po_arm', $uploadFileContent['po_arm'])->first();
                                                if (!$supplierInventoryFour) {
//                                             info("no po arm matching update row");
                                                    $updatedRowsIds[] = $supplierInventoryThree->id;
                                                    $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                                    $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                                    $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                                    $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                                    $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];
                                                }else{
                                                    $supplierInventoryFive = $supplierInventories->whereDate('eta_import', $uploadFileContent['eta_import'])->first();
                                                    if (!$supplierInventoryFive) {
//                                                 info("no eta import matching update row");
                                                        $updatedRowsIds[] = $supplierInventoryFour->id;
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
                            } else
                            {
                                $nullChaisisCount = SupplierInventory::where('master_model_id', $modelId)
                                    ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                                    ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                                    ->where('supplier', $request->supplier)
                                    ->where('whole_sales', $request->whole_sales)
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
                                    $supplierInventory = $supplierInventories->whereNull('chasis')->first();
                                    $supplierInventory1 = $supplierInventories->whereNull('chasis')
                                        ->where('engine_number', $uploadFileContent['engine_number'])
                                        ->first();
                                    if (!$supplierInventory1)
                                    {
                                        $updatedRowsIds[] = $supplierInventory->id;
                                        $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                        $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                        $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                        $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                        $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];

                                    }else
                                    {
                                        $supplierInventory2 = $supplierInventories->whereNull('chasis')
                                            ->where('color_code', $uploadFileContent['color_code'])
                                            ->first();
                                        if (!$supplierInventory2)
                                        {
                                            $updatedRowsIds[] = $supplierInventory1->id;
                                            $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                            $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                            $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                            $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                            $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];

                                        } else {
                                            $supplierInventory3 = $supplierInventories->whereNull('chasis')
                                                ->where('pord_month', $uploadFileContent['pord_month'])
                                                ->first();
                                            if (!$supplierInventory3)
                                            {
                                                $updatedRowsIds[] = $supplierInventory2->id;
                                                $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                                $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                                $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                                $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                                $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];

                                            }else{
                                                $supplierInventory4 = $supplierInventories->whereNull('chasis')
                                                    ->where('po_arm', $uploadFileContent['po_arm'])
                                                    ->first();
                                                if (!$supplierInventory4)
                                                {
                                                    $updatedRowsIds[] = $supplierInventory4->id;
                                                    $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                                    $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                                    $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                                    $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                                    $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];

                                                }else{
                                                    $supplierInventory5 = $supplierInventories->whereNull('chasis')
                                                        ->whereDate('eta_import', $uploadFileContent['eta_import'])
                                                        ->first();
                                                    if (!$supplierInventory5)
                                                    {
                                                        $updatedRowsIds[] = $supplierInventory4->id;
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
                        }$i++;
                    }
                    $supplierInventories = SupplierInventory::where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                        ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                        ->where('supplier', $request->supplier)
                        ->where('whole_sales', $request->whole_sales)
                        ->get();

                    $totalExcelEntries = count($uploadFileContents);
                    if ($supplierInventories->count() > $totalExcelEntries )
                    {
                        $excelValuePair = [];
                        $excelRows = [];
                        foreach ($uploadFileContents as $uploadFileContent)
                        {
                            $model = MasterModel::where('model', $uploadFileContent['model'])
                                ->where('sfx', $uploadFileContent['sfx'])
                                ->first();
                            $isExistSupplier = SupplierInventory::where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                                ->where('supplier', $request->supplier)
                                ->where('whole_sales', $request->whole_sales)
                                ->where('master_model_id', $model->id)
                                ->where('chasis', $uploadFileContent['chasis'])
                                ->where('engine_number', $uploadFileContent['engine_number'])
                                ->where('color_code', $uploadFileContent['color_code'])
                                ->where('pord_month', $uploadFileContent['pord_month'])
                                ->where('po_arm', $uploadFileContent['po_arm'])
                                ->whereDate('eta_import', $uploadFileContent['eta_import'])
                                ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE);
                            if ($isExistSupplier->count() > 0)
                            {
                                info("row exist");
                                if ($isExistSupplier->count() > 1) {
                                    info("multiple row found");
                                    info("dbcount => ".$isExistSupplier->count());
                                    $dbRowCount = $isExistSupplier->count();
                                    $csvValuePair = $uploadFileContent['model']."_".$uploadFileContent['sfx']."_".$uploadFileContent['chasis']."_".
                                        $uploadFileContent['engine_number']."_".$uploadFileContent['color_code']."_".$uploadFileContent['pord_month']."_".
                                        $uploadFileContent['po_arm'];
                                    $excelValuePair[] = $csvValuePair;
                                    $groupedExcelCountValue =  array_count_values($excelValuePair);
                                    if ($groupedExcelCountValue[$csvValuePair] < $dbRowCount)
                                    {
                                        info("founded rows deleted");
                                        $ExcelExistingRowId = $isExistSupplier->take($groupedExcelCountValue[$csvValuePair])->pluck('id');
                                        info("row id of existing in excel".$ExcelExistingRowId);
                                        foreach ($ExcelExistingRowId as $ExcelExistingRowId) {
                                            $excelRows[] = $ExcelExistingRowId;
                                        }
                                    }
                                }else{
                                    info("data exist with 1 row");
                                    info($isExistSupplier->first());
                                    $supplierInventory = $isExistSupplier->first();
                                    $excelRows[] = $supplierInventory->id;
                                }
                                foreach ($updatedRowsIds as $updatedRowsId) {
                                    $excelRows[] = $updatedRowsId;
                                }
                                info($excelRows);
                                info("row found in excel");
                            }
                        }
                        $excelRows =  array_map("unserialize", array_unique(array_map("serialize", $excelRows)));
                        $deletedRows = SupplierInventory::where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                            ->where('supplier', $request->supplier)
                            ->where('whole_sales', $request->whole_sales)
                            ->whereNotIn('id', $excelRows)
                            ->get();
                    }
                    $preivousDatas = SupplierInventory::where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                        ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                        ->where('supplier', $request->supplier)
                        ->where('whole_sales', $request->whole_sales)
                        ->get();
                    if ($preivousDatas->count() > 0) {
                        foreach ($preivousDatas as $preivousData)
                        {
                            $preivousData->upload_status = SupplierInventory::UPLOAD_STATUS_INACTIVE;
                            $preivousData->save();
                        }
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
                        $supplierInventory->is_add_new     	= !empty($request->is_add_new) ? true : false;
                        $supplierInventory->supplier        = $uploadFileContent['supplier'];
                        $supplierInventory->whole_sales	    = $uploadFileContent['whole_sales'];
                        $supplierInventory->country     	= $uploadFileContent['country'];
                        $supplierInventory->date_of_entry   = Carbon::now()->format('Y-m-d');
                        $supplierInventory->upload_status   = SupplierInventory::UPLOAD_STATUS_ACTIVE;
                        $supplierInventory->veh_status      = SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY;
                        $supplierInventory->save();
                    }

                    $pdf = PDF::loadView('supplier_inventories.reports', compact('newlyAddedRows',
                        'updatedRows','deletedRows'));
                    return $pdf->download('report.pdf');

                }else{
                    info(" checked");
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
                        $supplierInventory->is_add_new     	= !empty($request->is_add_new) ? true : false;
                        $supplierInventory->supplier        = $uploadFileContent['supplier'];
                        $supplierInventory->whole_sales	    = $uploadFileContent['whole_sales'];
                        $supplierInventory->country     	= $uploadFileContent['country'];
                        $supplierInventory->date_of_entry   = Carbon::now()->format('Y-m-d');
                        $supplierInventory->upload_status   = SupplierInventory::UPLOAD_STATUS_ACTIVE;
                        $supplierInventory->veh_status      = SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY;
                        $supplierInventory->save();
                    }
                }
            }
                return redirect()->route('supplier-inventories.create')->with('message','supplier inventory updated successfully');
        }
    }
}
