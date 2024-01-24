<?php

namespace App\Http\Controllers;

use App\Imports\SupplierInventoryImport;
use App\Models\Brand;
use App\Models\ColorCode;
use App\Models\DemandList;
use App\Models\LetterOfIndent;
use App\Models\MasterModel;
use App\Models\ModelYearCalculationCategory;
use App\Models\Supplier;
use App\Models\SupplierInventory;
use App\Models\SupplierInventoryHistory;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class SupplierInventoryController extends Controller
{
    public function index(Request $request)
    {
        (new UserActivityController)->createActivity('Open Supplier Inventory List Page');

        $supplierInventories = SupplierInventory::select('master_model_id','upload_status','veh_status')
            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
            ->leftJoin('master_models', 'master_models.id', '=', 'supplier_inventories.master_model_id')
            ->groupBy('master_models.model','master_models.sfx');

        $suppliers = Supplier::with('supplierTypes')
            ->whereHas('supplierTypes', function ($query) {
                $query->where('supplier_type', Supplier::SUPPLIER_TYPE_DEMAND_PLANNING);
            })
            ->where('status', Supplier::SUPPLIER_STATUS_ACTIVE)
            ->get();

        if($request->supplier_id){

            $supplierInventories = $supplierInventories->where('supplier_id', $request->supplier_id);
        }
        if($request->dealers){
            $supplierInventories = $supplierInventories->where('whole_sales', $request->dealers);
        }

        $supplierInventories = $supplierInventories->get();
        foreach ($supplierInventories as $supplierInventory) {
            $modelIds = MasterModel::where('model', $supplierInventory->masterModel->model)
                        ->where('sfx', $supplierInventory->masterModel->sfx)
                        ->where('steering', $supplierInventory->masterModel->steering)
                        ->pluck('id');

            $supplierInventory->childRows =  SupplierInventory::with('masterModel')
                    ->whereIn('master_model_id', $modelIds)
                    ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                    ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                    ->orderBy('id','desc')
                    ->get();
        }
        return view('supplier_inventories.index', compact('supplierInventories','suppliers'));
    }
    public function viewAll(Request $request, Builder $builder)
    {
        (new UserActivityController)->createActivity('Open Supplier Inventory Sheet Update Page');

        $supplierInventories = SupplierInventory::with('masterModel')
            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
            ->orderBy('updated_at','DESC')
            ->get();
        foreach ($supplierInventories as $supplierInventory) {
            $supplierInventory->modelYears = MasterModel::where('model', $supplierInventory->masterModel->model)
                ->where('sfx', $supplierInventory->masterModel->sfx)
                ->pluck('model_year');
        }

        $suppliers = Supplier::with('supplierTypes')
            ->whereHas('supplierTypes', function ($query) {
                $query->where('supplier_type', Supplier::SUPPLIER_TYPE_DEMAND_PLANNING);
            })
            ->where('status', Supplier::SUPPLIER_STATUS_ACTIVE)
            ->get();

        return view('supplier_inventories.list_update', compact('supplierInventories','suppliers'));
    }
    public function create()
    {
        (new UserActivityController)->createActivity('Open Supplier Inventory Create Page');

        $suppliers = Supplier::with('supplierTypes')
            ->whereHas('supplierTypes', function ($query) {
                $query->where('supplier_type', Supplier::SUPPLIER_TYPE_DEMAND_PLANNING);
            })
            ->where('status', Supplier::SUPPLIER_STATUS_ACTIVE)
            ->get();
        return view('supplier_inventories.edit', compact('suppliers'));

    }

    public function store(Request $request)
    {
        (new UserActivityController)->createActivity('Added Supplier Inventories');

        $request->validate([
            'whole_sales' => 'required',
            'supplier_id' =>' required',
            'file' => 'required|mimes:xlsx,xls,csv|max:102400',
        ]);

        if ($request->file('file'))
        {

            $errors = [];
            $numberOfFields = 10;
            $file = $request->file('file');
            $fileName = time().'.'.$file->getClientOriginalExtension();
            $destinationPath = "inventory";
            $file->move($destinationPath,$fileName);
           // file validations
            $path = public_path("inventory/".$fileName);
            $fileColumns = Excel::toArray([], $path)[0][0];
            $columnCount = count($fileColumns);
            if($columnCount != $numberOfFields) {
                return redirect()->back()->with(['error' => 'Invalid Column Count found!.']);
            }

            $file = fopen("inventory/".$fileName, "r");
            $i = 0;

            $uploadFileContents = [];
            $colourname = NULL;

            $date = Carbon::tomorrow()->format('Y-m-d');
            $unavailableExtColours = [];
            $unavailableIntColours = [];

            while (($filedata = fgetcsv($file, 5000, ",")) !== FALSE) {
                $num = count($filedata);
                if ($i > 0 && $num == $numberOfFields)
                {
                    $supplier_id = $request->input('supplier_id');
                    $country = $request->input('country');
                    $colourcode = $filedata[5];

                    if($colourcode) {
                        if(strlen($filedata[5]) < 4  || strlen($filedata[5]) > 5){
                            return redirect()->back()->with('error', 'Invalid Colour Code '.$filedata[5].', Color Code length should be 5 or 4!');
                        }
                        $colourcodecount = strlen($colourcode);

                        if ($colourcodecount == 5) {
                            $extColour = substr($colourcode, 0, 3);
                            $intColour = substr($colourcode,  -2);

                        }
                        if ($colourcodecount == 4) {

                            $altercolourcode = "0" . $colourcode;
                            $extColour = substr($altercolourcode, 0, 3);
                            $intColour = substr($altercolourcode, -2);
                            $colourcode = $extColour.''.$intColour;

                        }
                        if($extColour) {
                            $extColourRow = ColorCode::where('code', $extColour)
                                ->where('belong_to', ColorCode::EXTERIOR)
                                ->first();
                            $exteriorColor = "";
                            if ($extColourRow)
                            {
                                $exteriorColor = $extColourRow->name;
                                $exteriorColorId = $extColourRow->id;
                            }else{
                                $unavailableExtColours[]  = $extColour;
                            }
                        }
                        if($intColour) {
                            $intColourRow = ColorCode::where('code', $intColour)
                                ->where('belong_to', ColorCode::INTERIOR)
                                ->first();
                            $interiorColor = "";
                            if ($intColourRow)
                            {
                                $interiorColor = $intColourRow->name;
                                $interiorColorId = $intColourRow->id;
                            }else{
                                $unavailableIntColours[] = $intColour;
                            }
                        }
                    }

                    $uploadFileContents[$i]['steering'] = $filedata[0];
                    $uploadFileContents[$i]['model'] = $filedata[1];
                    $uploadFileContents[$i]['sfx'] = $filedata[2];
                    $uploadFileContents[$i]['chasis'] = !empty($filedata[3]) ? $filedata[3] : NULL;
                    $uploadFileContents[$i]['engine_number'] = $filedata[4];
                    $uploadFileContents[$i]['color_code'] = $colourcode;
                    $uploadFileContents[$i]['pord_month'] = $filedata[6];
                    $uploadFileContents[$i]['po_arm'] = $filedata[7];
                    if(!empty($filedata[6])) {
                      if(strlen($filedata[6]) != 6){
                          return redirect()->back()->with('error', 'Invalid Production Month '.$filedata[6].', Production month length should be exactly 6!');
                      }else{
                          $productionMonth = substr($filedata[6],  -2);
                          if($productionMonth < 0 || $productionMonth > 12) {
                              return redirect()->back()->with('error', 'Invalid Production Month '.$filedata[6].', Last 2 digit indicating Invalid month!');
                          }
                      }
                    }
                    if (!empty($filedata[8])) {
                        try {
                            $filedata[8] = \Illuminate\Support\Carbon::parse($filedata[8])->format('Y-m-d');
                        } catch (\Exception $e) {
                            return redirect()->back()->with('error', 'Invalid date, Please enter valid ETA import date!') ;
                        }
                    }else {
                        $filedata[8] = NULL;
                    }
                    $uploadFileContents[$i]['eta_import'] = $filedata[8];
                    $uploadFileContents[$i]['delivery_note'] = !empty($filedata[9]) ? $filedata[9] : NULL;
                    $uploadFileContents[$i]['supplier_id'] = $supplier_id;
                    $uploadFileContents[$i]['whole_sales'] = $request->whole_sales;
                    $uploadFileContents[$i]['country'] = $country;
                    $uploadFileContents[$i]['date_of_entry'] = $date;
                    $uploadFileContents[$i]['veh_status'] = SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY;
                    $uploadFileContents[$i]['exterior_color_code_id'] = !empty($exteriorColorId) ? $exteriorColorId: NULL;
                    $uploadFileContents[$i]['interior_color_code_id'] = !empty($interiorColorId) ? $interiorColorId: NULL;

                    ////// finding model year //////////

                    if ($filedata[6]) {
                        // fetch year from pod month
                        $modelYear = substr($filedata[6], 0, -2);
                        $productionMonth = substr($filedata[6], -2);
                        $modelYearCalculationCategories = ModelYearCalculationCategory::all();

                        foreach ($modelYearCalculationCategories as $modelYearCalculationCategory) {
                            $isItemExistCategory = MasterModel::select(['id', 'model', 'sfx', 'variant_id'])
                                ->where('model', $filedata[1])
                                ->where('sfx', $filedata[2])
                                ->with('variant.master_model_lines')
                                ->whereHas('variant.master_model_lines', function ($query) use ($modelYearCalculationCategory) {
                                    $query->where('model_line', 'LIKE', '%' . $modelYearCalculationCategory->name . '%');
                                });

                            if ($isItemExistCategory->count() > 0) {
                                $correspondingCategoryRuleValue = $modelYearCalculationCategory->modelYearRule->value ?? 0;
                                if ($productionMonth > $correspondingCategoryRuleValue) {
                                    if ($filedata[6]){
                                        $modelYear = substr($filedata[6], 0, -2) + 1;
                                    }
                                    break;
                                }
                            }
                        }
                    }else{
                        $modelYear = null;
                        // eta import date always come get the month from eta import date.
                        $latestModelYearVariant = MasterModel::where('model', $filedata[1])
                            ->where('sfx', $filedata[2])
                            ->orderBy('model_year','DESC')
                            ->first();
                          if($latestModelYearVariant) {
                              $modelYear = $latestModelYearVariant->model_year;
                          }
                    }

                    ////////////// model year calculation end //////////
                    $uploadFileContents[$i]['model_year'] = $modelYear;
                }
                $exteriorColorId = NULL;
                $interiorColorId = NULL;
                $i++;
            }

            fclose($file);
            $newModels = [];
            $newModelsWithSteerings = [];
            $j=0;

            if(count($unavailableIntColours) || count($unavailableIntColours)) {
             $extColors = implode(',', array_unique($unavailableExtColours));
             $intColors = implode(',', array_unique($unavailableIntColours));

                return redirect()->back()->with('error','These Colour codes are not available in the Master Data.
                Exterior Color codes are '.$extColors." and Interior Color Codes are ".$intColors.".");
            }
            $excelPairs = [];
            $chasis = [];

            foreach($uploadFileContents as $uploadFileContent) {

//                $LOIs = LetterOfIndent::where('submission_status', LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED)
//                                        ->where('dealers', $request->whole_sales)
//                                        ->where('date', )

//                    info($uploadFileContent['model_year']);
//                    info($uploadFileContent['model']);
//                    info($uploadFileContent['sfx']);

                $chasis[] = $uploadFileContent['chasis'];
                if(empty($uploadFileContent['chasis'])) {
                    $excelPairs[] = $uploadFileContent['model'] . "_" . $uploadFileContent['sfx'];
                }

                // CHCEKING NEW MODEL SFX MODEL YEAR COMBINATION EXISTING ///////////

                $isModelExist = MasterModel::where('model',$uploadFileContent['model'])
                                            ->where('sfx', $uploadFileContent['sfx'])
                                            ->where('model_year',  $uploadFileContent['model_year'])
                                            ->first();

                $isModelWithSteeringExist = MasterModel::where('model', $uploadFileContent['model'])
                    ->where('sfx', $uploadFileContent['sfx'])
                    ->where('steering', $uploadFileContent['steering'])
                    ->where('model_year', $uploadFileContent['model_year'])
                    ->first();

                if(empty($isModelWithSteeringExist))
                {

                    $newModelsWithSteerings[$j]['steering'] = $uploadFileContent['steering'];
                    $newModelsWithSteerings[$j]['model'] = $uploadFileContent['model'];
                    $newModelsWithSteerings[$j]['sfx'] = $uploadFileContent['sfx'];
                    $newModelsWithSteerings[$j]['model_year'] = $uploadFileContent['model_year'];

                }
                if (empty($isModelExist))
                {

                    $newModels[$j]['model'] = $uploadFileContent['model'];
                    $newModels[$j]['sfx'] = $uploadFileContent['sfx'];
                    $newModels[$j]['model_year'] =  $uploadFileContent['model_year'];
                }
                $j++;
            }

                // get the null chasis row count by model and sfx.
            $inventories = SupplierInventory::where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                ->where('supplier_id', $request->supplier_id)
                ->where('whole_sales', $request->whole_sales)
                ->whereNull('chasis')
                ->get();

            $existingItems = [];
            foreach ($inventories as $inventory) {
                $existingItems[] = $inventory->masterModel->model .'_'.$inventory->masterModel->sfx;
            }

            $groupedExcelPairs =  array_count_values($excelPairs);
            $groupedExistingPairs = array_count_values($existingItems);

            // CHCEK CHASIS EXISTING WITH ALREDY UPLOADED DATA.

            $chasisNumbers = array_filter($chasis);
            $uniqueChaisis =  array_unique($chasisNumbers);

            if(count($chasisNumbers) !== count($uniqueChaisis)) {
                return redirect()->back()->with('error', "Duplicate Chasis Number found in Your File! Please upload file with unique Chasis Number.");
            }

            $newModelsWithSteerings = array_map("unserialize", array_unique(array_map("serialize", $newModelsWithSteerings)));
            $newModels = array_map("unserialize", array_unique(array_map("serialize", $newModels)));

            if(count($newModels) > 0 || count($newModelsWithSteerings) > 0)
            {
                $pdf = Pdf::loadView('supplier_inventories.new_models', compact('newModels', 'newModelsWithSteerings'));
                return $pdf->download('New_Models_'.date('Y_m_d').'.pdf');
                // show error msg
            } else
            {
                if(!$request->has('is_add_new'))
                {
                    DB::beginTransaction();

                    $i = 0;
                    $countblankchasis = [];
                    $newlyAddedRows = [];
                    $newlyAddedRowIds = [];
                    $updatedRows = [];
                    $updatedRowsIds = [];
                    $excelValuePair = [];
                    $noChangeRowIds = [];
                    $chasisUpdatedRowIds = [];

                    $supplierInventoryHistories = SupplierInventoryHistory::all();

                    foreach ($supplierInventoryHistories as $inventoryHistory) {
                        $inventoryHistory->upload_status = SupplierInventory::UPLOAD_STATUS_INACTIVE;
                        $inventoryHistory->save();
                    }

                    foreach ($uploadFileContents as $uploadFileContent)
                    {
                        $model = MasterModel::where('model', $uploadFileContent['model'])
                            ->where('sfx', $uploadFileContent['sfx'])
                            ->where('model_year', $uploadFileContent['model_year'])
                            ->where('steering', $uploadFileContent['steering'])
                            ->first();

                        $modelId = $model->id;
                        info($uploadFileContent['model']);
                        info($uploadFileContent['sfx']);
                        $modelIds = MasterModel::where('model', $uploadFileContent['model'])
                            ->where('sfx', $uploadFileContent['sfx'])
                            ->pluck('id')->toArray();

                        $deliveryNote = $uploadFileContent['delivery_note'];
                        // after having DN data their is no changes for data of thata ro.so consider the data without eta import for inventory
                        $supplierInventories = SupplierInventory::whereIn('master_model_id', $modelIds)
//                            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                            ->where('supplier_id', $request->supplier_id)
                            ->where('whole_sales', $request->whole_sales)
//                            ->whereNull('delivery_note');
                            ->where(function ($query) use($deliveryNote) {
                                $query->whereNull('delivery_note')
                                    ->orwhere('delivery_note', $deliveryNote);
                            });
    //                       ->whereNull('delivery_note');
                        info("supplier count");
                        info($supplierInventories->count());

                        if ($supplierInventories->count() <= 0)
                        {
                            info("no row existing with model,sfx model year so => add new row");

                            // model and sfx not existing in Suplr Invtry => new row
                            $newlyAddedRows[$i]['model'] = $uploadFileContent['model'];
                            $newlyAddedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                            $newlyAddedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                            $newlyAddedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                            $newlyAddedRows[$i]['color_code'] = $uploadFileContent['color_code'];

                            $supplierInventory = new SupplierInventory();
                            $supplierInventory->master_model_id = $modelId;
                            $supplierInventory->supplier_id     = $uploadFileContent['supplier_id'];
                            $supplierInventory->chasis          = $uploadFileContent['chasis'];
                            $supplierInventory->engine_number   = $uploadFileContent['engine_number'];
                            $supplierInventory->color_code      = $uploadFileContent['color_code'];
                            $supplierInventory->pord_month      = $uploadFileContent['pord_month'];
                            $supplierInventory->po_arm          = $uploadFileContent['po_arm'];
                            $supplierInventory->eta_import      = $uploadFileContent['eta_import'];
                            $supplierInventory->is_add_new     	= false;
                            $supplierInventory->whole_sales	    = $uploadFileContent['whole_sales'];
                            $supplierInventory->country     	= $uploadFileContent['country'];
                            $supplierInventory->delivery_note   = $uploadFileContent['delivery_note'];
                            $supplierInventory->date_of_entry   = $date;
                            $supplierInventory->upload_status   = SupplierInventory::UPLOAD_STATUS_ACTIVE;
                            $supplierInventory->veh_status      = SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY;
                            $supplierInventory->interior_color_code_id = $uploadFileContent['interior_color_code_id'];
                            $supplierInventory->exterior_color_code_id = $uploadFileContent['exterior_color_code_id'];
                            if($uploadFileContent['delivery_note']) {
                                $supplierInventory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                            }
                            $supplierInventory->save();

                            $newlyAddedRowIds[] = $supplierInventory->id;

                        } else {
                            $isSimilarRowExist =  SupplierInventory::whereIn('master_model_id', $modelIds)
//                            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                                ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                                ->where('supplier_id', $request->supplier_id)
                                ->where('whole_sales', $request->whole_sales)
                                ->where('chasis', $uploadFileContent['chasis'])
                                ->where('engine_number', $uploadFileContent['engine_number'])
                                ->where('color_code', $uploadFileContent['color_code'])
                                ->where('pord_month', $uploadFileContent['pord_month'])
                                ->where('po_arm', $uploadFileContent['po_arm'])
                                ->whereNotIn('id', $noChangeRowIds)
                                ->whereNotIn('id', $updatedRowsIds)
                                ->whereNotIn('id', $newlyAddedRowIds)
                                ->where('eta_import', $uploadFileContent['eta_import'])
                                ->where('delivery_note', $uploadFileContent['delivery_note'])
                                ->first();

                            info($isSimilarRowExist);
                            info("model sfx modelyear combination row existing");
                            if(empty($isSimilarRowExist)) {

//                            info($uploadFileContent['chasis']);
                                if (!empty($uploadFileContent['chasis']))
                                {
                                    info($uploadFileContent['chasis']);
                                    info($modelIds);

                                    // Store the Count into Update the Row with data
                                    $supplierInventory = SupplierInventory::whereIn('master_model_id', $modelIds)
                                        //   ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                                        ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                                        ->where('supplier_id', $request->supplier_id)
                                        ->where('whole_sales', $request->whole_sales)
                                        ->whereNotIn('id', $noChangeRowIds)
                                        ->whereNotIn('id', $newlyAddedRowIds)
                                        ->whereNotIn('id', $updatedRowsIds)
                                        ->where('chasis', $uploadFileContent['chasis'])
//                                        ->whereNull('delivery_note') unable to find row when have delivery note
                                        ->where(function ($query) use($deliveryNote) {
                                            $query->whereNull('delivery_note')
                                                ->orwhere('delivery_note', $deliveryNote);
                                        })
                                    ->first();

                                    info($supplierInventory);

                                    if (empty($supplierInventory)) {
                                        info("chasis matching row not avaialble, case of chasis updation ore new chaisi row add");
                                        //adding new row simply
                                        $isNullChaisis = SupplierInventory::whereIn('master_model_id', $modelIds)
    //                            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                                            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                                            ->where('supplier_id', $request->supplier_id)
                                            ->where('whole_sales', $request->whole_sales)
                                            ->whereNotIn('id', $noChangeRowIds)
                                            ->whereNotIn('id', $newlyAddedRowIds)
                                            ->whereNotIn('id', $updatedRowsIds)
                                            ->whereNull('chasis')
                                            ->where(function ($query) use($deliveryNote) {
                                                $query->whereNull('delivery_note')
                                                    ->orwhere('delivery_note', $deliveryNote);
                                            });
                                        $isNullChaisisIds = $isNullChaisis->pluck('id');

                                        info($isNullChaisis->get());

    //                                    $isNullChaisisExist = $isNullChaisis->first();
                                        info($isNullChaisis->count());
                                        if ($isNullChaisis->count() > 0) {
                                            info("null chasis row exist");
                                            // find the null chaisis row
                                            // null chaisis existing => updating row
                                            // find the row with similar details.
                                            $SimilarRowWithNullChaisis = $isNullChaisis->where('engine_number', $uploadFileContent['engine_number'])
                                                ->where('color_code', $uploadFileContent['color_code'])
                                                ->where('pord_month', $uploadFileContent['pord_month'])
                                                ->where('po_arm', $uploadFileContent['po_arm'])
                                                ->where('eta_import', $uploadFileContent['eta_import'])
                                                ->first();
                                            info($SimilarRowWithNullChaisis);
                                            if(!empty($SimilarRowWithNullChaisis)) {
                                                info("null chasis with smilar data exist");

    //                                            $isNullChaisis = $SimilarRowWithNullChaisis;
                                                $SimilarRowWithNullChaisis->chasis  = $uploadFileContent['chasis'];
                                                $SimilarRowWithNullChaisis->save();

                                                $updatedRowsIds[] = $SimilarRowWithNullChaisis->id;
                                                $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                                $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                                $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                                $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                                $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];

                                            }else{
                                                info("null chasis smilar data not exist => check if any similar model sfx row without having any update yet.");
                                                info($newlyAddedRowIds);
                                                info($updatedRowsIds);

                                                $rowWithoutUpdate = SupplierInventory::whereIn('id', $isNullChaisisIds)->whereNotIn('id', $newlyAddedRowIds)
                                                    ->whereNotIn('id', $updatedRowsIds)
                                                    ->whereNotIn('id', $noChangeRowIds)->first();

                                                info($rowWithoutUpdate);

                                                if(!empty($rowWithoutUpdate)) {
    //                                                $isNullChaisis = $rowWithoutUpdate->first();
                                                    $updatedRowsIds[] = $rowWithoutUpdate->id;

                                                    $rowWithoutUpdate->chasis          = $uploadFileContent['chasis'];
                                                    $rowWithoutUpdate->engine_number   = $uploadFileContent['engine_number'];
                                                    $rowWithoutUpdate->color_code      = $uploadFileContent['color_code'];
                                                    $rowWithoutUpdate->pord_month      = $uploadFileContent['pord_month'];
                                                    $rowWithoutUpdate->po_arm          = $uploadFileContent['po_arm'];
                                                    $rowWithoutUpdate->eta_import      = $uploadFileContent['eta_import'];
                                                    $rowWithoutUpdate->delivery_note   = $uploadFileContent['delivery_note'];
                                                    $rowWithoutUpdate->interior_color_code_id = $uploadFileContent['interior_color_code_id'];
                                                    $rowWithoutUpdate->exterior_color_code_id = $uploadFileContent['exterior_color_code_id'];
                                                    if($uploadFileContent['delivery_note']) {
                                                        $rowWithoutUpdate->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                                    }
                                                    $rowWithoutUpdate->save();

                                                    $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                                    $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                                    $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                                    $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                                    $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];
                                                }else{
                                                    info("no row found to update");
                                                    $newlyAddedRows[$i]['model'] = $uploadFileContent['model'];
                                                    $newlyAddedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                                    $newlyAddedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                                    $newlyAddedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                                    $newlyAddedRows[$i]['color_code'] = $uploadFileContent['color_code'];

                                                    $supplierInventory = new SupplierInventory();
                                                    $supplierInventory->master_model_id = $modelId;
                                                    $supplierInventory->supplier_id     = $uploadFileContent['supplier_id'];
                                                    $supplierInventory->chasis          = $uploadFileContent['chasis'];
                                                    $supplierInventory->engine_number   = $uploadFileContent['engine_number'];
                                                    $supplierInventory->color_code      = $uploadFileContent['color_code'];
                                                    $supplierInventory->pord_month      = $uploadFileContent['pord_month'];
                                                    $supplierInventory->po_arm          = $uploadFileContent['po_arm'];
                                                    $supplierInventory->eta_import      = $uploadFileContent['eta_import'];
                                                    $supplierInventory->is_add_new     	= false;
                                                    $supplierInventory->whole_sales	    = $uploadFileContent['whole_sales'];
                                                    $supplierInventory->country     	= $uploadFileContent['country'];
                                                    $supplierInventory->delivery_note   = $uploadFileContent['delivery_note'];
                                                    $supplierInventory->date_of_entry   = $date;
                                                    $supplierInventory->upload_status   = SupplierInventory::UPLOAD_STATUS_ACTIVE;
                                                    $supplierInventory->veh_status      = SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY;
                                                    $supplierInventory->interior_color_code_id = $uploadFileContent['interior_color_code_id'];
                                                    $supplierInventory->exterior_color_code_id = $uploadFileContent['exterior_color_code_id'];
                                                    if($uploadFileContent['delivery_note']) {
                                                        $supplierInventory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                                    }
                                                    $supplierInventory->save();

                                                    $newlyAddedRowIds[] = $supplierInventory->id;
                                                }
                                            }

                                        } else {
                                            info("new chaisis with existing model and sfx => add row");
                                            // new chaisis with existing model and sfx => add row ,
                                            $newlyAddedRows[$i]['model'] = $uploadFileContent['model'];
                                            $newlyAddedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                            $newlyAddedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                            $newlyAddedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                            $newlyAddedRows[$i]['color_code'] = $uploadFileContent['color_code'];

                                            $supplierInventory = new SupplierInventory();
                                            $supplierInventory->master_model_id = $modelId;
                                            $supplierInventory->supplier_id     = $uploadFileContent['supplier_id'];
                                            $supplierInventory->chasis          = $uploadFileContent['chasis'];
                                            $supplierInventory->engine_number   = $uploadFileContent['engine_number'];
                                            $supplierInventory->color_code      = $uploadFileContent['color_code'];
                                            $supplierInventory->pord_month      = $uploadFileContent['pord_month'];
                                            $supplierInventory->po_arm          = $uploadFileContent['po_arm'];
                                            $supplierInventory->eta_import      = $uploadFileContent['eta_import'];
                                            $supplierInventory->is_add_new     	= false;
                                            $supplierInventory->whole_sales	    = $uploadFileContent['whole_sales'];
                                            $supplierInventory->country     	= $uploadFileContent['country'];
                                            $supplierInventory->delivery_note   = $uploadFileContent['delivery_note'];
                                            $supplierInventory->date_of_entry   = $date;
                                            $supplierInventory->upload_status   = SupplierInventory::UPLOAD_STATUS_ACTIVE;
                                            $supplierInventory->veh_status      = SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY;
                                            $supplierInventory->interior_color_code_id = $uploadFileContent['interior_color_code_id'];
                                            $supplierInventory->exterior_color_code_id = $uploadFileContent['exterior_color_code_id'];
                                            if($uploadFileContent['delivery_note']) {
                                                $supplierInventory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                            }
                                            $supplierInventory->save();

                                            $newlyAddedRowIds[] = $supplierInventory->id;

                                        }
                                    }
                                    else
                                    {
                                        info("inventory with chasis existing.");
                                        // inventory with chasis existing...

                                        if(!empty($uploadFileContent['delivery_note'])) {
                                            $supplierInventories = $supplierInventories->where('delivery_note', $uploadFileContent['delivery_note']);
                                        }
                                        $supplierInventories = $supplierInventories->where('engine_number', $uploadFileContent['engine_number'])
                                            ->where('color_code', $uploadFileContent['color_code'])
                                            ->where('pord_month', $uploadFileContent['pord_month'])
                                            ->where('po_arm', $uploadFileContent['po_arm'])
                                            ->where('eta_import', $uploadFileContent['eta_import'])
                                            ->first();

                                        if (!$supplierInventories)
                                        {
                                            info("chasis with detail row not exist update row");
                                            // chasis existing our system so get corresponding inventory when engine number is not matching
                                            $updatedRowsIds[]                   = $supplierInventory->id;
                                            $updatedRows[$i]['model']           = $uploadFileContent['model'];
                                            $updatedRows[$i]['sfx']             = $uploadFileContent['sfx'];
                                            $updatedRows[$i]['model_year']      = $uploadFileContent['model_year'];
                                            $updatedRows[$i]['chasis']          = $uploadFileContent['chasis'];
                                            $updatedRows[$i]['engine_number']   = $uploadFileContent['engine_number'];
                                            $updatedRows[$i]['color_code']      = $uploadFileContent['color_code'];

                                            $supplierInventory->chasis          = $uploadFileContent['chasis'];
                                            $supplierInventory->engine_number   = $uploadFileContent['engine_number'];
                                            $supplierInventory->color_code      = $uploadFileContent['color_code'];
                                            $supplierInventory->interior_color_code_id = $uploadFileContent['interior_color_code_id'];
                                            $supplierInventory->exterior_color_code_id = $uploadFileContent['exterior_color_code_id'];
                                            $supplierInventory->pord_month      = $uploadFileContent['pord_month'];
                                            $supplierInventory->po_arm          = $uploadFileContent['po_arm'];
                                            $supplierInventory->eta_import      = $uploadFileContent['eta_import'];
                                            $supplierInventory->delivery_note   = $uploadFileContent['delivery_note'];
                                            if($uploadFileContent['delivery_note']) {
                                                $supplierInventory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                            }
                                            $supplierInventory->save();
                                        }
                                    }
                                }
                                else {
                                    info("no chasis found=> chcek for updation or add new row");
                                    $modelSfxValuePair = $uploadFileContent['model']."_".$uploadFileContent['sfx'];

                                    $excelPairCount = $groupedExcelPairs[$modelSfxValuePair];
                                    if(array_key_exists($modelSfxValuePair, $groupedExistingPairs)) {
                                        $existingPairCount = $groupedExistingPairs[$modelSfxValuePair];

                                    }else{
                                        $existingPairCount = 0;

                                    }

                                    if($excelPairCount == $existingPairCount) {
                                        $inventoryRow = SupplierInventory::whereIn('master_model_id', $modelIds)
                                            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                                            ->where('supplier_id', $request->supplier_id)
                                            ->where('whole_sales', $request->whole_sales)
                                            ->whereNull('chasis')
                                            ->whereNotIn('id', $noChangeRowIds)
                                            ->whereNotIn('id', $updatedRowsIds)
                                            ->whereNotIn('id', $newlyAddedRowIds)
                                            ->where(function ($query) use($deliveryNote) {
                                                $query->whereNull('delivery_note')
                                                    ->orwhere('delivery_note', $deliveryNote);
                                            })
                                            ->first();
                                        if(!empty($inventoryRow)) {
                                            $updatedRowsIds[] = $inventoryRow->id;

                                            $inventoryRow->engine_number   = $uploadFileContent['engine_number'];
                                            $inventoryRow->color_code      = $uploadFileContent['color_code'];
                                            $inventoryRow->pord_month      = $uploadFileContent['pord_month'];
                                            $inventoryRow->po_arm          = $uploadFileContent['po_arm'];
                                            $inventoryRow->eta_import      = $uploadFileContent['eta_import'];
                                            $inventoryRow->delivery_note   = $uploadFileContent['delivery_note'];
                                            $inventoryRow->interior_color_code_id = $uploadFileContent['interior_color_code_id'];
                                            $inventoryRow->exterior_color_code_id = $uploadFileContent['exterior_color_code_id'];
                                            if($uploadFileContent['delivery_note']) {
                                                $inventoryRow->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                            }
                                            $inventoryRow->save();

                                            $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                            $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                            $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                            $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                            $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];
                                        }

                                        info("both colum count equal => clear case of row updation find the row ");

                                    }else if($excelPairCount > $existingPairCount) {
                                        // check for engine number is changed or not
                                        $nullChasisRow = SupplierInventory::whereIn('master_model_id', $modelIds)
                                            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                                            ->where('supplier_id', $request->supplier_id)
                                            ->where('whole_sales', $request->whole_sales)
                                            ->whereNull('chasis')
                                            ->whereNotIn('id', $noChangeRowIds)
                                            ->whereNotIn('id', $updatedRowsIds)
                                            ->whereNotIn('id', $newlyAddedRowIds)->first();

                                        if(!empty($nullChasisRow)) {
                                            info("no existing row with chasis found => update row");
                                            $updatedRowsIds[] = $nullChasisRow->id;

                                            $nullChasisRow->engine_number   = $uploadFileContent['engine_number'];
                                            $nullChasisRow->color_code      = $uploadFileContent['color_code'];
                                            $nullChasisRow->pord_month      = $uploadFileContent['pord_month'];
                                            $nullChasisRow->po_arm          = $uploadFileContent['po_arm'];
                                            $nullChasisRow->eta_import      = $uploadFileContent['eta_import'];
                                            $nullChasisRow->delivery_note   = $uploadFileContent['delivery_note'];
                                            $nullChasisRow->interior_color_code_id = $uploadFileContent['interior_color_code_id'];
                                            $nullChasisRow->exterior_color_code_id = $uploadFileContent['exterior_color_code_id'];
                                            if($uploadFileContent['delivery_note']) {
                                                $nullChasisRow->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                            }
                                            $nullChasisRow->save();

                                            $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                            $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                            $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                            $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                            $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];

                                        }else{
                                            info("no existing row with no chasis found => add new row");

                                            $newlyAddedRows[$i]['model'] = $uploadFileContent['model'];
                                            $newlyAddedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                            $newlyAddedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                            $newlyAddedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                            $newlyAddedRows[$i]['color_code'] = $uploadFileContent['color_code'];

                                            $supplierInventory = new SupplierInventory();
                                            $supplierInventory->master_model_id = $modelId;
                                            $supplierInventory->supplier_id     = $uploadFileContent['supplier_id'];
                                            $supplierInventory->chasis          = $uploadFileContent['chasis'];
                                            $supplierInventory->engine_number   = $uploadFileContent['engine_number'];
                                            $supplierInventory->color_code      = $uploadFileContent['color_code'];
                                            $supplierInventory->pord_month      = $uploadFileContent['pord_month'];
                                            $supplierInventory->po_arm          = $uploadFileContent['po_arm'];
                                            $supplierInventory->eta_import      = $uploadFileContent['eta_import'];
                                            $supplierInventory->is_add_new     	= false;
                                            $supplierInventory->whole_sales	    = $uploadFileContent['whole_sales'];
                                            $supplierInventory->country     	= $uploadFileContent['country'];
                                            $supplierInventory->delivery_note   = $uploadFileContent['delivery_note'];
                                            $supplierInventory->date_of_entry   = $date;
                                            $supplierInventory->upload_status   = SupplierInventory::UPLOAD_STATUS_ACTIVE;
                                            $supplierInventory->veh_status      = SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY;
                                            $supplierInventory->interior_color_code_id = $uploadFileContent['interior_color_code_id'];
                                            $supplierInventory->exterior_color_code_id = $uploadFileContent['exterior_color_code_id'];
                                            if($uploadFileContent['delivery_note']) {
                                                $supplierInventory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                            }
                                            $supplierInventory->save();

                                            $newlyAddedRowIds[] = $supplierInventory->id;
                                            info("coming row count is > existing chance for adding row also check any row updation is there");
                                        }

                                    }else{
                                        $nullChasisRow = SupplierInventory::whereIn('master_model_id', $modelIds)
                                            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                                            ->where('supplier_id', $request->supplier_id)
                                            ->where('whole_sales', $request->whole_sales)
                                            ->whereNull('chasis')
                                            ->whereNotIn('id', $noChangeRowIds)
                                            ->whereNotIn('id', $updatedRowsIds)
                                            ->whereNotIn('id', $newlyAddedRowIds)->first();

                                        if(!empty($nullChasisRow)) {
                                            info("no existing row with chasis found => update row");
                                            $updatedRowsIds[] = $nullChasisRow->id;

                                            $nullChasisRow->engine_number   = $uploadFileContent['engine_number'];
                                            $nullChasisRow->color_code      = $uploadFileContent['color_code'];
                                            $nullChasisRow->pord_month      = $uploadFileContent['pord_month'];
                                            $nullChasisRow->po_arm          = $uploadFileContent['po_arm'];
                                            $nullChasisRow->eta_import      = $uploadFileContent['eta_import'];
                                            $nullChasisRow->delivery_note   = $uploadFileContent['delivery_note'];
                                            $nullChasisRow->interior_color_code_id = $uploadFileContent['interior_color_code_id'];
                                            $nullChasisRow->exterior_color_code_id = $uploadFileContent['exterior_color_code_id'];
                                            if($uploadFileContent['delivery_note']) {
                                                $nullChasisRow->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                            }
                                            $nullChasisRow->save();

                                            $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                            $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                            $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                            $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                            $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];
                                        }
                                        info("coming row count is lesser it may be deleted or updation");
                                    }
                                }
                            }else{

                                $noChangeRowIds[] = $isSimilarRowExist->id;
                            }

                        }$i++;

                        $supplierInventoryHistory = new SupplierInventoryHistory();

                        $supplierInventoryHistory->master_model_id = $model->id;
                        $supplierInventoryHistory->chasis          = $uploadFileContent['chasis'];
                        $supplierInventoryHistory->engine_number   = $uploadFileContent['engine_number'];
                        $supplierInventoryHistory->color_code      = $uploadFileContent['color_code'];
                        $supplierInventoryHistory->pord_month      = $uploadFileContent['pord_month'];
                        $supplierInventoryHistory->po_arm          = $uploadFileContent['po_arm'];
                        $supplierInventoryHistory->eta_import      = $uploadFileContent['eta_import'];
                        $supplierInventoryHistory->is_add_new      = !empty($request->is_add_new) ? true : false;
                        $supplierInventoryHistory->supplier_id     = $uploadFileContent['supplier_id'];
                        $supplierInventoryHistory->whole_sales	   = $uploadFileContent['whole_sales'];
                        $supplierInventoryHistory->country     	   = $uploadFileContent['country'];
                        $supplierInventoryHistory->delivery_note   = $uploadFileContent['delivery_note'];
                        $supplierInventoryHistory->date_of_entry   = $date;
                        $supplierInventoryHistory->upload_status   = SupplierInventory::UPLOAD_STATUS_ACTIVE;
                        $supplierInventoryHistory->veh_status      = SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY;
                        $supplierInventoryHistory->interior_color_code_id = $uploadFileContent['interior_color_code_id'];
                        $supplierInventoryHistory->exterior_color_code_id = $uploadFileContent['exterior_color_code_id'];
                        if($uploadFileContent['delivery_note']) {
                            $supplierInventoryHistory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                        }
                        $supplierInventoryHistory->save();
                    }
                        // to find deleted rows
                        // group the value pair to get count of duplicate data

                        $deletedRows = SupplierInventory::where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                            ->where('supplier_id', $request->supplier_id)
                            ->where('whole_sales', $request->whole_sales)
                            ->whereNotIn('id', $noChangeRowIds)
                            ->whereNotIn('id', $updatedRowsIds)
                            ->whereNotIn('id', $newlyAddedRowIds)->get();
                        foreach ($deletedRows as $deletedRow) {
                            $deletedRow->upload_status = SupplierInventory::VEH_STATUS_DELETED;
                            $deletedRow->save();
                        }

                        info("updated rows");
                        info($updatedRowsIds);

                        info("newly added row");
                        info($newlyAddedRowIds);

                        info("no change row Ids");
                        info($noChangeRowIds);

                    DB::commit();

                    $pdf = Pdf::loadView('supplier_inventories.reports', compact('newlyAddedRows',
                        'updatedRows','deletedRows'));
                    return $pdf->download('report.pdf');

                }
                else{

                     DB::beginTransaction();

                    $supplierInventoryHistories = SupplierInventoryHistory::all();

                    foreach ($supplierInventoryHistories as $inventoryHistory) {
                        $inventoryHistory->upload_status = SupplierInventory::UPLOAD_STATUS_INACTIVE;
                        $inventoryHistory->save();
                    }

                    foreach ($uploadFileContents as $uploadFileContent)
                    {
                        $model = MasterModel::where('model', $uploadFileContent['model'])
                            ->where('sfx', $uploadFileContent['sfx'])
                            ->where('steering', $uploadFileContent['steering'])
                            ->where('model_year', $uploadFileContent['model_year'])
                            ->first();

                        $supplierInventory = new SupplierInventory();

                        $supplierInventory->master_model_id = $model->id;
                        $supplierInventory->chasis          = $uploadFileContent['chasis'];
                        $supplierInventory->engine_number   = $uploadFileContent['engine_number'];
                        $supplierInventory->color_code      = $uploadFileContent['color_code'];
                        $supplierInventory->pord_month      = $uploadFileContent['pord_month'];
                        $supplierInventory->po_arm          = $uploadFileContent['po_arm'];
                        $supplierInventory->eta_import      = $uploadFileContent['eta_import'];
                        $supplierInventory->is_add_new     	= !empty($request->is_add_new) ? true : false;
                        $supplierInventory->supplier_id     = $uploadFileContent['supplier_id'];
                        $supplierInventory->whole_sales	    = $uploadFileContent['whole_sales'];
                        $supplierInventory->country     	= $uploadFileContent['country'];
                        $supplierInventory->delivery_note   = $uploadFileContent['delivery_note'];
                        $supplierInventory->date_of_entry   = $date;
                        $supplierInventory->upload_status   = SupplierInventory::UPLOAD_STATUS_ACTIVE;
                        $supplierInventory->veh_status      = SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY;
                        $supplierInventory->interior_color_code_id = $uploadFileContent['interior_color_code_id'];
                        $supplierInventory->exterior_color_code_id = $uploadFileContent['exterior_color_code_id'];
                        if($uploadFileContent['delivery_note']) {
                            $supplierInventory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                        }
                        $supplierInventory->save();

                        $supplierInventoryHistory = new SupplierInventoryHistory();

                        $supplierInventoryHistory->master_model_id = $model->id;
                        $supplierInventoryHistory->chasis          = $uploadFileContent['chasis'];
                        $supplierInventoryHistory->engine_number   = $uploadFileContent['engine_number'];
                        $supplierInventoryHistory->color_code      = $uploadFileContent['color_code'];
                        $supplierInventoryHistory->pord_month      = $uploadFileContent['pord_month'];
                        $supplierInventoryHistory->po_arm          = $uploadFileContent['po_arm'];
                        $supplierInventoryHistory->eta_import      = $uploadFileContent['eta_import'];
                        $supplierInventoryHistory->is_add_new      = !empty($request->is_add_new) ? true : false;
                        $supplierInventoryHistory->supplier_id     = $uploadFileContent['supplier_id'];
                        $supplierInventoryHistory->whole_sales	   = $uploadFileContent['whole_sales'];
                        $supplierInventoryHistory->country     	   = $uploadFileContent['country'];
                        $supplierInventoryHistory->delivery_note   = $uploadFileContent['delivery_note'];
                        $supplierInventoryHistory->date_of_entry   = $date;
                        $supplierInventoryHistory->upload_status   = SupplierInventory::UPLOAD_STATUS_ACTIVE;
                        $supplierInventoryHistory->veh_status      = SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY;
                        $supplierInventoryHistory->interior_color_code_id = $uploadFileContent['interior_color_code_id'];
                        $supplierInventoryHistory->exterior_color_code_id = $uploadFileContent['exterior_color_code_id'];
                        if($uploadFileContent['delivery_note']) {
                            $supplierInventory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                        }
                        $supplierInventoryHistory->save();

                    }

                    DB::commit();

                    return redirect()->route('supplier-inventories.create')->with('message','supplier inventory updated successfully');
                }
            }

        }
    }
    public function updateInventory(Request $request) {

        $updatedDatas = $request->selectedUpdatedDatas;

        foreach ($updatedDatas as $data) {
            $inventoryId = $data['id'];
            $fieldName = $data['field'];
            $fieldValue = $data['value'];

            $inventory = SupplierInventory::find($inventoryId);
            if($fieldName == 'model_year') {

                $masterModel = MasterModel::where('model', $inventory->masterModel->model)
                    ->where('sfx', $inventory->masterModel->sfx)
                    ->where('model_year', $fieldValue)
                    ->first();
                $inventory->master_model_id = $masterModel->id;
            }else if($fieldName == 'eta_import') {

                $inventory->$fieldName = Carbon::parse($fieldValue)->format('Y-m-d');
            }
            else if($fieldName == 'pord_month') {
                $modelYear = substr($fieldValue, 0, -2);
                $productionMonth = substr($fieldValue, -2);
                $modelYearCalculationCategories = ModelYearCalculationCategory::all();
                foreach ($modelYearCalculationCategories as $modelYearCalculationCategory) {
                    $isItemExistCategory = MasterModel::select(['id', 'model', 'sfx', 'variant_id'])
                        ->where('model', $inventory->masterModel->model)
                        ->where('sfx', $inventory->masterModel->sfx)
                        ->with('variant.master_model_lines')
                        ->whereHas('variant.master_model_lines', function ($query) use ($modelYearCalculationCategory) {
                            $query->where('model_line', 'LIKE', '%' . $modelYearCalculationCategory->name . '%');
                        });

                    if ($isItemExistCategory->count() > 0) {
                        $correspondingCategoryRuleValue = $modelYearCalculationCategory->modelYearRule->value ?? 0;
                        if ($productionMonth > $correspondingCategoryRuleValue) {
                            if ($fieldValue){
                                $modelYear = substr($fieldValue, 0, -2) + 1;
                            }
                            break;
                        }
                    }
                }

                $masterModel =  MasterModel::where('model', $inventory->masterModel->model)
                    ->where('sfx', $inventory->masterModel->sfx)
                    ->where('model_year',  $modelYear)
                    ->first();

                $inventory->$fieldName = $fieldValue;
                $inventory->master_model_id = $masterModel->id;
            }else if($fieldName == 'color_code') {
                if($fieldValue) {
                    $colourcode = $fieldValue;
                    $colourcodecount = strlen($fieldValue);

                    if ($colourcodecount == 5) {
                        $extColour = substr($colourcode, 0, 3);
                        $intColour = substr($colourcode,  -2);

                    }
                    if ($colourcodecount == 4) {

                        $altercolourcode = "0" . $colourcode;
                        $extColour = substr($altercolourcode, 0, 3);
                        $intColour = substr($altercolourcode, -2);
                        $fieldValue = $extColour.''.$intColour;
                    }
                    if($extColour) {
                        $extColourRow = ColorCode::where('code', $extColour)
                            ->where('belong_to', ColorCode::EXTERIOR)
                            ->first();
                        $exteriorColor = "";
                        if ($extColourRow)
                        {
                            $exteriorColor = $extColourRow->name;
                            $exteriorColorId = $extColourRow->id;
                        }
                    }
                    if($intColour) {
                        $intColourRow = ColorCode::where('code', $intColour)
                            ->where('belong_to', ColorCode::INTERIOR)
                            ->first();
                        $interiorColor = "";
                        if ($intColourRow)
                        {
                            $interiorColor = $intColourRow->name;
                            $interiorColorId = $intColourRow->id;
                        }
                    }
                    $inventory->$fieldName = $fieldValue;
                    $inventory->interior_color_code_id = $interiorColorId;
                    $inventory->exterior_color_code_id = $exteriorColorId;
                }
            }else if($fieldName == 'delivery_note') {
                $inventory->$fieldName = $fieldValue;
                $inventory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
            }
            else{
               $inventory->$fieldName = $fieldValue;
            }

            $inventory->save();
        }
        return response(true);
    }
    public function FileComparision(Request $request) {
        (new UserActivityController)->createActivity('Open Supplier Inventories File Comparison Page');

        $newlyAddedRows = [];
        $deletedRows = [];
        $updatedRows = [];
        $suppliers = Supplier::with('supplierTypes')
            ->whereHas('supplierTypes', function ($query) {
                $query->where('supplier_type', Supplier::SUPPLIER_TYPE_DEMAND_PLANNING);
            })
            ->where('status', Supplier::SUPPLIER_STATUS_ACTIVE)
            ->get();

        return view('supplier_inventories.file_comparision',compact('suppliers',
            'newlyAddedRows', 'deletedRows','updatedRows'));
    }
    public function FileComparisionReport(Request $request)
    {
//        dd($request->all());
        (new UserActivityController)->createActivity('Supplier Inventories File Compared');

        $request->validate([
            'first_file' => 'date',
            'second_file' =>' date|after:first_file',
        ]);

        $suppliers = Supplier::with('supplierTypes')
            ->whereHas('supplierTypes', function ($query) {
                $query->where('supplier_type', Supplier::SUPPLIER_TYPE_DEMAND_PLANNING);
            })
            ->where('status', Supplier::SUPPLIER_STATUS_ACTIVE)
            ->get();
        $newlyAddedRows = [];
        $updatedRows = [];
        $updatedRowsIds = [];
        $noChangeRowIds = [];
        $i = 0;

        $firstFileRowDetails = SupplierInventoryHistory::whereDate('date_of_entry', $request->first_file)
//            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
            ->where('supplier_id', $request->supplier_id)
            ->where('whole_sales', $request->whole_sales)
            ->whereNull('chasis')
            ->get();
        $secondFileRowDetails = SupplierInventoryHistory::whereDate('date_of_entry', $request->second_file)
//            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
//            ->whereNull('delivery_note')
            ->where('supplier_id', $request->supplier_id)
            ->where('whole_sales', $request->whole_sales)
            ->get();

        $firstFileItems = [];
        $secondFileItems = [];

        foreach ($firstFileRowDetails as $firstFileRowDetail) {
            $firstFileItems[] = $firstFileRowDetail->masterModel->model .'_'.$firstFileRowDetail->masterModel->sfx;
        }
        $secondFileDetails = $secondFileRowDetails->whereNull('chasis');
        foreach ($secondFileDetails as $secondFileDetail) {
            $secondFileItems[] = $secondFileDetail->masterModel->model .'_'.$secondFileDetail->masterModel->sfx;
        }

        $secondFileItemPairs =  array_count_values($secondFileItems);
        $firstFileItemPairs = array_count_values($firstFileItems);

        foreach ($secondFileRowDetails as $secondFileRowDetail)
        {
            info($secondFileRowDetail['model']);
            info($secondFileRowDetail['sfx']);
            $model = $secondFileRowDetail->masterModel->model;
            $sfx = $secondFileRowDetail->masterModel->sfx;

            $modelIds = MasterModel::where('model', $model)
                ->where('sfx', $sfx)
                ->pluck('id')->toArray();

            $deliveryNote = $secondFileRowDetail['delivery_note'];
            // after having DN data their is no changes for data of thata ro.so consider the data without eta import for inventory
            $supplierInventories = SupplierInventoryHistory::whereIn('master_model_id', $modelIds)
                            ->whereDate('date_of_entry', $request->first_file)
//                            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                            ->where('supplier_id', $request->supplier_id)
                            ->where('whole_sales', $request->whole_sales)
                            ->where(function ($query) use($deliveryNote) {
                                $query->whereNull('delivery_note')
                                    ->orwhere('delivery_note', $deliveryNote);
                            });

            if ($supplierInventories->count() <= 0)
            {
                info("no row existing with model,sfx model year so => add new row");

                // model and sfx not existing in Suplr Invtry => new row
                $newlyAddedRows[$i]['model'] = $model;
                $newlyAddedRows[$i]['sfx'] = $sfx;
                $newlyAddedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                $newlyAddedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                $newlyAddedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];

            } else {
                $isSimilarRowExist =  $supplierInventories->where('chasis', $secondFileRowDetail['chasis'])
                    ->where('engine_number', $secondFileRowDetail['engine_number'])
                    ->where('color_code', $secondFileRowDetail['color_code'])
                    ->where('pord_month', $secondFileRowDetail['pord_month'])
                    ->where('po_arm', $secondFileRowDetail['po_arm'])
                    ->whereNotIn('id', $noChangeRowIds)
                    ->whereNotIn('id', $updatedRowsIds)
                    ->where('eta_import', $secondFileRowDetail['eta_import'])
                    ->where('delivery_note', $secondFileRowDetail['delivery_note'])
                    ->first();

                info($isSimilarRowExist);
                info("model sfx modelyear combination row existing");
                if(empty($isSimilarRowExist)) {
                    if (!empty($secondFileRowDetail['chasis']))
                    {
                        info($secondFileRowDetail['chasis']);
                        info($modelIds);
                        // Store the Count into Update the Row with data
                        $supplierInventory = SupplierInventoryHistory::whereIn('master_model_id', $modelIds)
                            ->whereDate('date_of_entry', $request->first_file)
//                            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                            ->where('supplier_id', $request->supplier_id)
                            ->where('whole_sales', $request->whole_sales)
                            ->whereNotIn('id', $noChangeRowIds)
                            ->whereNotIn('id', $updatedRowsIds)
                            ->where('chasis', $secondFileRowDetail['chasis'])
//                                        ->whereNull('delivery_note') unable to find row when have delivery note
                            ->where(function ($query) use($deliveryNote) {
                                $query->whereNull('delivery_note')
                                    ->orwhere('delivery_note', $deliveryNote);
                            })
                            ->first();

                        info($supplierInventory);

                        if (empty($supplierInventory)) {
                            info("chasis matching row not avaialble, case of chasis updation ore new chaisi row add");
                            //adding new row simply
                            $isNullChaisis = SupplierInventoryHistory::whereIn('master_model_id', $modelIds)
                                ->whereDate('date_of_entry', $request->first_file)
//                                ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                                ->where('supplier_id', $request->supplier_id)
                                ->where('whole_sales', $request->whole_sales)
                                ->whereNotIn('id', $noChangeRowIds)
                                ->whereNotIn('id', $updatedRowsIds)
                                ->whereNull('chasis')
                                ->where(function ($query) use($deliveryNote) {
                                    $query->whereNull('delivery_note')
                                        ->orwhere('delivery_note', $deliveryNote);
                                });
                            $isNullChaisisIds = $isNullChaisis->pluck('id');

                            info($isNullChaisis->get());
                            if ($isNullChaisis->count() > 0) {
                                info("null chasis row exist");
                                // find the null chaisis row
                                // null chaisis existing => updating row
                                // find the row with similar details.
                                $SimilarRowWithNullChaisis = $isNullChaisis->where('engine_number', $secondFileRowDetail['engine_number'])
                                    ->where('color_code', $secondFileRowDetail['color_code'])
                                    ->where('pord_month', $secondFileRowDetail['pord_month'])
                                    ->where('po_arm', $secondFileRowDetail['po_arm'])
                                    ->where('eta_import', $secondFileRowDetail['eta_import'])
                                    ->first();
                                info($SimilarRowWithNullChaisis);
                                if(!empty($SimilarRowWithNullChaisis)) {
                                    info("null chasis with smilar data exist");

                                    $updatedRowsIds[] = $SimilarRowWithNullChaisis->id;
                                    $updatedRows[$i]['model'] = $model;
                                    $updatedRows[$i]['sfx'] = $sfx;
                                    $updatedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                                    $updatedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                                    $updatedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];

                                }else{
                                    info("null chasis smilar data not exist => check if any similar model sfx row without having any update yet.");

                                    info($updatedRowsIds);

                                    $rowWithoutUpdate = SupplierInventoryHistory::whereIn('id', $isNullChaisisIds)
                                        ->whereDate('date_of_entry', $request->first_file)
                                        ->whereNotIn('id', $updatedRowsIds)
                                        ->whereNotIn('id', $noChangeRowIds)->first();

                                    info($rowWithoutUpdate);

                                    if(!empty($rowWithoutUpdate)) {
                                        info("update row");
                                        $updatedRowsIds[] = $rowWithoutUpdate->id;

                                        $updatedRows[$i]['model'] = $model;
                                        $updatedRows[$i]['sfx'] = $sfx;
                                        $updatedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                                        $updatedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                                        $updatedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];
                                    }else{
                                        info("no row found to update => add new");
                                        $newlyAddedRows[$i]['model'] = $secondFileRowDetail['model'];
                                        $newlyAddedRows[$i]['sfx'] = $secondFileRowDetail['sfx'];
                                        $newlyAddedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                                        $newlyAddedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                                        $newlyAddedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];

//                                        $newlyAddedRowIds[] = $supplierInventory->id;
                                    }
                                }

                            } else {
                                info("new chaisis with existing model and sfx => add row");
                                // new chaisis with existing model and sfx => add row ,
                                $newlyAddedRows[$i]['model'] = $model;
                                $newlyAddedRows[$i]['sfx'] = $sfx;
                                $newlyAddedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                                $newlyAddedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                                $newlyAddedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];

//                                $newlyAddedRowIds[] = $supplierInventory->id;
                            }
                        }
                        else
                        {
                            info("inventory with chasis existing.");
                            // inventory with chasis existing...

                            if(!empty($secondFileRowDetail['delivery_note'])) {
                                $supplierInventories = $supplierInventories->where('delivery_note', $secondFileRowDetail['delivery_note']);
                            }
                            $supplierInventories = $supplierInventories->where('engine_number', $secondFileRowDetail['engine_number'])
                                ->where('color_code', $secondFileRowDetail['color_code'])
                                ->where('pord_month', $secondFileRowDetail['pord_month'])
                                ->where('po_arm', $secondFileRowDetail['po_arm'])
                                ->where('eta_import', $secondFileRowDetail['eta_import'])
                                ->first();

                            if (!$supplierInventories)
                            {
                                info("chasis with detail row not exist update row");
                                // chasis existing our system so get corresponding inventory when engine number is not matching
                                $updatedRowsIds[]                   = $supplierInventory->id;
                                $updatedRows[$i]['model']           = $model;
                                $updatedRows[$i]['sfx']             = $sfx;
                                $updatedRows[$i]['model_year']      = $secondFileRowDetail['model_year'];
                                $updatedRows[$i]['chasis']          = $secondFileRowDetail['chasis'];
                                $updatedRows[$i]['engine_number']   = $secondFileRowDetail['engine_number'];
                                $updatedRows[$i]['color_code']      = $secondFileRowDetail['color_code'];
                            }
                        }
                    }
                    else {
                        info("no chasis found=> chcek for updation or add new row");
                        $modelSfxValuePair = $model."_".$sfx;

                        $secondFilePairCount = $secondFileItemPairs[$modelSfxValuePair];
                        if(array_key_exists($modelSfxValuePair, $firstFileItemPairs)) {
                            $firstFilePairCount = $firstFileItemPairs[$modelSfxValuePair];

                        }else{
                            $firstFilePairCount = 0;

                        }

                        if($secondFilePairCount == $firstFilePairCount) {
                            $inventoryRow = SupplierInventoryHistory::whereIn('master_model_id', $modelIds)
                                ->whereDate('date_of_entry', $request->first_file)
//                                ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                                ->where('supplier_id', $request->supplier_id)
                                ->where('whole_sales', $request->whole_sales)
                                ->whereNull('chasis')
                                ->whereNotIn('id', $noChangeRowIds)
                                ->whereNotIn('id', $updatedRowsIds)
//                                ->whereNotIn('id', $newlyAddedRowIds)
                                ->where(function ($query) use($deliveryNote) {
                                    $query->whereNull('delivery_note')
                                        ->orwhere('delivery_note', $deliveryNote);
                                })
                                ->first();
                            if(!empty($inventoryRow)) {
                                $updatedRowsIds[] = $inventoryRow->id;

                                $updatedRows[$i]['model'] = $model;
                                $updatedRows[$i]['sfx'] = $sfx;
                                $updatedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                                $updatedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                                $updatedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];
                            }

                            info("both colum count equal => clear case of row updation find the row ");

                        }else if($secondFilePairCount > $firstFilePairCount) {
                            // check for engine number is changed or not
                            $nullChasisRow = SupplierInventoryHistory::whereIn('master_model_id', $modelIds)
                                ->whereDate('date_of_entry', $request->first_file)
//                                ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                                ->where('supplier_id', $request->supplier_id)
                                ->where('whole_sales', $request->whole_sales)
                                ->whereNull('chasis')
                                ->whereNotIn('id', $noChangeRowIds)
                                ->whereNotIn('id', $updatedRowsIds)
//                                ->whereNotIn('id', $newlyAddedRowIds)
                                ->first();

                            if(!empty($nullChasisRow)) {
                                info("no existing row with chasis found => update row");
                                $updatedRowsIds[] = $nullChasisRow->id;

                                $updatedRows[$i]['model'] = $model;
                                $updatedRows[$i]['sfx'] = $sfx;
                                $updatedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                                $updatedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                                $updatedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];

                            }else{
                                info("no existing row with no chasis found => add new row");

                                $newlyAddedRows[$i]['model'] = $model;
                                $newlyAddedRows[$i]['sfx'] = $sfx;
                                $newlyAddedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                                $newlyAddedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                                $newlyAddedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];

//                                $newlyAddedRowIds[] = $supplierInventory->id;
                                info("coming row count is > existing chance for adding row also check any row updation is there");
                            }

                        }else{
                            $nullChasisRow = SupplierInventoryHistory::whereIn('master_model_id', $modelIds)
                                ->whereDate('date_of_entry', $request->first_file)
//                                ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                                ->where('supplier_id', $request->supplier_id)
                                ->where('whole_sales', $request->whole_sales)
                                ->whereNull('chasis')
                                ->whereNotIn('id', $noChangeRowIds)
                                ->whereNotIn('id', $updatedRowsIds)
//                                ->whereNotIn('id', $newlyAddedRowIds)
                                ->first();

                            if(!empty($nullChasisRow)) {
                                info("no existing row with chasis found => update row");
                                $updatedRowsIds[] = $nullChasisRow->id;

                                $updatedRows[$i]['model'] = $model;
                                $updatedRows[$i]['sfx'] = $sfx;
                                $updatedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                                $updatedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                                $updatedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];
                            }

                        }
                    }
                }else{

                    $noChangeRowIds[] = $isSimilarRowExist->id;
                }

            }$i++;
        }

        $deletedRows = SupplierInventoryHistory::whereDate('date_of_entry', $request->first_file)
            ->where('supplier_id', $request->supplier_id)
            ->where('whole_sales', $request->whole_sales)
            ->whereNotIn('id', $noChangeRowIds)
            ->whereNotIn('id', $updatedRowsIds)
//            ->whereNotIn('id', $newlyAddedRowIds)
            ->get();

//        return $deletedRows;

//        foreach ($secondFileRowDetails as $secondFileRowDetail)
//        {
//            $masterModel = MasterModel::find($secondFileRowDetail['master_model_id']);
//            $masterModelIds = MasterModel::where('model', $masterModel->model)
//                ->where('sfx', $masterModel->sfx)->pluck('id')->toArray();
//
//            $supplierInventories = SupplierInventory::whereDate('date_of_entry', $request->first_file)
//                ->whereIn('master_model_id', $masterModelIds)
//                ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
//                ->where('supplier_id', $request->supplier_id)
//                ->where('whole_sales', $request->whole_sales)
//                ->whereNull('delivery_note');
//
//            if ($supplierInventories->count() <= 0) {
//                // model and sfx not existing in Suplr Invtry => new row
//                $newlyAddedRows[$i]['model'] = $masterModel->model;
//                $newlyAddedRows[$i]['sfx'] = $masterModel->sfx;
//                $newlyAddedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
//                $newlyAddedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
//                $newlyAddedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];
//                $newlyAddedRows[$i]['pord_month'] = $secondFileRowDetail['pord_month'];
//                $newlyAddedRows[$i]['po_arm'] = $secondFileRowDetail['po_arm'];
//                $newlyAddedRows[$i]['eta_import'] = $secondFileRowDetail['eta_import'];
//
//            } else {
//                if (!empty($secondFileRowDetail['chasis'])) {
//                    // Store the Count into Update the Row with data
//                    $supplierInventory = $supplierInventories->where('chasis', $secondFileRowDetail['chasis'])
//                        ->first();
//                    $isNullChaisis = SupplierInventory::whereDate('date_of_entry', $request->first_file)
//                        ->whereIn('master_model_id', $masterModelIds)
//                        ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
//                        ->whereNull('delivery_note')
//                        ->where('supplier_id', $request->supplier_id)
//                        ->where('whole_sales', $request->whole_sales)
//                        ->whereNull('chasis');
//
//                    if (!$supplierInventory) {
//                        //adding new row simply
//                        if (!empty($isNullChaisis->first())) {
//                            // null chaisis existing => updating row
//                            $chasisUpdatedRow = $isNullChaisis->whereNotIn('id',$chasisUpdatedRowIds)->first();
//                            $chasisUpdatedRowIds[] = $chasisUpdatedRow->id;
//                            $isNullChaisis = $isNullChaisis->first();
//
//                            $updatedRowsIds[] = $isNullChaisis->id;
//                            $updatedRows[$i]['model'] = $masterModel->model;
//                            $updatedRows[$i]['sfx'] = $masterModel->sfx;
//                            $updatedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
//                            $updatedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
//                            $updatedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];
//                            $updatedRows[$i]['pord_month'] = $secondFileRowDetail['pord_month'];
//                            $updatedRows[$i]['po_arm'] = $secondFileRowDetail['po_arm'];
//                            $updatedRows[$i]['eta_import'] = $secondFileRowDetail['eta_import'];
//                        } else {
//                            // new chaisis with existing model and sfx => add row ,
//                            $newlyAddedRows[$i]['model'] = $masterModel->model;
//                            $newlyAddedRows[$i]['sfx'] = $masterModel->sfx;
//                            $newlyAddedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
//                            $newlyAddedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
//                            $newlyAddedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];
//                        }
//                    } else
//                    {
//
//
//                    }
//                } else
//                {
//                    $nullChaisisCount = SupplierInventory::whereDate('date_of_entry', $request->first_file)
//                        ->whereIn('master_model_id',$masterModelIds)
//                        ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
//                        ->where('supplier_id', $request->supplier_id)
//                        ->where('whole_sales', $request->whole_sales)
//                        ->whereNotIn('id', $chasisUpdatedRowIds)
//                        ->whereNull('chasis')
//                         ->whereNull('delivery_note')
//                        ->count();
//                    $modelSfxValuePair = $masterModel->model."_".$masterModel->sfx;
//                    $countblankchasis[] = $modelSfxValuePair;
//                    $groupedCountValue =  array_count_values($countblankchasis);
//                    if ($groupedCountValue[$modelSfxValuePair] > $nullChaisisCount)
//                    {
//                        $newlyAddedRows[$i]['model'] = $masterModel->model;
//                        $newlyAddedRows[$i]['sfx'] = $masterModel->sfx;
//                        $newlyAddedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
//                        $newlyAddedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
//                        $newlyAddedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];
//
//                    }else
//                    {
//                        $supplierInventory = $supplierInventories->whereNull('chasis')->first();
//                        $supplierInventory1 = $supplierInventories->whereNull('chasis')
//                            ->where('engine_number', $secondFileRowDetail['engine_number'])
//                            ->first();
//                        if (!$supplierInventory1)
//                        {
//                            $updatedRowsIds[] = $supplierInventory->id;
//                            $updatedRows[$i]['model'] = $masterModel->model;
//                            $updatedRows[$i]['sfx'] = $masterModel->sfx;
//                            $updatedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
//                            $updatedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
//                            $updatedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];
//                            $updatedRows[$i]['pord_month'] = $secondFileRowDetail['pord_month'];
//                            $updatedRows[$i]['po_arm'] = $secondFileRowDetail['po_arm'];
//                            $updatedRows[$i]['eta_import'] = $secondFileRowDetail['eta_import'];
//
//                        }else
//                        {
//                            $supplierInventory2 = $supplierInventories->whereNull('chasis')
//                                ->where('color_code', $secondFileRowDetail['color_code'])
//                                ->first();
//                            if (!$supplierInventory2)
//                            {
//                                $updatedRowsIds[] = $supplierInventory1->id;
//                                $updatedRows[$i]['model'] = $masterModel->model;
//                                $updatedRows[$i]['sfx'] = $masterModel->sfx;
//                                $updatedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
//                                $updatedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
//                                $updatedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];
//                                $updatedRows[$i]['pord_month'] = $secondFileRowDetail['pord_month'];
//                                $updatedRows[$i]['po_arm'] = $secondFileRowDetail['po_arm'];
//                                $updatedRows[$i]['eta_import'] = $secondFileRowDetail['eta_import'];
//
//                            } else {
//                                $supplierInventory3 = $supplierInventories->whereNull('chasis')
//                                    ->where('pord_month', $secondFileRowDetail['pord_month'])
//                                    ->first();
//                                if (!$supplierInventory3)
//                                {
//                                    $updatedRowsIds[] = $supplierInventory2->id;
//                                    $updatedRows[$i]['model'] = $masterModel->model;
//                                    $updatedRows[$i]['sfx'] = $masterModel->sfx;
//                                    $updatedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
//                                    $updatedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
//                                    $updatedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];
//                                    $updatedRows[$i]['pord_month'] = $secondFileRowDetail['pord_month'];
//                                    $updatedRows[$i]['po_arm'] = $secondFileRowDetail['po_arm'];
//                                    $updatedRows[$i]['eta_import'] = $secondFileRowDetail['eta_import'];
//
//                                }else{
//                                    $supplierInventory4 = $supplierInventories->whereNull('chasis')
//                                        ->where('po_arm', $secondFileRowDetail['po_arm'])
//                                        ->first();
//                                    if (!$supplierInventory4)
//                                    {
//                                        $updatedRowsIds[] = $supplierInventory3->id;
//                                        $updatedRows[$i]['model'] = $masterModel->model;
//                                        $updatedRows[$i]['sfx'] = $masterModel->sfx;
//                                        $updatedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
//                                        $updatedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
//                                        $updatedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];
//                                        $updatedRows[$i]['pord_month'] = $secondFileRowDetail['pord_month'];
//                                        $updatedRows[$i]['po_arm'] = $secondFileRowDetail['po_arm'];
//                                        $updatedRows[$i]['eta_import'] = $secondFileRowDetail['eta_import'];
//
//                                    }else{
//                                        if (!empty($secondFileRowDetail['eta_import'])) {
//                                            $supplierInventory5 = $supplierInventories->whereNull('chasis')
//                                                ->whereDate('eta_import', $secondFileRowDetail['eta_import'])
//                                                ->first();
//                                            if (!$supplierInventory5)
//                                            {
//                                                $updatedRowsIds[] = $supplierInventory4->id;
//                                                $updatedRows[$i]['model'] = $masterModel->model;
//                                                $updatedRows[$i]['sfx'] = $masterModel->sfx;
//                                                $updatedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
//                                                $updatedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
//                                                $updatedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];
//                                                $updatedRows[$i]['pord_month'] = $secondFileRowDetail['pord_month'];
//                                                $updatedRows[$i]['po_arm'] = $secondFileRowDetail['po_arm'];
//                                                $updatedRows[$i]['eta_import'] = $secondFileRowDetail['eta_import'];
//                                            }
//                                        }
//                                    }
//                                }
//                            }
//                        }
//                    }
//                }
//            }$i++;
//        }
//
//        foreach ($firstFileRowDetails as $firstFileRowDetail)
//        {
//            $masterModel = MasterModel::find($firstFileRowDetail['master_model_id']);
//            $firstFileValuePairs[] =  $masterModel->model."_".$masterModel->sfx."_".$firstFileRowDetail['chasis']."_".
//                $firstFileRowDetail['engine_number']."_".$firstFileRowDetail['color_code']."_".$firstFileRowDetail['pord_month']."_".
//                $firstFileRowDetail['po_arm'];
//        }
//        // to find deleted rows
//        // group the value pair to get count of duplicate data
//        $groupedFirstFileCount =  array_count_values($firstFileValuePairs);
//        $j=0;
//        $updatedDetails = [];
//        foreach ($updatedRowsIds as $updatedRowsId) {
//            $supplierInventory = SupplierInventory::find($updatedRowsId);
//            $updatedDetails[] =  $supplierInventory->masterModel->model."_".$supplierInventory->masterModel->sfx."_".$supplierInventory->chasis."_".
//                $supplierInventory->engine_number."_".$supplierInventory->color_code."_".$supplierInventory->pord_month."_".
//                $supplierInventory->po_arm;
//        }
//        $chasisUpdatedRows = [];
//        if(!empty($chasisUpdatedRowIds)) {
//            foreach ($chasisUpdatedRowIds as $chasisUpdatedRowId) {
//                $supplierInventory = SupplierInventory::find($chasisUpdatedRowId);
//                $chasisUpdatedRows[] =  $supplierInventory->masterModel->model."_".$supplierInventory->masterModel->sfx."_".$supplierInventory->chasis."_".
//                    $supplierInventory->engine_number."_".$supplierInventory->color_code."_".$supplierInventory->pord_month."_".
//                    $supplierInventory->po_arm;
//            }
//            $updatedChasisGroupedCount =  array_count_values($chasisUpdatedRows);
//        }
//
//        foreach ($firstFileRowDetails as $firstFileRowDetail)
//        {
//            $date =  Carbon::parse($request->second_file)->format('Y-m-d');
//            $masterModel = MasterModel::find($firstFileRowDetail['master_model_id']);
//            $isExistSupplier = SupplierInventory::whereDate('date_of_entry', $date)
//                ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
//                ->where('supplier_id', $request->supplier_id)
//                ->where('whole_sales', $request->whole_sales)
//                ->where('master_model_id', $firstFileRowDetail['master_model_id'])
//                ->where('chasis', $firstFileRowDetail['chasis'])
//                ->where('engine_number', $firstFileRowDetail['engine_number'])
//                ->where('color_code', $firstFileRowDetail['color_code'])
//                ->where('pord_month', $firstFileRowDetail['pord_month'])
//                ->where('po_arm', $firstFileRowDetail['po_arm']);
////                ->whereNull('delivery_note');
//                $firstFileRow = $masterModel->model."_".$masterModel->sfx."_".$firstFileRowDetail['chasis']."_".
//                                $firstFileRowDetail['engine_number']."_".$firstFileRowDetail['color_code']."_".
//                                $firstFileRowDetail['pord_month']."_". $firstFileRowDetail['po_arm'];
////                                ->whereNull('delivery_note')
//            if ($isExistSupplier->count() > 0)
//            {
//                if ($isExistSupplier->count() != $groupedFirstFileCount[$firstFileRow])
//                {
//                    $secondFileRowCount = $isExistSupplier->count();
//                    if (!empty($chasisUpdatedRows))
//                    {
//                        if (in_array($firstFileRow,$chasisUpdatedRows))
//                        {
//                            $groupedFirstFileCount[$firstFileRow] = $groupedFirstFileCount[$firstFileRow] - $updatedChasisGroupedCount[$firstFileRow];
//                        }
//                    }
//                    if ($secondFileRowCount < $groupedFirstFileCount[$firstFileRow])
//                    {
//                         $deletedRowCount = $groupedFirstFileCount[$firstFileRow] - $secondFileRowCount;
//                         $row = $isExistSupplier->first();
//                         for($i=0;$i<$deletedRowCount;$i++) {
//                             $deletedRows[$i]['model'] = $masterModel->model;
//                             $deletedRows[$i]['sfx'] = $masterModel->sfx;
//                             $deletedRows[$i]['chasis'] = $row->chasis;
//                             $deletedRows[$i]['engine_number'] = $row->engine_number;
//                             $deletedRows[$i]['color_code'] = $row->color_code;
//                         }
//                    }
//                }
//            }else{
//                if (!in_array($firstFileRow, $updatedDetails ))
//                {
//                    $deletedRows[$j]['model'] = $masterModel->model;
//                    $deletedRows[$j]['sfx'] = $masterModel->sfx;
//                    $deletedRows[$j]['chasis'] = $firstFileRowDetail['chasis'];
//                    $deletedRows[$j]['engine_number'] = $firstFileRowDetail['engine_number'];
//                    $deletedRows[$j]['color_code'] = $firstFileRowDetail['color_code'];
//                }
//            }
//            $j++;
//        }
        return view('supplier_inventories.file_comparision',compact('newlyAddedRows',
            'deletedRows','updatedRows','suppliers'));
    }
    public function lists(Request $request) {

        $request->validate([
            'start_date' => 'date',
            'end_date' =>' date|after:start_date',
        ]);

        $startDate = '';
        $endDate = ' ';
        $supplierInventories = SupplierInventory::with('masterModel')
            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
//            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
            ->whereNull('delivery_note')
            ->groupBy('master_model_id');

        if (!empty($request->start_date) && !empty($request->end_date)) {
            $startDate = Carbon::parse($request->start_date)->format('Y-m-d');
            $endDate =  Carbon::parse($request->end_date)->format('Y-m-d');

            $supplierInventories =  $supplierInventories->whereBetween('date_of_entry',[$startDate,$endDate]);
        }
        $supplierInventories = $supplierInventories->get();

        return view('supplier_inventories.list', compact('supplierInventories','startDate','endDate'));
    }
    public function getDate(Request $request)
    {
        $supplierInventoryDates = SupplierInventoryHistory::where('supplier_id', $request->supplier_id)
            ->where('whole_sales', $request->wholesaler)
            ->groupBy('date_of_entry')
            ->pluck('date_of_entry');

        return $supplierInventoryDates;
    }

    public function checkChasisUnique(Request $request) {

        $isChasisExist = SupplierInventory::where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
            ->where('chasis',  $request->chasis)
            ->first();

        if($isChasisExist) {
            $data = 1;
        }else{
            $data = 0;
        }

        return response($data);
    }
    public function checkProductionMonth(Request $request) {

        $supplierInventory = SupplierInventory::find($request->id);

        $production_month = $request->prod_month;

        $modelYear = substr($production_month, 0, -2);
        $productionMonth = substr($production_month, -2);
        $modelYearCalculationCategories = ModelYearCalculationCategory::all();

        foreach ($modelYearCalculationCategories as $modelYearCalculationCategory) {

            $isItemExistCategory = MasterModel::select(['id', 'model', 'sfx', 'variant_id'])
                ->where('model', $supplierInventory->masterModel->model)
                ->where('sfx', $supplierInventory->masterModel->sfx)
                ->with('variant.master_model_lines')
                ->whereHas('variant.master_model_lines', function ($query) use ($modelYearCalculationCategory) {
                    $query->where('model_line', 'LIKE', '%' . $modelYearCalculationCategory->name . '%');
                });

            if ($isItemExistCategory->count() > 0) {

                $correspondingCategoryRuleValue = $modelYearCalculationCategory->modelYearRule->value ?? 0;

                if ($productionMonth > $correspondingCategoryRuleValue) {

                    if ($production_month){
                        $modelYear = substr($production_month, 0, -2) + 1;
                    }
                    break;
                }
            }
        }

       $isExistModelCombination =  MasterModel::where('model', $supplierInventory->masterModel->model)
            ->where('sfx', $supplierInventory->masterModel->sfx)
            ->where('model_year', $modelYear)
            ->first();

           if($isExistModelCombination) {
               $data = 1;
           }else{
               $data = $modelYear;
           }

       return response($data);
    }
    public function isExistColorCode(Request $request) {

        $colourcode = $request->color_code;
        $colourcodecount = strlen($colourcode);

        if ($colourcodecount == 5) {
            $extColour = substr($colourcode, 0, 3);
            $intColour = substr($colourcode,  -2);
        }
        if ($colourcodecount == 4) {

            $altercolourcode = "0" . $colourcode;
            $extColour = substr($altercolourcode, 0, 3);
            $intColour = substr($altercolourcode, -2);
        }

        $extColourRow = ColorCode::where('code', $extColour)
            ->where('belong_to', ColorCode::EXTERIOR)
            ->first();
        $intColourRow = ColorCode::where('code', $intColour)
            ->where('belong_to', ColorCode::INTERIOR)
            ->first();

        $data = 0;
       if($intColourRow && $extColourRow) {
           $data = 1;
       }

       return response($data);
    }
}
