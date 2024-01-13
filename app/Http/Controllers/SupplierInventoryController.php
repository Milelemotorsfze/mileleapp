<?php

namespace App\Http\Controllers;

use App\Imports\SupplierInventoryImport;
use App\Models\Brand;
use App\Models\ColorCode;
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

        $supplierInventories = SupplierInventory::with('masterModel')
            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
//            ->whereNull('eta_import')
            ->groupBy('master_model_id')
            ->orderBy('id','desc');

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
            $supplierInventory->childRows =  SupplierInventory::with('masterModel')
                ->where('master_model_id', $supplierInventory->master_model_id)
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

            DB::beginTransaction();

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

            $date = Carbon::now()->format('Y-m-d');
            while (($filedata = fgetcsv($file, 5000, ",")) !== FALSE) {
                $num = count($filedata);
                if ($i > 0 && $num == $numberOfFields)
                {
                    $supplier_id = $request->input('supplier_id');
                    $country = $request->input('country');
                    $colourcode = $filedata[5];
                    if($colourcode) {
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
                    }

                    $uploadFileContents[$i]['steering'] = $filedata[0];
                    $uploadFileContents[$i]['model'] = $filedata[1];
                    $uploadFileContents[$i]['sfx'] = $filedata[2];
                    $uploadFileContents[$i]['chasis'] = !empty($filedata[3]) ? $filedata[3] : NULL;
                    $uploadFileContents[$i]['engine_number'] = $filedata[4];
                    $uploadFileContents[$i]['color_code'] = $colourcode;
                    $uploadFileContents[$i]['pord_month'] = $filedata[6];
                    $uploadFileContents[$i]['po_arm'] = $filedata[7];
                    if (!empty($filedata[8])) {
                        $filedata[8] = \Illuminate\Support\Carbon::parse($filedata[8])->format('Y-m-d');
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

            foreach($uploadFileContents as $uploadFileContent) {
                $chaisis[] = $uploadFileContent['chasis'];

                // CHCEKING NEW MODEL SFX MODEL YEAR COMBINATION EXISTING ///////////

                $isModelExist = MasterModel::where('model',$uploadFileContent['model'])
                                            ->where('sfx', $uploadFileContent['sfx'])
                                            ->where('model_year',  $uploadFileContent['model_year'])
                                            ->first();

                $isModelWithSteeringExist = MasterModel::where('model', $uploadFileContent['model'])
                    ->where('sfx', $uploadFileContent['sfx'])
                    ->where('steering', $uploadFileContent['steering'])
                    ->where('model_year',  $uploadFileContent['model_year'])
                    ->first();

                if(!$isModelWithSteeringExist)
                {

                    $newModelsWithSteerings[$j]['steering'] = $uploadFileContent['steering'];
                    $newModelsWithSteerings[$j]['model'] = $uploadFileContent['model'];
                    $newModelsWithSteerings[$j]['sfx'] = $uploadFileContent['sfx'];
                    $newModelsWithSteerings[$j]['model_year'] = $uploadFileContent['model_year'];

                }
                if (!$isModelExist)
                {

                    $newModels[$j]['model'] = $uploadFileContent['model'];
                    $newModels[$j]['sfx'] = $uploadFileContent['sfx'];
                    $newModels[$j]['model_year'] =  $uploadFileContent['model_year'];
                }
                $j++;
            }
            // CHCEK CHASIS EXISTING WITH ALREDY UPLOADED DATA.
            $chaisisNumbers = array_filter($chaisis);
            $uniqueChaisis =  array_unique($chaisisNumbers);

            if(count($chaisisNumbers) !== count($uniqueChaisis)) {
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
                            ->where('model_year', $uploadFileContent['model_year'])
                            ->where('steering', $uploadFileContent['steering'])
                            ->first();
                        $modelId = $model->id;
                        info($modelId);
                            info($uploadFileContent['model'] ."-". $uploadFileContent['sfx']);
                            $deliveryNote = $uploadFileContent['delivery_note'];
                        // after having DN data their is no changes for data of thata ro.so consider the data without eta import for inventory
                        $supplierInventories = SupplierInventory::where('master_model_id', $modelId)
//                            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                            ->where('supplier_id', $request->supplier_id)
                            ->where('whole_sales', $request->whole_sales)
                            ->where(function ($query) use($deliveryNote) {
                                $query->whereNull('delivery_note')
                                    ->orwhere('delivery_note', $deliveryNote);
                            });
    //                            ->whereNull('delivery_note');

                        if ($supplierInventories->count() <= 0)
                        {
                            info("no row existing with model,sfx model year so add new row");
                            // model and sfx not existing in Suplr Invtry => new row
                            $newlyAddedRows[$i]['model'] = $uploadFileContent['model'];
                            $newlyAddedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                            $newlyAddedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                            $newlyAddedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                            $newlyAddedRows[$i]['color_code'] = $uploadFileContent['color_code'];

                            $supplierInventory = new SupplierInventory();
                            $supplierInventory->master_model_id = $modelId;
                            $supplierInventory->supplier_id       = $uploadFileContent['supplier_id'];
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
                            $supplierInventory->save();

                        } else {
                            info("model sfx modelyear combination row existing");
//                            info($uploadFileContent['chasis']);
                            if (!empty($uploadFileContent['chasis']))
                            {
                                // Store the Count into Update the Row with data
                                $supplierInventory = $supplierInventories->where('chasis', $uploadFileContent['chasis'])
                                    ->first();
                                info("chaisis avaialable");
                                info($supplierInventory);

                                if (empty($supplierInventory)) {
                                    info("chasis matching row not avaialble, case of chasis updation");
                                    //adding new row simply
                                    $isNullChaisis = SupplierInventory::where('master_model_id', $modelId)
//                            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                                        ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                                        ->where('supplier_id', $request->supplier_id)
                                        ->where('whole_sales', $request->whole_sales)
                                        ->whereNull('chasis')
                                        ->where(function ($query) use($deliveryNote) {
                                            $query->whereNull('delivery_note')
                                                ->orwhere('delivery_note', $deliveryNote);
                                        });

                                    info($isNullChaisis->get());

//                                    $isNullChaisisExist = $isNullChaisis->first();
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

                                        if(!empty($SimilarRowWithNullChaisis)) {
                                            info("null chasis with smilar data exist");

                                            $isNullChaisis = $SimilarRowWithNullChaisis;
                                        }else{
                                            info("null chasis smilar data not exist");

                                            $isNullChaisis = $isNullChaisis->first();
                                        }

                                        $chasisUpdatedRow = $isNullChaisis->whereNotIn('id', $chasisUpdatedRowIds)->first();
//
                                        if($chasisUpdatedRow) {
                                            $chasisUpdatedRowIds[] = $chasisUpdatedRow->id;
                                        }

                                        $updatedRowsIds[] = $isNullChaisis->id;
                                        $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                        $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                        $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                        $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                        $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];

                                        $isNullChaisis->chasis  = $uploadFileContent['chasis'];
                                        $isNullChaisis->save();
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
                                        $supplierInventory->save();
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

                                        $supplierInventory->chasis              = $uploadFileContent['chasis'];
                                        $supplierInventory->engine_number   = $uploadFileContent['engine_number'];
                                        $supplierInventory->color_code      = $uploadFileContent['color_code'];
                                        $supplierInventory->interior_color_code_id = $uploadFileContent['interior_color_code_id'];
                                        $supplierInventory->exterior_color_code_id = $uploadFileContent['exterior_color_code_id'];
                                        $supplierInventory->pord_month      = $uploadFileContent['pord_month'];
                                        $supplierInventory->po_arm          = $uploadFileContent['po_arm'];
                                        $supplierInventory->eta_import      = $uploadFileContent['eta_import'];
                                        $supplierInventory->delivery_note   = $uploadFileContent['delivery_note'];
                                        $supplierInventory->save();
                                    }

                                }
                            } else
                            {
//                                $nullChaisisCount = SupplierInventory::where('master_model_id', $modelId)
//                                    ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
//                                    ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
//                                    ->where('supplier_id', $request->supplier_id)
//                                    ->where('whole_sales', $request->whole_sales)
//                                    ->whereNotIn('id', $chasisUpdatedRowIds)
//                                    ->whereNull('chasis')
//                                    //->whereNull('eta_import')
//                                    ->count();
//                                $modelSfxValuePair = $uploadFileContent['model']."_".$uploadFileContent['sfx'];
//                                $countblankchasis[] = $modelSfxValuePair;
//                                $groupedCountValue =  array_count_values($countblankchasis);
//                                if ($groupedCountValue[$modelSfxValuePair] > $nullChaisisCount)
//                                {
//                                    $newlyAddedRows[$i]['model'] = $uploadFileContent['model'];
//                                    $newlyAddedRows[$i]['sfx'] = $uploadFileContent['sfx'];
//                                    $newlyAddedRows[$i]['chasis'] = $uploadFileContent['chasis'];
//                                    $newlyAddedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
//                                    $newlyAddedRows[$i]['color_code'] = $uploadFileContent['color_code'];
//
//                                }else
//                                {
//                                    $supplierInventory = $supplierInventories->whereNull('chasis')->first();
//                                    $supplierInventory1 = $supplierInventories->whereNull('chasis')
//                                        ->where('engine_number', $uploadFileContent['engine_number'])
//                                        ->first();
//                                    if (!$supplierInventory1)
//                                    {
//                                        $updatedRowsIds[] = $supplierInventory->id;
//                                        $updatedRows[$i]['model'] = $uploadFileContent['model'];
//                                        $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
//                                        $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
//                                        $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
//                                        $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];
//
//                                    }else
//                                    {
//                                        $supplierInventory2 = $supplierInventories->whereNull('chasis')
//                                            ->where('color_code', $uploadFileContent['color_code'])
//                                            ->first();
//                                        if (!$supplierInventory2)
//                                        {
//                                            $updatedRowsIds[] = $supplierInventory1->id;
//                                            $updatedRows[$i]['model'] = $uploadFileContent['model'];
//                                            $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
//                                            $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
//                                            $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
//                                            $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];
//
//                                        } else {
//                                            $supplierInventory3 = $supplierInventories->whereNull('chasis')
//                                                ->where('pord_month', $uploadFileContent['pord_month'])
//                                                ->first();
//                                            if (!$supplierInventory3)
//                                            {
//                                                $updatedRowsIds[] = $supplierInventory2->id;
//                                                $updatedRows[$i]['model'] = $uploadFileContent['model'];
//                                                $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
//                                                $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
//                                                $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
//                                                $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];
//
//                                            }else{
//                                                $supplierInventory4 = $supplierInventories->whereNull('chasis')
//                                                    ->where('po_arm', $uploadFileContent['po_arm'])
//                                                    ->first();
//                                                if (!$supplierInventory4)
//                                                {
//                                                    $updatedRowsIds[] = $supplierInventory3->id;
//                                                    $updatedRows[$i]['model'] = $uploadFileContent['model'];
//                                                    $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
//                                                    $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
//                                                    $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
//                                                    $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];
//
//                                                }else{
//                                                    if (!empty($uploadFileContent['eta_import'])) {
//                                                        $supplierInventory5 = $supplierInventories->whereNull('chasis')
//                                                            ->whereDate('eta_import', $uploadFileContent['eta_import'])
//                                                            ->first();
//                                                        if (!$supplierInventory5)
//                                                        {
//                                                            $updatedRowsIds[] = $supplierInventory4->id;
//                                                            $updatedRows[$i]['model'] = $uploadFileContent['model'];
//                                                            $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
//                                                            $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
//                                                            $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
//                                                            $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];
//                                                        }
//                                                    }
//                                                }
//                                            }
//                                        }
//                                    }
//                                }
                            }
                        }$i++;
                    }
                        // to find deleted rows
                        // group the value pair to get count of duplicate data

                        $deletedRows = [];

                    DB::commit();

                    $pdf = Pdf::loadView('supplier_inventories.reports', compact('newlyAddedRows',
                        'updatedRows','deletedRows'));
                    return $pdf->download('report.pdf');

                }
                else{
                    info("test");
                    info($uploadFileContents);
                    foreach ($uploadFileContents as $uploadFileContent)
                    {
                        $model = MasterModel::where('model', $uploadFileContent['model'])
                            ->where('sfx', $uploadFileContent['sfx'])
                            ->where('steering', $uploadFileContent['steering'])
                            ->where('model_year', $uploadFileContent['model_year'])
                            ->first();
                        info($model->id);

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
                        $supplierInventory->save();


//                        $supplierInventoryHistory = new SupplierInventoryHistory();
//
//                        $supplierInventoryHistory->master_model_id = $model->id;
//                        $supplierInventoryHistory->chasis          = $uploadFileContent['chasis'];
//                        $supplierInventoryHistory->engine_number   = $uploadFileContent['engine_number'];
//                        $supplierInventoryHistory->color_code      = $uploadFileContent['color_code'];
//                        $supplierInventoryHistory->pord_month      = $uploadFileContent['pord_month'];
//                        $supplierInventoryHistory->po_arm          = $uploadFileContent['po_arm'];
//                        $supplierInventoryHistory->eta_import      = $uploadFileContent['eta_import'];
//                        $supplierInventoryHistory->is_add_new      = !empty($request->is_add_new) ? true : false;
//                        $supplierInventoryHistory->supplier_id     = $uploadFileContent['supplier_id'];
//                        $supplierInventoryHistory->whole_sales	   = $uploadFileContent['whole_sales'];
//                        $supplierInventoryHistory->country     	   = $uploadFileContent['country'];
//                        $supplierInventoryHistory->date_of_entry   = $date;
//                        $supplierInventoryHistory->upload_status   = SupplierInventory::UPLOAD_STATUS_ACTIVE;
//                        $supplierInventoryHistory->veh_status      = SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY;
//                        $supplierInventoryHistory->interior_color_code_id = $uploadFileContent['interior_color_code_id'];
//                        $supplierInventoryHistory->exterior_color_code_id = $uploadFileContent['exterior_color_code_id'];
//                        $supplierInventoryHistory->save();

                    }

                    return redirect()->route('supplier-inventories.create')->with('message','supplier inventory updated successfully');
                }
            }

        }
    }
    public function updateInventory(Request $request) {
        info($request->all());
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
                info($fieldValue);
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
        $deletedRows = [];
        $updatedRows = [];
        $firstFileValuePairs = [];
        $chasisUpdatedRowIds = [];
        $updatedRowsIds = [];
        $i = 0;

        $firstFileRowDetails = SupplierInventory::whereDate('date_of_entry', $request->first_file)
            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
            ->where('supplier_id', $request->supplier_id)
            ->where('whole_sales', $request->whole_sales)
//            ->whereNull('eta_import')
            ->get();
        $secondFileRowDetails = SupplierInventory::whereDate('date_of_entry', $request->second_file)
            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
//            ->whereNull('eta_import')
            ->where('supplier_id', $request->supplier_id)
            ->where('whole_sales', $request->whole_sales)
            ->get();

        foreach ($secondFileRowDetails as $secondFileRowDetail)
        {
            $masterModel = MasterModel::find($secondFileRowDetail['master_model_id']);
            $supplierInventories = SupplierInventory::whereDate('date_of_entry', $request->first_file)
                ->where('master_model_id', $secondFileRowDetail['master_model_id'])
                ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                ->where('supplier_id', $request->supplier_id)
                ->where('whole_sales', $request->whole_sales);
//                ->whereNull('eta_import');
            if ($supplierInventories->count() <= 0) {
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
                    // Store the Count into Update the Row with data
                    $supplierInventory = $supplierInventories->where('chasis', $secondFileRowDetail['chasis'])
                        ->first();
                    $isNullChaisis = SupplierInventory::whereDate('date_of_entry', $request->first_file)
                        ->where('master_model_id', $secondFileRowDetail['master_model_id'])
                        ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
//                        ->whereNull('eta_import')
                        ->where('supplier_id', $request->supplier_id)
                        ->where('whole_sales', $request->whole_sales)
                        ->whereNull('chasis');
                    if (!$supplierInventory) {
                        //adding new row simply
                        if (!empty($isNullChaisis->first())) {
                            // null chaisis existing => updating row
                            $chasisUpdatedRow = $isNullChaisis->whereNotIn('id',$chasisUpdatedRowIds)->first();
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
                            // new chaisis with existing model and sfx => add row ,
                            $newlyAddedRows[$i]['model'] = $masterModel->model;
                            $newlyAddedRows[$i]['sfx'] = $masterModel->sfx;
                            $newlyAddedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                            $newlyAddedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                            $newlyAddedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];
                        }
                    } else
                    {
                        $supplierInventoryOne = $supplierInventories->where('engine_number', $secondFileRowDetail['engine_number'])->first();
                        if (!$supplierInventoryOne)
                        {
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
                                    if (!$supplierInventoryFour)
                                    {
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
                                        if(!empty($secondFileRowDetail['eta_import'])) {
                                            $supplierInventoryFive = $supplierInventories->whereDate('eta_import', $secondFileRowDetail['eta_import'])->first();
                                            if (!$supplierInventoryFive) {
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
                    $nullChaisisCount = SupplierInventory::whereDate('date_of_entry', $request->first_file)
                        ->where('master_model_id', $secondFileRowDetail['master_model_id'])
                        ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                        ->where('supplier_id', $request->supplier_id)
                        ->where('whole_sales', $request->whole_sales)
                        ->whereNotIn('id', $chasisUpdatedRowIds)
                        ->whereNull('chasis')
//                         ->whereNull('eta_import')
                        ->count();
                    $modelSfxValuePair = $masterModel->model."_".$masterModel->sfx;
                    $countblankchasis[] = $modelSfxValuePair;
                    $groupedCountValue =  array_count_values($countblankchasis);
                    if ($groupedCountValue[$modelSfxValuePair] > $nullChaisisCount)
                    {
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
                ->where('supplier_id', $request->supplier_id)
                ->where('whole_sales', $request->whole_sales)
                ->where('master_model_id', $firstFileRowDetail['master_model_id'])
                ->where('chasis', $firstFileRowDetail['chasis'])
                ->where('engine_number', $firstFileRowDetail['engine_number'])
                ->where('color_code', $firstFileRowDetail['color_code'])
                ->where('pord_month', $firstFileRowDetail['pord_month'])
                ->where('po_arm', $firstFileRowDetail['po_arm']);
//                ->whereNull('eta_import');
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
//            ->whereNull('eta_import')
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
        $supplierInventoryDates = SupplierInventory::where('supplier_id', $request->supplier_id)
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
        info("reached");
        info($request->all());
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
