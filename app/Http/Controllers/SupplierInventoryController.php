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
            ->whereNull('eta_import')
            ->groupBy('master_model_id')
            ->orderBy('id','desc')
            ->get();

        return view('supplier_inventories.index', compact('supplierInventories'));
    }
    public function create()
    {
//        $newModels = [];
//        $newModelsWithSteerings = [];
//        return view('supplier_inventories.new_models',compact('newModelsWithSteerings','newModels'));
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
            $extcolour = NULL;
            $date = '2023-05-04';
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
                    if (!empty($filedata[8])) {
                        $filedata[8] = Carbon::createFromFormat('d/m/Y', $filedata[8])->format('Y-m-d');
                    }else {
                        $filedata[8] = NULL;
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
            $newModelsWithSteerings = [];
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
                    $newModelsWithSteerings[$j]['steering'] = $uploadFileContent['steering'];
                    $newModelsWithSteerings[$j]['model'] = $uploadFileContent['model'];
                    $newModelsWithSteerings[$j]['sfx'] = $uploadFileContent['sfx'];
                }
                if (!$isModelExist)
                {
                    $newModels[$j]['model'] = $uploadFileContent['model'];
                    $newModels[$j]['sfx'] = $uploadFileContent['sfx'];
                }
                $j++;
            }
            $newModelsWithSteerings = array_map("unserialize", array_unique(array_map("serialize", $newModelsWithSteerings)));
            $newModels = array_map("unserialize", array_unique(array_map("serialize", $newModels)));
            if(count($newModels) > 0 || count($newModelsWithSteerings) > 0)
            {
                $pdf = PDF::loadView('supplier_inventories.new_models', compact('newModels', 'newModelsWithSteerings'));
                return $pdf->download('New_Models_'.date('Y_m_d').'.pdf');
//                return  response()->stream($callback, 200, $headers);
                // show error msg
//                return redirect()->route('supplier-inventories.create')->with('message','Please add new models to master table.');
            } else
            {
                if(!$request->has('is_add_new'))
                {
                    info("not checked");
//                    $deletedRows = [];
                    $i = 0;
                    $countblankchasis = [];
                    $newlyAddedRows = [];
                    $updatedRows = [];
                    $updatedRowsIds = [];
                    $excelValuePair = [];
                    $chasisUpdatedRowIds = [];
                    foreach ($uploadFileContents as $uploadFileContent) {
                        $csvValuePair = $uploadFileContent['model'] . "_" . $uploadFileContent['sfx'] . "_" . $uploadFileContent['chasis'] . "_" .
                            $uploadFileContent['engine_number'] . "_" . $uploadFileContent['color_code'] . "_" . $uploadFileContent['pord_month'] . "_" .
                            $uploadFileContent['po_arm'];
                        $excelValuePair[] = $csvValuePair;
                    }
                    foreach ($uploadFileContents as $uploadFileContent)
                    {
                        $model = MasterModel::where('model', $uploadFileContent['model'])
                            ->where('sfx', $uploadFileContent['sfx'])
                            ->where('steering', $uploadFileContent['steering'])
                            ->first();
                        $modelId = $model->id;
                        $supplierInventories = SupplierInventory::where('master_model_id', $modelId)
                            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                            ->where('supplier', $request->supplier)
                            ->where('whole_sales', $request->whole_sales);
//                            ->whereNull('eta_import');

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
                                info("chais not empty");
                                // Store the Count into Update the Row with data
                                $supplierInventory = $supplierInventories->where('chasis', $uploadFileContent['chasis'])
                                    ->first();
                                $isNullChaisis = SupplierInventory::where('master_model_id', $modelId)
                                    ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                                    ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
//                                    ->whereNull('eta_import')
                                    ->whereNull('chasis');
                                if (!$supplierInventory) {
                                    //adding new row simply
                                    if (!empty($isNullChaisis->first())) {
                                        // null chaisis existing => updating row
                                        $chasisUpdatedRow = $isNullChaisis->whereNotIn('id',$chasisUpdatedRowIds)->first();
                                        info("empty chais with same model and sfx existing => update that row".$chasisUpdatedRow->id);
                                        $chasisUpdatedRowIds[] = $chasisUpdatedRow->id;
                                        $isNullChaisis = $isNullChaisis->first();
                                        $updatedRowsIds[] = $isNullChaisis->id;
                                        $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                        $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                        $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                        $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                        $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];
                                    } else {
                                        info("new chasis with existing model and sfx => new entry".$uploadFileContent['sfx']);
                                        // new chaisis with existing model and sfx => add row ,
                                        $newlyAddedRows[$i]['model'] = $uploadFileContent['model'];
                                        $newlyAddedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                        $newlyAddedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                        $newlyAddedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                        $newlyAddedRows[$i]['color_code'] = $uploadFileContent['color_code'];
                                    }
                                } else
                                {
                                    info(" chais existing then checking any field have updattion");
                                    $supplierInventoryOne = $supplierInventories->where('engine_number', $uploadFileContent['engine_number'])->first();
                                    if (!$supplierInventoryOne)
                                    {
                                        info("engine number not matching => update done".$supplierInventory->id);
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
                                            info("engine number not matching => update done".$supplierInventory->id);

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
                                             info("no po arm matching update row");
                                                    $updatedRowsIds[] = $supplierInventoryThree->id;
                                                    $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                                    $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                                    $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                                    $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                                    $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];
                                                }else{
                                                    info("eta import value".$uploadFileContent['eta_import']);
                                                    if(!empty($uploadFileContent['eta_import'])) {
                                                        $supplierInventoryFive = $supplierInventories->whereDate('eta_import', $uploadFileContent['eta_import'])->first();
                                                        if (!$supplierInventoryFive) {
                                                            info("no eta import matching update row");
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
                                }
                            } else
                            {
                                info("CHASIS updated rows");
                                info($chasisUpdatedRowIds);
                                $nullChaisisCount = SupplierInventory::where('master_model_id', $modelId)
                                    ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                                    ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                                    ->where('supplier', $request->supplier)
                                    ->where('whole_sales', $request->whole_sales)
                                    ->whereNotIn('id', $chasisUpdatedRowIds)
                                    ->whereNull('chasis')
//                                    ->whereNull('eta_import')
                                    ->count();
                                $modelSfxValuePair = $uploadFileContent['model']."_".$uploadFileContent['sfx'];
                                $countblankchasis[] = $modelSfxValuePair;
                                info("model value pair".$modelSfxValuePair);
                                $groupedCountValue =  array_count_values($countblankchasis);
                                info("excel count".$groupedCountValue[$modelSfxValuePair]);
                                info("db count".$nullChaisisCount);
                                if ($groupedCountValue[$modelSfxValuePair] > $nullChaisisCount)
                                {
                                    info("newly add is there");
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
//                                        info("engine number not match update");
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
                                            info("clr code not match update");

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
                                                info("chasis not match update");

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
                                                    info("po arm not match update");

                                                    $updatedRowsIds[] = $supplierInventory3->id;
                                                    $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                                    $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                                    $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                                    $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                                    $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];

                                                }else{
                                                    if (!empty($uploadFileContent['eta_import'])) {
                                                        $supplierInventory5 = $supplierInventories->whereNull('chasis')
                                                            ->whereDate('eta_import', $uploadFileContent['eta_import'])
                                                            ->first();
                                                        if (!$supplierInventory5)
                                                        {
                                                            info("eta import not match update");

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
                            }
                        }$i++;
                    }
                        // to find deleted rows
                        // group the value pair to get count of duplicate data
                        $groupedExcelCountValue =  array_count_values($excelValuePair);
                        $excelRows = [];
                        foreach ($uploadFileContents as $uploadFileContent)
                        {
                            $model = MasterModel::where('model', $uploadFileContent['model'])
                                ->where('sfx', $uploadFileContent['sfx'])
                                ->where('steering',$uploadFileContent['steering'])
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
//                                ->whereNull('eta_import')
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
                                    info("model value pair is".$csvValuePair);
                                    info("the excel value is");
                                    info($groupedExcelCountValue[$csvValuePair]);
                                    if ($groupedExcelCountValue[$csvValuePair] <= $dbRowCount)
                                    {
//                                        info("excel have only row count". $groupedExcelCountValue[$csvValuePair]);
                                        $ExcelExistingRowId = $isExistSupplier->orderBy('id','desc')->take($groupedExcelCountValue[$csvValuePair])->pluck('id');
//                                        info("row id of existing in excel".$ExcelExistingRowId);
                                        foreach ($ExcelExistingRowId as $ExcelExistingRowId) {
                                            $excelRows[] = $ExcelExistingRowId;
                                        }
                                    }else{
                                        $ExcelExistingRowId = $isExistSupplier->take($dbRowCount)->pluck('id');
//                                        info("row id of existing in excel".$ExcelExistingRowId);
                                        foreach ($ExcelExistingRowId as $ExcelExistingRowId) {
                                            $excelRows[] = $ExcelExistingRowId;
                                        }
                                    }
                                }else{
                                    $supplierInventory = $isExistSupplier->first();
                                    $excelRows[] = $supplierInventory->id;
                                }
                                foreach ($updatedRowsIds as $updatedRowsId) {
                                    $excelRows[] = $updatedRowsId;
                                }
                            }
                        }
                        $excelRows =  array_map("unserialize", array_unique(array_map("serialize", $excelRows)));
                        $deletedRows = SupplierInventory::where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                            ->where('supplier', $request->supplier)
                            ->where('whole_sales', $request->whole_sales)
                            ->whereNotIn('id', $excelRows)
                            ->whereNull('eta_import')
                            ->get();
                        foreach ($deletedRows as $deletedRow) {
                            $deletedRow->status = SupplierInventory::VEH_STATUS_DELETED;
                            $deletedRow->save();
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
                            ->where('steering', $uploadFileContent['steering'])
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
                        $supplierInventory->date_of_entry   = $date;
                        $supplierInventory->upload_status   = SupplierInventory::UPLOAD_STATUS_ACTIVE;
                        $supplierInventory->veh_status      = SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY;
                        $supplierInventory->save();
                    }

                    $pdf = PDF::loadView('supplier_inventories.reports', compact('newlyAddedRows',
                        'updatedRows','deletedRows'));
                    return $pdf->download('report.pdf');

                }else{
                    foreach ($uploadFileContents as $uploadFileContent)
                    {
                        $model = MasterModel::where('model', $uploadFileContent['model'])
                            ->where('sfx', $uploadFileContent['sfx'])
                            ->where('steering', $uploadFileContent['steering'])
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
                        $supplierInventory->date_of_entry   = $date;
                        $supplierInventory->upload_status   = SupplierInventory::UPLOAD_STATUS_ACTIVE;
                        $supplierInventory->veh_status      = SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY;
                        $supplierInventory->save();
                    }
                    return redirect()->route('supplier-inventories.create')->with('message','supplier inventory updated successfully');
                }
            }

        }
    }
    public function FileComparision(Request $request) {

        $supplierInventoryDates = SupplierInventory::groupBy('date_of_entry')->pluck('date_of_entry');
        $newlyAddedRows = [];
        $deletedRows = [];
        $updatedRows = [];

        return view('supplier_inventories.file_comparision',compact('supplierInventoryDates',
            'newlyAddedRows',
            'deletedRows','updatedRows'
        ));
    }
    public function FileComparisionReport(Request $request)
    {
        $supplierInventoryDates = SupplierInventory::groupBy('date_of_entry')->pluck('date_of_entry');

        $newlyAddedRows = [];
        $deletedRows = [];
        $updatedRows = [];
        $firstFileValuePairs = [];
        $chasisUpdatedRowIds = [];
        $updatedRowsIds = [];
        $i = 0;

        $firstFileRowDetails = SupplierInventory::whereDate('date_of_entry', $request->first_file)
            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
            ->where('supplier', $request->supplier)
            ->where('whole_sales', $request->whole_sales)
//            ->whereNull('eta_import')
            ->get();
        $secondFileRowDetails = SupplierInventory::whereDate('date_of_entry', $request->second_file)
            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
//            ->whereNull('eta_import')
            ->where('supplier', $request->supplier)
            ->where('whole_sales', $request->whole_sales)
            ->get();

        foreach ($secondFileRowDetails as $secondFileRowDetail)
        {
            $masterModel = MasterModel::find($secondFileRowDetail['master_model_id']);

            info($secondFileRowDetail['master_model_id']);
            info("master model id");
            $supplierInventories = SupplierInventory::whereDate('date_of_entry', $request->first_file)
                ->where('master_model_id', $secondFileRowDetail['master_model_id'])
                ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                ->where('supplier', $request->supplier)
                ->where('whole_sales', $request->whole_sales);
//                            ->whereNull('eta_import');

            info($supplierInventories->count());
            info("suppliers count");

            if ($supplierInventories->count() <= 0) {
                info("new entry");
                // model and sfx not existing in Suplr Invtry => new row
                $newlyAddedRows[$i]['model'] = $masterModel->model;
                $newlyAddedRows[$i]['sfx'] = $masterModel->sfx;
                $newlyAddedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                $newlyAddedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                $newlyAddedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];
                $newlyAddedRows[$i]['pord_month'] = $secondFileRowDetail['pord_month'];
                $newlyAddedRows[$i]['po_arm'] = $secondFileRowDetail['po_arm'];
                $newlyAddedRows[$i]['eta_import'] = $secondFileRowDetail['eta_import'];

            } else {
                if (!empty($secondFileRowDetail['chasis'])) {
                    info("chais not empty");
                    // Store the Count into Update the Row with data
                    $supplierInventory = $supplierInventories->where('chasis', $secondFileRowDetail['chasis'])
                        ->first();
                    $isNullChaisis = SupplierInventory::whereDate('date_of_entry', $request->first_file)
                        ->where('master_model_id', $secondFileRowDetail['master_model_id'])
                        ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
//                                    ->whereNull('eta_import')
                        ->where('supplier', $request->supplier)
                        ->where('whole_sales', $request->whole_sales)
                        ->whereNull('chasis');
                    if (!$supplierInventory) {
                        //adding new row simply
                        if (!empty($isNullChaisis->first())) {
                            // null chaisis existing => updating row
                            $chasisUpdatedRow = $isNullChaisis->whereNotIn('id',$chasisUpdatedRowIds)->first();
                            info("empty chais with same model and sfx existing => update that row".$chasisUpdatedRow->id);
                            $chasisUpdatedRowIds[] = $chasisUpdatedRow->id;
                            $isNullChaisis = $isNullChaisis->first();
                            $updatedRowsIds[] = $isNullChaisis->id;

                            $updatedRows[$i]['model'] = $masterModel->model;
                            $updatedRows[$i]['sfx'] = $masterModel->sfx;
                            $updatedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                            $updatedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                            $updatedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];
                            $updatedRows[$i]['pord_month'] = $secondFileRowDetail['pord_month'];
                            $updatedRows[$i]['po_arm'] = $secondFileRowDetail['po_arm'];
                            $updatedRows[$i]['eta_import'] = $secondFileRowDetail['eta_import'];
                        } else {
                            info("new chasis with existing model and sfx => new entry".$secondFileRowDetail['sfx']);
                            // new chaisis with existing model and sfx => add row ,
                            $newlyAddedRows[$i]['model'] = $masterModel->model;
                            $newlyAddedRows[$i]['sfx'] = $masterModel->sfx;
                            $newlyAddedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                            $newlyAddedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                            $newlyAddedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];
                        }
                    } else
                    {
                        info(" chais existing then checking any field have updattion");
                        $supplierInventoryOne = $supplierInventories->where('engine_number', $secondFileRowDetail['engine_number'])->first();
                        if (!$supplierInventoryOne)
                        {
                            info("engine number not matching => update done".$supplierInventory->id);
                            // chasis existing our system so get corresponding inventory when engine number is not matching
                            $updatedRowsIds[] = $supplierInventory->id;

                            $updatedRows[$i]['model'] = $masterModel->model;
                            $updatedRows[$i]['sfx'] = $masterModel->sfx;
                            $updatedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                            $updatedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                            $updatedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];
                            $updatedRows[$i]['pord_month'] = $secondFileRowDetail['pord_month'];
                            $updatedRows[$i]['po_arm'] = $secondFileRowDetail['po_arm'];
                            $updatedRows[$i]['eta_import'] = $secondFileRowDetail['eta_import'];

                        }else
                        {
                            $supplierInventoryTwo = $supplierInventories->where('color_code', $secondFileRowDetail['color_code'])->first();
                            if (!$supplierInventoryTwo)
                            {
                                info("engine number not matching => update done".$supplierInventory->id);

                                $updatedRowsIds[] = $supplierInventoryOne->id;
                                $updatedRows[$i]['model'] = $masterModel->model;
                                $updatedRows[$i]['sfx'] = $masterModel->sfx;
                                $updatedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                                $updatedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                                $updatedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];
                                $updatedRows[$i]['pord_month'] = $secondFileRowDetail['pord_month'];
                                $updatedRows[$i]['po_arm'] = $secondFileRowDetail['po_arm'];
                                $updatedRows[$i]['eta_import'] = $secondFileRowDetail['eta_import'];
                            }else
                            {
                                $supplierInventoryThree = $supplierInventories->where('pord_month', $secondFileRowDetail['pord_month'])->first();
                                if (!$supplierInventoryThree)
                                {
                                    info($supplierInventoryTwo);
                                    info("no clr code not matching update row");
                                    $updatedRowsIds[] = $supplierInventoryTwo->id;
                                    $updatedRows[$i]['model'] = $masterModel->model;
                                    $updatedRows[$i]['sfx'] = $masterModel->sfx;
                                    $updatedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                                    $updatedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                                    $updatedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];
                                    $updatedRows[$i]['pord_month'] = $secondFileRowDetail['pord_month'];
                                    $updatedRows[$i]['po_arm'] = $secondFileRowDetail['po_arm'];
                                    $updatedRows[$i]['eta_import'] = $secondFileRowDetail['eta_import'];
                                }else{

                                    $supplierInventoryFour = $supplierInventories->where('po_arm', $secondFileRowDetail['po_arm'])->first();
                                    if (!$supplierInventoryFour) {
                                        info("no po arm matching update row");
                                        $updatedRowsIds[] = $supplierInventoryThree->id;
                                        $updatedRows[$i]['model'] = $masterModel->model;
                                        $updatedRows[$i]['sfx'] = $masterModel->sfx;
                                        $updatedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                                        $updatedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                                        $updatedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];
                                        $updatedRows[$i]['pord_month'] = $secondFileRowDetail['pord_month'];
                                        $updatedRows[$i]['po_arm'] = $secondFileRowDetail['po_arm'];
                                        $updatedRows[$i]['eta_import'] = $secondFileRowDetail['eta_import'];
                                    }else{
                                        info("eta import value".$secondFileRowDetail['eta_import']);
                                        if(!empty($secondFileRowDetail['eta_import'])) {
                                            $supplierInventoryFive = $supplierInventories->whereDate('eta_import', $secondFileRowDetail['eta_import'])->first();
                                            if (!$supplierInventoryFive) {
                                                info("no eta import matching update row");
                                                $updatedRowsIds[] = $supplierInventoryFour->id;
                                                $updatedRows[$i]['model'] = $masterModel->model;
                                                $updatedRows[$i]['sfx'] = $masterModel->sfx;
                                                $updatedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                                                $updatedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                                                $updatedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];
                                                $updatedRows[$i]['pord_month'] = $secondFileRowDetail['pord_month'];
                                                $updatedRows[$i]['po_arm'] = $secondFileRowDetail['po_arm'];
                                                $updatedRows[$i]['eta_import'] = $secondFileRowDetail['eta_import'];
                                            }
                                        }

                                    }
                                }
                            }

                        }
                    }
                } else
                {
                    info("CHASIS updated rows");
                    info($chasisUpdatedRowIds);
                    $nullChaisisCount = SupplierInventory::whereDate('date_of_entry', $request->first_file)
                        ->where('master_model_id', $secondFileRowDetail['master_model_id'])
                        ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                        ->where('supplier', $request->supplier)
                        ->where('whole_sales', $request->whole_sales)
                        ->whereNotIn('id', $chasisUpdatedRowIds)
                        ->whereNull('chasis')
//                                    ->whereNull('eta_import')
                        ->count();
                    $modelSfxValuePair = $masterModel->model."_".$masterModel->sfx;
                    $countblankchasis[] = $modelSfxValuePair;
                    info("model value pair".$modelSfxValuePair);
                    $groupedCountValue =  array_count_values($countblankchasis);
                    info("excel count".$groupedCountValue[$modelSfxValuePair]);
                    info("db count".$nullChaisisCount);
                    if ($groupedCountValue[$modelSfxValuePair] > $nullChaisisCount)
                    {
                        info("newly add is there");
                        $newlyAddedRows[$i]['model'] = $masterModel->model;
                        $newlyAddedRows[$i]['sfx'] = $masterModel->sfx;
                        $newlyAddedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                        $newlyAddedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                        $newlyAddedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];

                    }else
                    {
                        $supplierInventory = $supplierInventories->whereNull('chasis')->first();
                        $supplierInventory1 = $supplierInventories->whereNull('chasis')
                            ->where('engine_number', $secondFileRowDetail['engine_number'])
                            ->first();
                        if (!$supplierInventory1)
                        {
//                                        info("engine number not match update");
                            $updatedRowsIds[] = $supplierInventory->id;
                            $updatedRows[$i]['model'] = $masterModel->model;
                            $updatedRows[$i]['sfx'] = $masterModel->sfx;
                            $updatedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                            $updatedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                            $updatedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];
                            $updatedRows[$i]['pord_month'] = $secondFileRowDetail['pord_month'];
                            $updatedRows[$i]['po_arm'] = $secondFileRowDetail['po_arm'];
                            $updatedRows[$i]['eta_import'] = $secondFileRowDetail['eta_import'];

                        }else
                        {
                            $supplierInventory2 = $supplierInventories->whereNull('chasis')
                                ->where('color_code', $secondFileRowDetail['color_code'])
                                ->first();
                            if (!$supplierInventory2)
                            {
                                info("clr code not match update");

                                $updatedRowsIds[] = $supplierInventory1->id;
                                $updatedRows[$i]['model'] = $masterModel->model;
                                $updatedRows[$i]['sfx'] = $masterModel->sfx;
                                $updatedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                                $updatedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                                $updatedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];
                                $updatedRows[$i]['pord_month'] = $secondFileRowDetail['pord_month'];
                                $updatedRows[$i]['po_arm'] = $secondFileRowDetail['po_arm'];
                                $updatedRows[$i]['eta_import'] = $secondFileRowDetail['eta_import'];

                            } else {
                                $supplierInventory3 = $supplierInventories->whereNull('chasis')
                                    ->where('pord_month', $secondFileRowDetail['pord_month'])
                                    ->first();
                                if (!$supplierInventory3)
                                {
                                    info("chasis not match update");

                                    $updatedRowsIds[] = $supplierInventory2->id;
                                    $updatedRows[$i]['model'] = $masterModel->model;
                                    $updatedRows[$i]['sfx'] = $masterModel->sfx;
                                    $updatedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                                    $updatedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                                    $updatedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];
                                    $updatedRows[$i]['pord_month'] = $secondFileRowDetail['pord_month'];
                                    $updatedRows[$i]['po_arm'] = $secondFileRowDetail['po_arm'];
                                    $updatedRows[$i]['eta_import'] = $secondFileRowDetail['eta_import'];

                                }else{
                                    $supplierInventory4 = $supplierInventories->whereNull('chasis')
                                        ->where('po_arm', $secondFileRowDetail['po_arm'])
                                        ->first();
                                    if (!$supplierInventory4)
                                    {
                                        info("po arm not match update");

                                        $updatedRowsIds[] = $supplierInventory3->id;
                                        $updatedRows[$i]['model'] = $masterModel->model;
                                        $updatedRows[$i]['sfx'] = $masterModel->sfx;
                                        $updatedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                                        $updatedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                                        $updatedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];
                                        $updatedRows[$i]['pord_month'] = $secondFileRowDetail['pord_month'];
                                        $updatedRows[$i]['po_arm'] = $secondFileRowDetail['po_arm'];
                                        $updatedRows[$i]['eta_import'] = $secondFileRowDetail['eta_import'];

                                    }else{
                                        if (!empty($secondFileRowDetail['eta_import'])) {
                                            $supplierInventory5 = $supplierInventories->whereNull('chasis')
                                                ->whereDate('eta_import', $secondFileRowDetail['eta_import'])
                                                ->first();
                                            if (!$supplierInventory5)
                                            {
                                                info("eta import not match update");
                                                $updatedRowsIds[] = $supplierInventory4->id;
                                                $updatedRows[$i]['model'] = $masterModel->model;
                                                $updatedRows[$i]['sfx'] = $masterModel->sfx;
                                                $updatedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                                                $updatedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                                                $updatedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];
                                                $updatedRows[$i]['pord_month'] = $secondFileRowDetail['pord_month'];
                                                $updatedRows[$i]['po_arm'] = $secondFileRowDetail['po_arm'];
                                                $updatedRows[$i]['eta_import'] = $secondFileRowDetail['eta_import'];
                                            }
                                        }

                                    }
                                }
                            }
                        }
                    }
                }
            }$i++;
        }

        foreach ($firstFileRowDetails as $firstFileRowDetail)
        {
            $masterModel = MasterModel::find($firstFileRowDetail['master_model_id']);
            $firstFileValuePairs[] =  $masterModel->model."_".$masterModel->sfx."_".$firstFileRowDetail['chasis']."_".
                $firstFileRowDetail['engine_number']."_".$firstFileRowDetail['color_code']."_".$firstFileRowDetail['pord_month']."_".
                $firstFileRowDetail['po_arm'];
        }
        // to find deleted rows
        // group the value pair to get count of duplicate data
        $groupedFirstFileCount =  array_count_values($firstFileValuePairs);
        $j=0;
        $updatedDetails = [];
        foreach ($updatedRowsIds as $updatedRowsId) {
            $supplierInventory = SupplierInventory::find($updatedRowsId);
            $updatedDetails[] =  $supplierInventory->masterModel->model."_".$supplierInventory->masterModel->sfx."_".$supplierInventory->chasis."_".
                $supplierInventory->engine_number."_".$supplierInventory->color_code."_".$supplierInventory->pord_month."_".
                $supplierInventory->po_arm;
        }
        $chasisUpdatedRows = [];
        if(!empty($chasisUpdatedRowIds)) {
            foreach ($chasisUpdatedRowIds as $chasisUpdatedRowId) {
                $supplierInventory = SupplierInventory::find($chasisUpdatedRowId);
                $chasisUpdatedRows[] =  $supplierInventory->masterModel->model."_".$supplierInventory->masterModel->sfx."_".$supplierInventory->chasis."_".
                    $supplierInventory->engine_number."_".$supplierInventory->color_code."_".$supplierInventory->pord_month."_".
                    $supplierInventory->po_arm;
            }
            $updatedChasisGroupedCount =  array_count_values($chasisUpdatedRows);
        }

        foreach ($firstFileRowDetails as $firstFileRowDetail)
        {
            $date =  Carbon::parse($request->second_file)->format('Y-m-d');
            $masterModel = MasterModel::find($firstFileRowDetail['master_model_id']);
            $isExistSupplier = SupplierInventory::whereDate('date_of_entry', $date)
                ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                ->where('supplier', $request->supplier)
                ->where('whole_sales', $request->whole_sales)
                ->where('master_model_id', $firstFileRowDetail['master_model_id'])
                ->where('chasis', $firstFileRowDetail['chasis'])
                ->where('engine_number', $firstFileRowDetail['engine_number'])
                ->where('color_code', $firstFileRowDetail['color_code'])
                ->where('pord_month', $firstFileRowDetail['pord_month'])
                ->where('po_arm', $firstFileRowDetail['po_arm']);

                $firstFileRow = $masterModel->model."_".$masterModel->sfx."_".$firstFileRowDetail['chasis']."_".
                                $firstFileRowDetail['engine_number']."_".$firstFileRowDetail['color_code']."_".
                                $firstFileRowDetail['pord_month']."_". $firstFileRowDetail['po_arm'];
//                                ->whereNull('eta_import')
            if ($isExistSupplier->count() > 0)
            {
                if ($isExistSupplier->count() != $groupedFirstFileCount[$firstFileRow])
                {
                    $secondFileRowCount = $isExistSupplier->count();
                    if (!empty($chasisUpdatedRows))
                    {
                        if (in_array($firstFileRow,$chasisUpdatedRows))
                        {
                            $groupedFirstFileCount[$firstFileRow] = $groupedFirstFileCount[$firstFileRow] - $updatedChasisGroupedCount[$firstFileRow];
                        }
                    }
                    if ($secondFileRowCount < $groupedFirstFileCount[$firstFileRow])
                    {
                         $deletedRowCount = $groupedFirstFileCount[$firstFileRow] - $secondFileRowCount;
                         $row = $isExistSupplier->first();
                         for($i=0;$i<$deletedRowCount;$i++) {
                             $deletedRows[$i]['model'] = $masterModel->model;
                             $deletedRows[$i]['sfx'] = $masterModel->sfx;
                             $deletedRows[$i]['chasis'] = $row->chasis;
                             $deletedRows[$i]['engine_number'] = $row->engine_number;
                             $deletedRows[$i]['color_code'] = $row->color_code;
                         }
                    }
                }
            }else{
                if (!in_array($firstFileRow, $updatedDetails ))
                {
                    $deletedRows[$j]['model'] = $masterModel->model;
                    $deletedRows[$j]['sfx'] = $masterModel->sfx;
                    $deletedRows[$j]['chasis'] = $firstFileRowDetail['chasis'];
                    $deletedRows[$j]['engine_number'] = $firstFileRowDetail['engine_number'];
                    $deletedRows[$j]['color_code'] = $firstFileRowDetail['color_code'];
                }
            }
            $j++;
        }
        return view('supplier_inventories.file_comparision',compact('supplierInventoryDates','newlyAddedRows',
            'deletedRows','updatedRows'));
    }
    public function lists(Request $request) {
        $startDate = '';
        $endDate = ' ';
        $supplierInventories = SupplierInventory::with('masterModel')
            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
//            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
            ->whereNull('eta_import')
            ->groupBy('master_model_id');

        if (!empty($request->start_date) && !empty($request->end_date)) {
            $startDate = Carbon::parse($request->start_date)->format('Y-m-d');
            $endDate =  Carbon::parse($request->end_date)->format('Y-m-d');

            $supplierInventories =  $supplierInventories->whereBetween('date_of_entry',[$startDate,$endDate]);
        }
        $supplierInventories = $supplierInventories->get();

        return view('supplier_inventories.list', compact('supplierInventories','startDate','endDate'));
    }

    public function getChildRows(Request $request) {
        $masterModelId = $request->master_model_id;
        $data = SupplierInventory::where('master_model_id', $masterModelId)
           ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
            ->get();

        return $data;
    }
    public function getDate(Request $request)
    {
        $supplierInventoryDates = SupplierInventory::where('supplier', $request->supplier)
            ->where('whole_sales', $request->wholesaler)
            ->groupBy('date_of_entry')
            ->pluck('date_of_entry');

        return $supplierInventoryDates;
    }
}
