<?php

namespace App\Http\Controllers;

use App\Imports\SupplierInventoryImport;
use App\Models\Brand;
use App\Models\ColorCode;
use App\Models\DemandList;
use App\Models\LetterOfIndent;
use App\Models\LetterOfIndentItem;
use App\Models\LOIMappingCriteria;
use App\Models\MasterModel;
use App\Models\ModelYearCalculationCategory;
use App\Models\Supplier;
use App\Models\SupplierInventory;
use App\Models\SupplierInventoryHistory;
use App\Models\ApprovedLetterOfIndentItem;
use App\Models\SupplierInventoryLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $supplierInventories = SupplierInventory::select('master_model_id','upload_status','veh_status','supplier_id','whole_sales')
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
//                        ->where('steering', $supplierInventory->masterModel->steering)
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
            ->orderBy('updated_at','DESC');

            if($request->supplier_id){

                $supplierInventories = $supplierInventories->where('supplier_id', $request->supplier_id);
            }
            if($request->dealers){
                $supplierInventories = $supplierInventories->where('whole_sales', $request->dealers);
            }

            $supplierInventories = $supplierInventories->get();

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
        (new UserActivityController)->createActivity('Open Supplier Inventory Excel Upload Page.');

        $suppliers = Supplier::with('supplierTypes')
            ->whereHas('supplierTypes', function ($query) {
                $query->where('supplier_type', Supplier::SUPPLIER_TYPE_DEMAND_PLANNING);
            })
            ->where('status', Supplier::SUPPLIER_STATUS_ACTIVE)
            ->get();
        return view('supplier_inventories.edit', compact('suppliers'));

    }
    public function createNew()
    {
        (new UserActivityController)->createActivity('Open Supplier Inventory Create Page');

        $suppliers = Supplier::with('supplierTypes')
            ->whereHas('supplierTypes', function ($query) {
                $query->where('supplier_type', Supplier::SUPPLIER_TYPE_DEMAND_PLANNING);
            })
            ->where('status', Supplier::SUPPLIER_STATUS_ACTIVE)
            ->get();

        $models  = MasterModel::select('model','id')->groupBy('model')->get();
        return view('supplier_inventories.create', compact('suppliers','models'));
    }
    public function inventoryLog($action,$id) {

        $supplierInventoryLog = new SupplierInventoryLog();
        $supplierInventoryLog->updated_by = Auth::id();
        $supplierInventoryLog->supplier_inventory_id = $id;
        $supplierInventoryLog->action = $action;
        $supplierInventoryLog->save();
    }
    public function store(Request $request)
    {
        $request->validate([
            'whole_sales' => 'required',
            'supplier_id' => 'required',
            'country'     => 'required',
            'model'       => 'required',
            'sfx'         => 'required',
        ]);
        (new UserActivityController)->createActivity('Supplier Inventory Created.');

        if(!empty($request->prod_month)) {
            $productionMonth = substr($request->prod_month,  -2);
            $modelYear = substr($request->prod_month, 0, -2);
            if($productionMonth < 0 || $productionMonth > 12) {
                return redirect()->back()->with('error', 'Invalid Production Month '.$productionMonth.', Last 2 digit indicating Invalid month!');

            }
            $masterModel = MasterModel::where('model', $request->model)
                ->where('sfx', $request->sfx)
                ->where('model_year', $modelYear)->first();
        }else{
            $masterModel = MasterModel::where('model', $request->model)
                                    ->where('sfx', $request->sfx)
                                    ->orderBy('model_year','DESC')
                                    ->first();
        }

        $colourcode = $request->color_code;

        $extColour = mb_substr($colourcode, 0, -2);
        $intColour = mb_substr($colourcode,  -2);

        $interiorColorId = NULL;
        $exteriorColorId = NUll;

        if($colourcode) {
            if($extColour) {
                $extColourRow = ColorCode::where('code', $extColour)
                                        ->where('belong_to', ColorCode::EXTERIOR)
                                        ->first();
                if ($extColourRow)
                {
                    $exteriorColorId = $extColourRow->id;
                }
            }
            if($intColour) {
                $intColourRow = ColorCode::where('code', $intColour)
                                    ->where('belong_to', ColorCode::INTERIOR)
                                    ->first();
                if ($intColourRow)
                {
                    $interiorColorId = $intColourRow->id;
                }
            }
        }
        DB::beginTransaction();

        $supplierInventory = new SupplierInventory();

        $supplierInventory->supplier_id = $request->supplier_id;
        $supplierInventory->whole_sales = $request->whole_sales;
        $supplierInventory->country = $request->country;
        $supplierInventory->eta_import = $request->eta_import ? \Illuminate\Support\Carbon::parse($request->eta_import)->format('Y-m-d') : NULL;
        $supplierInventory->chasis = $request->chasis;
        $supplierInventory->engine_number = $request->engine_number;
        $supplierInventory->pord_month = $request->prod_month;
        $supplierInventory->color_code = $request->color_code;
        // $supplierInventory->po_arm = $request->po_arm;
        $supplierInventory->delivery_note = $request->delivery_note;
        $supplierInventory->interior_color_code_id  = $interiorColorId;
        $supplierInventory->exterior_color_code_id  = $exteriorColorId;
        $supplierInventory->veh_status = SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY;
        $supplierInventory->upload_status = SupplierInventory::UPLOAD_STATUS_ACTIVE;
        $supplierInventory->date_of_entry = Carbon::now()->format('Y-m-d');
        $supplierInventory->master_model_id = $masterModel->id ?? '';

        if ($request->delivery_note) {
            if($request->country == SupplierInventory::COUNTRY_BELGIUM ) {
                if(strcasecmp($request->delivery_note, SupplierInventory::DN_STATUS_RECEIVED) == 0) {
                    $supplierInventory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                }
            }else{
                if(is_numeric($request->delivery_note)) {
                    $supplierInventory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                }
            }
        }
        $supplierInventory->updated_by = Auth::id();
        $supplierInventory->save();

        $action = "Inventory Item Added";
        $this->inventoryLog($action, $supplierInventory->id);

        DB::commit();

        return  redirect()->route('supplier-inventories.index')->with('success', 'Inventory added successfully.');
    }

    public function ExcelUpdate(Request $request)
    {
        (new UserActivityController)->createActivity('Added Supplier Inventories by Excel Upload');

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

            $date = Carbon::today()->format('Y-m-d');
//            $date = '2024-03-10';
            $unavailableExtColours = [];
            $unavailableIntColours = [];

            while (($filedata = fgetcsv($file, 5000, ",")) !== FALSE) {
                $num = count($filedata);
                if ($i > 0 && $num == $numberOfFields)
                {
                    $supplier_id = $request->input('supplier_id');
                    $country = $request->input('country');

                    $uploadFileContents[$i]['steering'] = $filedata[0];
                    $uploadFileContents[$i]['model'] = $filedata[1];
                    $uploadFileContents[$i]['sfx'] = $filedata[2];
                    $uploadFileContents[$i]['chasis'] = !empty($filedata[3]) ? $filedata[3] : NULL;
                    $uploadFileContents[$i]['engine_number'] = $filedata[4];
                    $intColour = $filedata[5];
                    $extColour = $filedata[6];

                    $uploadFileContents[$i]['color_code'] = $filedata[6].$filedata[5];
                    $uploadFileContents[$i]['pord_month'] = $filedata[7];
                    // $uploadFileContents[$i]['po_arm'] = $filedata[7];
                    if(!empty($filedata[7])) {
                        if(strlen($filedata[7]) != 6){
                            return redirect()->back()->with('error', 'Invalid Production Month '.$filedata[7].', Production month length should be exactly 6!');
                        }else{
                            $productionMonth = substr($filedata[7],  -2);
                            if($productionMonth < 0 || $productionMonth > 12) {
                                return redirect()->back()->with('error', 'Invalid Production Month '.$filedata[7].', Last 2 digit indicating Invalid month!');
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

                    if($extColour) {
                        $extColourRow = ColorCode::where('code', $extColour)
                            ->where('belong_to', ColorCode::EXTERIOR)
                            ->first();
//                        $exteriorColor = "";
                        if ($extColourRow)
                        {
//                            $exteriorColor = $extColourRow->name;
                            $exteriorColorId = $extColourRow->id;
                            // info("available exterior Colour");
                            // info($exteriorColorId);
                        }else{
                            // info("not available exterior Colour");
                            // info($extColour);
                            $unavailableExtColours[]  = $extColour;
                        }
                    }
                    if($intColour) {
                        $intColourRow = ColorCode::where('code', $intColour)
                            ->where('belong_to', ColorCode::INTERIOR)
                            ->first();

                        if ($intColourRow)
                        {
                            $interiorColorId = $intColourRow->id;
                        }else{
                            $unavailableIntColours[] = $intColour;
                        }
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
                    // info($uploadFileContents[$i]['exterior_color_code_id']);
                    // info($uploadFileContents[$i]['interior_color_code_id']);
                    ////// finding model year //////////

                    if ($filedata[7]) {
                        // fetch year from pod month
                        $modelYear = substr($filedata[7], 0, -2);
                        $productionMonth = substr($filedata[7], -2);
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
                                    if ($filedata[7]){
                                        $modelYear = substr($filedata[7], 0, -2) + 1;
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

                $i++;
            }

            fclose($file);
            $newModels = [];
            $newModelsWithSteerings = [];
            $j=0;

            if(count($unavailableExtColours) || count($unavailableIntColours)) {
                $extColors = implode(',', array_unique($unavailableExtColours));
                $intColors = implode(',', array_unique($unavailableIntColours));

                return redirect()->back()->with('error','These Colour codes are not available in the Master Data.
            Exterior Color codes are '.$extColors." and Interior Color Codes are ".$intColors.".");
            }


            $excelPairs = [];

            foreach($uploadFileContents as $uploadFileContent) {
                // info($uploadFileContent['color_code']);
                // if(empty($uploadFileContent['chasis'])) {
                $excelPairs[] = $uploadFileContent['model'] . "_" . $uploadFileContent['sfx'];
                // }

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
                $DN_WAITING = strcasecmp($uploadFileContent['delivery_note'], 'WAITING');
           
                
                $DN_RECEIVED = strcasecmp($uploadFileContent['delivery_note'], SupplierInventory::DN_STATUS_RECEIVED);
                if(!empty($uploadFileContent['delivery_note'])) {
                    // info($uploadFileContent['delivery_note']);
                    // info("DN VALUE");
                    // info($DN_WAITING);
                    if($country == SupplierInventory::COUNTRY_BELGIUM) {
                        if ($DN_WAITING != 0 ) {
                            if ($DN_RECEIVED != 0) {
                                return redirect()->back()->with('error', $uploadFileContent['delivery_note'] . " Delivery note should be a Waiting or Received");
                            }
                        }
                    }else{
                        if ($DN_WAITING != 0) {
                            if(!is_numeric($uploadFileContent['delivery_note'])) {
                                return redirect()->back()->with('error', "Delivery note should be a number or status should be Waiting");
                            }
                        }
                    }

                }else{
                    // info("empty DN");
                }
                $j++;
            }

            // get the null chasis row count by model and sfx.
            $inventories = SupplierInventory::where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                ->where('supplier_id', $request->supplier_id)
                ->where('whole_sales', $request->whole_sales)
//                ->whereNull('chasis')
                ->get();

            $existingItems = [];
            foreach ($inventories as $inventory) {
                $existingItems[] = $inventory->masterModel->model .'_'.$inventory->masterModel->sfx;
            }

            $groupedExcelPairs =  array_count_values($excelPairs);
            $groupedExistingPairs = array_count_values($existingItems);

            $newModelsWithSteerings = array_map("unserialize", array_unique(array_map("serialize", $newModelsWithSteerings)));
            $newModels = array_map("unserialize", array_unique(array_map("serialize", $newModels)));
            // info($newModelsWithSteerings);
            // info($newModels);

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
                    $newlyAddedRows = [];
                    $newlyAddedRowIds = [];
                    $updatedRows = [];
                    $updatedRowsIds = [];
                    $noChangeRowIds = [];

                    $supplierInventoryHistories = SupplierInventoryHistory::where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)->get();

                    foreach ($supplierInventoryHistories as $inventoryHistory) {
                        $inventoryHistory->upload_status = SupplierInventory::UPLOAD_STATUS_INACTIVE;
                        $inventoryHistory->save();
                    }
                    $dealer = $request->whole_sales;
                    foreach ($uploadFileContents as $uploadFileContent)
                    {
                        if ($uploadFileContent['delivery_note'] ) {
                            if($country == SupplierInventory::COUNTRY_BELGIUM ) {
                                if(strcasecmp($uploadFileContent['delivery_note'], SupplierInventory::DN_STATUS_RECEIVED) == 0) {
                                    $veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                }
                            }else{
                                if(is_numeric($uploadFileContent['delivery_note'])) {
                                    $veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                }
                            }
                        }
                        $model = MasterModel::where('model', $uploadFileContent['model'])
                            ->where('sfx', $uploadFileContent['sfx'])
                            ->where('model_year', $uploadFileContent['model_year'])
                            ->where('steering', $uploadFileContent['steering'])
                            ->first();


                        $modelId = $model->id;
                        // info($uploadFileContent['model']);
                        // info($uploadFileContent['sfx']);
                        $modelIds = MasterModel::where('model', $uploadFileContent['model'])
                            ->where('sfx', $uploadFileContent['sfx'])
                            ->pluck('id')->toArray();

                        $deliveryNote = $uploadFileContent['delivery_note'];
                        // after having DN data their is no changes for data of thata ro.so consider the data have numeric value for inventory
                        $supplierInventories = SupplierInventory::whereIn('master_model_id', $modelIds)
                            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                            ->where('supplier_id', $request->supplier_id)
                            ->where('whole_sales', $request->whole_sales)
//                            ->whereNull('delivery_note');
                            ->where(function ($query) use($deliveryNote) {
                                $query->whereNull('delivery_note')
                                    ->orwhere('delivery_note', $deliveryNote);
                            });
                        //                       ->whereNull('delivery_note');
                        // info("supplier count");
                        // info($supplierInventories->count());

                        if ($supplierInventories->count() <= 0)
                        {
                            // info("no row existing with model,sfx model year so => add new row");
                            $isChasisExist = SupplierInventory::where('chasis', $uploadFileContent['chasis'])
                                ->whereNotNull('chasis')
                                ->first();
                            if($isChasisExist) {
                                // info("case 1");
                                return redirect()->back()->with('error', $uploadFileContent['chasis']." Chasis already existing");

                            }else {
                                // model and sfx not existing in Suplr Invtry => new row
                                $newlyAddedRows[$i]['model'] = $uploadFileContent['model'];
                                $newlyAddedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                $newlyAddedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                $newlyAddedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                $newlyAddedRows[$i]['color_code'] = $uploadFileContent['color_code'];

                                $supplierInventory = new SupplierInventory();
                                $supplierInventory->master_model_id = $modelId;
                                $supplierInventory->supplier_id = $uploadFileContent['supplier_id'];
                                $supplierInventory->chasis = $uploadFileContent['chasis'];
                                $supplierInventory->engine_number = $uploadFileContent['engine_number'];
                                $supplierInventory->color_code = $uploadFileContent['color_code'];
                                $supplierInventory->pord_month = $uploadFileContent['pord_month'];
                                // $supplierInventory->po_arm = $uploadFileContent['po_arm'];
                                $supplierInventory->eta_import = $uploadFileContent['eta_import'];
                                $supplierInventory->is_add_new = false;
                                $supplierInventory->whole_sales = $uploadFileContent['whole_sales'];
                                $supplierInventory->country = $uploadFileContent['country'];
                                $supplierInventory->delivery_note = $uploadFileContent['delivery_note'];
                                $supplierInventory->date_of_entry = $date;
                                $supplierInventory->upload_status = SupplierInventory::UPLOAD_STATUS_ACTIVE;
                                $supplierInventory->veh_status = SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY;
                                $supplierInventory->interior_color_code_id = $uploadFileContent['interior_color_code_id'];
                                $supplierInventory->exterior_color_code_id = $uploadFileContent['exterior_color_code_id'];
                                if ($uploadFileContent['delivery_note'] ) {
                                    if($country == SupplierInventory::COUNTRY_BELGIUM ) {
                                        if(strcasecmp($uploadFileContent['delivery_note'], SupplierInventory::DN_STATUS_RECEIVED) == 0) {
                                            $supplierInventory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                        }
                                    }else{
                                        if(is_numeric($uploadFileContent['delivery_note'])) {
                                            $supplierInventory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                        }
                                    }
                                }
                                $supplierInventory->updated_by = Auth::id();
                                $supplierInventory->save();

                                $action = "Inventory Item Added";
                                $this->inventoryLog($action, $supplierInventory->id);

                                $newlyAddedRowIds[] = $supplierInventory->id;
                            }

                        } else {
                            $isSimilarRowExist =  SupplierInventory::whereIn('master_model_id', $modelIds)
                                ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                                ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                                ->where('supplier_id', $request->supplier_id)
                                ->where('whole_sales', $request->whole_sales)
                                ->where('chasis', $uploadFileContent['chasis'])
                                ->where('engine_number', $uploadFileContent['engine_number'])
                                ->where('color_code', $uploadFileContent['color_code'])
                                ->where('pord_month', $uploadFileContent['pord_month'])
                                // ->where('po_arm', $uploadFileContent['po_arm'])
                                ->whereNotIn('id', $noChangeRowIds)
                                ->whereNotIn('id', $updatedRowsIds)
                                ->whereNotIn('id', $newlyAddedRowIds)
                                ->where('eta_import', $uploadFileContent['eta_import'])
                                ->where('delivery_note', $uploadFileContent['delivery_note'])
                                ->first();

                            // info($isSimilarRowExist);
                            // info("model sfx modelyear combination row existing");
                            if(empty($isSimilarRowExist)) {

//                            info($uploadFileContent['chasis']);
                                if (!empty($uploadFileContent['chasis']))
                                {
                                    // info($uploadFileContent['chasis']);
                                    // info($modelIds);

                                    // Store the Count into Update the Row with data
                                    $supplierInventory = SupplierInventory::whereIn('master_model_id', $modelIds)
                                        ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                                        ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                                        ->where('supplier_id', $request->supplier_id)
                                        ->where('whole_sales', $request->whole_sales)
                                        ->whereNotIn('id', $noChangeRowIds)
                                        ->whereNotIn('id', $newlyAddedRowIds)
                                        ->whereNotIn('id', $updatedRowsIds)
                                        ->where('chasis', $uploadFileContent['chasis'])
//                                        ->whereNull('delivery_note') unable to find row when have delivery note
//                                         ->where(function ($query) use($deliveryNote) {
//                                             $query->whereNull('delivery_note')
//                                                 ->orwhere('delivery_note', $deliveryNote);
//                                         })
                                        // DN WHERE condition removed bcz when DN have updation it does't identify the row with chasis

                                        ->first();

                                    // info($supplierInventory);

                                    if (empty($supplierInventory)) {
                                        // info("chasis matching row not avaialble, case of chasis updation ore new chaisi row add");
                                        //adding new row simply
                                        $isNullChaisis = SupplierInventory::whereIn('master_model_id', $modelIds)
                                            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                                            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                                            ->where('supplier_id', $request->supplier_id)
                                            ->where('whole_sales', $request->whole_sales)
                                            ->whereNotIn('id', $noChangeRowIds)
                                            ->whereNotIn('id', $newlyAddedRowIds)
                                            ->whereNotIn('id', $updatedRowsIds)
                                            ->whereNull('chasis');
//                                             ->where(function ($query) use($deliveryNote) {
//                                                 $query->whereNull('delivery_note')
//                                                     ->orwhere('delivery_note', $deliveryNote);
//                                             });

                                        $isNullChaisisIds = $isNullChaisis->pluck('id');

                                        // info($isNullChaisis->get());

                                        //  $isNullChaisisExist = $isNullChaisis->first();
                                        // info($isNullChaisis->count());
                                        if ($isNullChaisis->count() > 0) {
                                            // info("null chasis row exist");
                                            // find the null chaisis row
                                            // null chaisis existing => updating row
                                            // find the row with similar details.
                                            $SimilarRowWithNullChaisis = $isNullChaisis->where('engine_number', $uploadFileContent['engine_number'])
                                                ->where('color_code', $uploadFileContent['color_code'])
                                                ->where('pord_month', $uploadFileContent['pord_month'])
                                                // ->where('po_arm', $uploadFileContent['po_arm'])
                                                ->where('eta_import', $uploadFileContent['eta_import'])
                                                ->where('delivery_note', $uploadFileContent['delivery_note'])
                                                ->first();

                                            // info($SimilarRowWithNullChaisis);
                                            if(!empty($SimilarRowWithNullChaisis)) {
                                                // info("null chasis with smilar data exist");
                                                $isChasisExist = SupplierInventory::where('chasis', $uploadFileContent['chasis'])
                                                    ->whereNotNull('chasis')
                                                    ->first();

                                                if($isChasisExist) {
                                                    // info("case 2");
                                                    return redirect()->back()->with('error', $uploadFileContent['chasis']." Chasis already existing");

                                                }else{
                                                    //  $isNullChaisis = $SimilarRowWithNullChaisis;
                                                    $SimilarRowWithNullChaisis->chasis  = $uploadFileContent['chasis'];
                                                    $SimilarRowWithNullChaisis->save();

                                                    $action = "Chasis Updated";
                                                    $this->inventoryLog($action, $SimilarRowWithNullChaisis->id);

                                                    $updatedRowsIds[] = $SimilarRowWithNullChaisis->id;
                                                    $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                                    $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                                    $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                                    $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                                    $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];
                                                }

                                            }else{
                                                // info("null chasis smilar data not exist => check if any similar model sfx row without having any update yet.");
                                                // info($newlyAddedRowIds);
                                                // info($updatedRowsIds);
                                                $isChasisExist = SupplierInventory::where('chasis', $uploadFileContent['chasis'])
                                                    ->whereNotNull('chasis')
                                                    ->first();
                                                if($isChasisExist) {
                                                    // info("case 3");
                                                    return redirect()->back()->with('error', $uploadFileContent['chasis']." Chasis already existing");

                                                }else{
                                                    $rowWithoutUpdate = SupplierInventory::whereIn('id', $isNullChaisisIds)->whereNotIn('id', $newlyAddedRowIds)
                                                        ->whereNotIn('id', $updatedRowsIds)
                                                        ->whereNotIn('id', $noChangeRowIds)
                                                        ->first();

                                                    // info($rowWithoutUpdate);

                                                    if(!empty($rowWithoutUpdate)) {
                                                        //  $isNullChaisis = $rowWithoutUpdate->first();
                                                        $updatedRowsIds[] = $rowWithoutUpdate->id;

                                                        $rowWithoutUpdate->chasis          = $uploadFileContent['chasis'];
                                                        $rowWithoutUpdate->engine_number   = $uploadFileContent['engine_number'];
                                                        $rowWithoutUpdate->color_code      = $uploadFileContent['color_code'];
                                                        $rowWithoutUpdate->pord_month      = $uploadFileContent['pord_month'];
                                                        // $rowWithoutUpdate->po_arm          = $uploadFileContent['po_arm'];
                                                        $rowWithoutUpdate->eta_import      = $uploadFileContent['eta_import'];
                                                        $rowWithoutUpdate->delivery_note   = $uploadFileContent['delivery_note'];
                                                        $rowWithoutUpdate->interior_color_code_id = $uploadFileContent['interior_color_code_id'];
                                                        $rowWithoutUpdate->exterior_color_code_id = $uploadFileContent['exterior_color_code_id'];
                                                        if($uploadFileContent['delivery_note'] ) {
                                                            if($country == SupplierInventory::COUNTRY_BELGIUM ) {
                                                                if(strcasecmp($uploadFileContent['delivery_note'], SupplierInventory::DN_STATUS_RECEIVED) == 0) {
                                                                    $rowWithoutUpdate->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                                                }
                                                            }else{
                                                                if(is_numeric($uploadFileContent['delivery_note'])) {
                                                                    $rowWithoutUpdate->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                                                }
                                                            }
                                                        }
                                                        $rowWithoutUpdate->updated_by = Auth::id();
                                                        $rowWithoutUpdate->save();

                                                        $action = "Row updated by inventory Excel upload";
                                                        $this->inventoryLog($action, $rowWithoutUpdate->id);


                                                        $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                                        $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                                        $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                                        $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                                        $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];
                                                    }else{
                                                        // info("no row found to update");

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
                                                        // $supplierInventory->po_arm          = $uploadFileContent['po_arm'];
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
                                                        if($uploadFileContent['delivery_note'] ) {
                                                            if($country == SupplierInventory::COUNTRY_BELGIUM ) {
                                                                if(strcasecmp($uploadFileContent['delivery_note'], SupplierInventory::DN_STATUS_RECEIVED) == 0) {
                                                                    $supplierInventory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                                                }
                                                            }else{
                                                                if(is_numeric($uploadFileContent['delivery_note'])) {
                                                                    $supplierInventory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                                                }
                                                            }
                                                        }
                                                        $supplierInventory->updated_by = Auth::id();
                                                        $supplierInventory->save();

                                                        $action = "Inventory Item Added";
                                                        $this->inventoryLog($action, $supplierInventory->id);

                                                        $newlyAddedRowIds[] = $supplierInventory->id;
                                                    }
                                                }
                                            }

                                        } else {

                                            $isChasisExist = SupplierInventory::where('chasis', $uploadFileContent['chasis'])
                                                ->whereNotNull('chasis')
                                                ->first();

                                            if($isChasisExist) {

                                                $updatedRowsIds[] = $isChasisExist->id;
                                                $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                                $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                                $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                                $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                                $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];

                                                $isChasisExist->engine_number   = $uploadFileContent['engine_number'];
                                                $isChasisExist->color_code      = $uploadFileContent['color_code'];
                                                $isChasisExist->interior_color_code_id = $uploadFileContent['interior_color_code_id'];
                                                $isChasisExist->exterior_color_code_id = $uploadFileContent['exterior_color_code_id'];
                                                $isChasisExist->pord_month      = $uploadFileContent['pord_month'];
                                                // $isChasisExist->po_arm          = $uploadFileContent['po_arm'];
                                                $isChasisExist->eta_import      = $uploadFileContent['eta_import'];
                                                $isChasisExist->delivery_note   = $uploadFileContent['delivery_note'];
                                                if($uploadFileContent['delivery_note'] ) {
                                                    if($country == SupplierInventory::COUNTRY_BELGIUM ) {
                                                        if(strcasecmp($uploadFileContent['delivery_note'], SupplierInventory::DN_STATUS_RECEIVED) == 0) {
                                                            $isChasisExist->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                                        }
                                                    }else{
                                                        if(is_numeric($uploadFileContent['delivery_note'])) {
                                                            $isChasisExist->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                                        }
                                                    }
                                                }
                                                $isChasisExist->updated_by = Auth::id();
                                                $isChasisExist->save();

                                                $action = "Row updated by inventory Excel upload";
                                                $this->inventoryLog($action, $isChasisExist->id);

                                            }else{
                                                // already checking chasis above condition.
                                                // info("new chaisis with existing model and sfx => add row");
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
                                                // $supplierInventory->po_arm          = $uploadFileContent['po_arm'];
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
                                                if($uploadFileContent['delivery_note'] ) {
                                                    if($country == SupplierInventory::COUNTRY_BELGIUM ) {
                                                        if(strcasecmp($uploadFileContent['delivery_note'], SupplierInventory::DN_STATUS_RECEIVED) == 0) {
                                                            $supplierInventory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                                        }
                                                    }else{
                                                        if(is_numeric($uploadFileContent['delivery_note'])) {
                                                            $supplierInventory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                                        }
                                                    }
                                                }
                                                $supplierInventory->updated_by = Auth::id();
                                                $supplierInventory->save();

                                                $action = "Inventory Item Added";
                                                $this->inventoryLog($action, $supplierInventory->id);

                                                $newlyAddedRowIds[] = $supplierInventory->id;
                                            }
                                        }
                                    }
                                    else
                                    {
                                        // item existing with chasis, also not existing with similar row data so it is a case of update
                                        // info("inventory with chasis existing.");

//                                        info($supplierInventories->get());
//                                        $supplierInventories = $supplierInventories->where('engine_number', $uploadFileContent['engine_number'])
//                                            ->where('color_code', $uploadFileContent['color_code'])
//                                            ->where('pord_month', $uploadFileContent['pord_month'])
//                                            ->where('eta_import', $uploadFileContent['eta_import'])
//                                            ->where('delivery_note', $deliveryNote)
//                                            ->first();
//
//                                        if (!$supplierInventories)
//                                        {
//                                            info($supplierInventories->pluck('id'));

                                        // info("chasis with detail row not exist update row");
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
                                        // $supplierInventory->po_arm          = $uploadFileContent['po_arm'];
                                        $supplierInventory->eta_import      = $uploadFileContent['eta_import'];
                                        $supplierInventory->delivery_note   = $uploadFileContent['delivery_note'];
                                        if($uploadFileContent['delivery_note'] ) {
                                            if($country == SupplierInventory::COUNTRY_BELGIUM ) {
                                                if(strcasecmp($uploadFileContent['delivery_note'], SupplierInventory::DN_STATUS_RECEIVED) == 0) {
                                                    $supplierInventory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                                }
                                            }else{
                                                if(is_numeric($uploadFileContent['delivery_note'])) {
                                                    $supplierInventory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                                }
                                            }
                                        }
                                        $supplierInventory->updated_by = Auth::id();
                                        $supplierInventory->save();

                                        $action = "Row updated by inventory Excel upload";
                                        $this->inventoryLog($action, $supplierInventory->id);

//                                        }
                                    }
                                }
                                else {
                                    // info("no chasis found=> chcek for updation or add new row");
                                    $modelSfxValuePair = $uploadFileContent['model']."_".$uploadFileContent['sfx'];

                                    $excelPairCount = $groupedExcelPairs[$modelSfxValuePair];
                                    if(array_key_exists($modelSfxValuePair, $groupedExistingPairs)) {
                                        $existingPairCount = $groupedExistingPairs[$modelSfxValuePair];

                                    }else{
                                        $existingPairCount = 0;
                                    }
                                    // info("excel pair count");
                                    // info($excelPairCount);
                                    if($excelPairCount == $existingPairCount) {
                                        $inventoryRow = SupplierInventory::whereIn('master_model_id', $modelIds)
                                            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                                            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                                            ->where('supplier_id', $request->supplier_id)
                                            ->where('whole_sales', $request->whole_sales)
                                            ->whereNull('chasis')
                                            ->whereNotIn('id', $noChangeRowIds)
                                            ->whereNotIn('id', $updatedRowsIds)
                                            ->whereNotIn('id', $newlyAddedRowIds)
                                            // ->where(function ($query) use($deliveryNote) {
                                            //     $query->whereNull('delivery_note')
                                            //         ->orwhere('delivery_note', $deliveryNote);
                                            // })
                                            // hide the DN check scenario : - when dn status changing to waiting to any number it unable to find the row
                                            ->first();
                                        if(!empty($inventoryRow)) {
                                            // info("null row exist");
                                            // info($inventoryRow->id);
                                            $updatedRowsIds[] = $inventoryRow->id;

                                            $inventoryRow->engine_number   = $uploadFileContent['engine_number'];
                                            $inventoryRow->color_code      = $uploadFileContent['color_code'];
                                            $inventoryRow->pord_month      = $uploadFileContent['pord_month'];
                                            // $inventoryRow->po_arm          = $uploadFileContent['po_arm'];
                                            $inventoryRow->eta_import      = $uploadFileContent['eta_import'];
                                            $inventoryRow->delivery_note   = $uploadFileContent['delivery_note'];
                                            $inventoryRow->interior_color_code_id = $uploadFileContent['interior_color_code_id'];
                                            $inventoryRow->exterior_color_code_id = $uploadFileContent['exterior_color_code_id'];
                                            if($uploadFileContent['delivery_note'] ) {
                                                if($country == SupplierInventory::COUNTRY_BELGIUM ) {
                                                    if(strcasecmp($uploadFileContent['delivery_note'], SupplierInventory::DN_STATUS_RECEIVED) == 0) {
                                                        $inventoryRow->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                                    }
                                                }else{
                                                    if(is_numeric($uploadFileContent['delivery_note'])) {
                                                        $inventoryRow->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                                    }
                                                }
                                            }
                                            $inventoryRow->updated_by = Auth::id();
                                            $inventoryRow->save();

                                            $action = "Row updated by inventory Excel upload";
                                            $this->inventoryLog($action, $inventoryRow->id);

                                            $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                            $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                            $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                            $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                            $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];
                                        }
                                        // info($updatedRowsIds);

                                        // info("both colum count equal => clear case of row updation find the row ");

                                    }else if($excelPairCount > $existingPairCount) {
                                        // info("excelpair count");
                                        // info($excelPairCount);
                                        // info("database count");
                                        // info($existingPairCount);
                                        // check for engine number is changed or not
                                        $nullChasisRow = SupplierInventory::whereIn('master_model_id', $modelIds)
                                            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                                            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                                            ->where('supplier_id', $request->supplier_id)
                                            ->where('whole_sales', $request->whole_sales)
                                            ->whereNull('chasis')
                                            ->whereNotIn('id', $noChangeRowIds)
                                            ->whereNotIn('id', $updatedRowsIds)
                                            ->whereNotIn('id', $newlyAddedRowIds)
                                            // ->where(function ($query) use($deliveryNote) {
                                            //     $query->whereNull('delivery_note')
                                            //         ->orwhere('delivery_note', $deliveryNote);
                                            // })
                                            ->first();

                                        if(!empty($nullChasisRow)) {
                                            // info("no existing row with chasis found => update row");
                                            $updatedRowsIds[] = $nullChasisRow->id;

                                            $nullChasisRow->engine_number   = $uploadFileContent['engine_number'];
                                            $nullChasisRow->color_code      = $uploadFileContent['color_code'];
                                            $nullChasisRow->pord_month      = $uploadFileContent['pord_month'];
                                            // $nullChasisRow->po_arm          = $uploadFileContent['po_arm'];
                                            $nullChasisRow->eta_import      = $uploadFileContent['eta_import'];
                                            $nullChasisRow->delivery_note   = $uploadFileContent['delivery_note'];
                                            $nullChasisRow->interior_color_code_id = $uploadFileContent['interior_color_code_id'];
                                            $nullChasisRow->exterior_color_code_id = $uploadFileContent['exterior_color_code_id'];
                                            if($uploadFileContent['delivery_note'] ) {
                                                if($country == SupplierInventory::COUNTRY_BELGIUM ) {
                                                    if(strcasecmp($uploadFileContent['delivery_note'], SupplierInventory::DN_STATUS_RECEIVED) == 0) {
                                                        $nullChasisRow->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                                    }
                                                }else{
                                                    if(is_numeric($uploadFileContent['delivery_note'])) {
                                                        $nullChasisRow->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                                    }
                                                }
                                            }
                                            $nullChasisRow->updated_by = Auth::id();
                                            $nullChasisRow->save();

                                            $action = "Row updated by inventory Excel upload";
                                            $this->inventoryLog($action, $nullChasisRow->id);

                                            $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                            $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                            $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                            $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                            $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];

                                        }else{
                                            $isChasisExist = SupplierInventory::where('chasis', $uploadFileContent['chasis'])
                                                ->whereNotNull('chasis')
                                                ->first();
                                            if($isChasisExist) {
                                                // info("case 5");
                                                return redirect()->back()->with('error', $uploadFileContent['chasis']." Chasis already existing");

                                            }else{
                                                // info("no existing row with no chasis found => add new row");

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
                                                // $supplierInventory->po_arm          = $uploadFileContent['po_arm'];
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
                                                if($uploadFileContent['delivery_note'] ) {
                                                    if($country == SupplierInventory::COUNTRY_BELGIUM ) {
                                                        if(strcasecmp($uploadFileContent['delivery_note'], SupplierInventory::DN_STATUS_RECEIVED) == 0) {
                                                            $supplierInventory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                                        }
                                                    }else{
                                                        if(is_numeric($uploadFileContent['delivery_note'])) {
                                                            $supplierInventory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                                        }
                                                    }
                                                }
                                                $supplierInventory->updated_by = Auth::id();
                                                $supplierInventory->save();

                                                $action = "Inventory Item Added";
                                                $this->inventoryLog($action, $supplierInventory->id);

                                                $newlyAddedRowIds[] = $supplierInventory->id;
                                                // info("coming row count is > existing chance for adding row also check any row updation is there");
                                            }

                                        }

                                    }else{
                                        $nullChasisRow = SupplierInventory::whereIn('master_model_id', $modelIds)
                                            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                                            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                                            ->where('supplier_id', $request->supplier_id)
                                            ->where('whole_sales', $request->whole_sales)
                                            ->whereNull('chasis')
                                            ->whereNotIn('id', $noChangeRowIds)
                                            ->whereNotIn('id', $updatedRowsIds)
                                            ->whereNotIn('id', $newlyAddedRowIds)->first();

                                        if(!empty($nullChasisRow)) {
                                            // info("no existing row with chasis found => update row");
                                            $updatedRowsIds[] = $nullChasisRow->id;

                                            $nullChasisRow->engine_number   = $uploadFileContent['engine_number'];
                                            $nullChasisRow->color_code      = $uploadFileContent['color_code'];
                                            $nullChasisRow->pord_month      = $uploadFileContent['pord_month'];
                                            // $nullChasisRow->po_arm          = $uploadFileContent['po_arm'];
                                            $nullChasisRow->eta_import      = $uploadFileContent['eta_import'];
                                            $nullChasisRow->delivery_note   = $uploadFileContent['delivery_note'];
                                            $nullChasisRow->interior_color_code_id = $uploadFileContent['interior_color_code_id'];
                                            $nullChasisRow->exterior_color_code_id = $uploadFileContent['exterior_color_code_id'];
                                            if($uploadFileContent['delivery_note']) {
                                                if($country == SupplierInventory::COUNTRY_BELGIUM ) {
                                                    if(strcasecmp($uploadFileContent['delivery_note'], SupplierInventory::DN_STATUS_RECEIVED) == 0) {
                                                        $nullChasisRow->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                                    }
                                                }else{
                                                    if(is_numeric($uploadFileContent['delivery_note'])) {
                                                        $nullChasisRow->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                                    }
                                                }
                                            }
                                            $nullChasisRow->updated_by = Auth::id();
                                            $nullChasisRow->save();

                                            $action = "Row updated by inventory Excel upload";
                                            $this->inventoryLog($action, $nullChasisRow->id);

                                            $updatedRows[$i]['model'] = $uploadFileContent['model'];
                                            $updatedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                                            $updatedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                                            $updatedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                                            $updatedRows[$i]['color_code'] = $uploadFileContent['color_code'];
                                        }
                                        // info("coming row count is lesser it may be deleted or updation");
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
                        // $supplierInventoryHistory->po_arm          = $uploadFileContent['po_arm'];
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
                        if($uploadFileContent['delivery_note'] ) {
                            if($country == SupplierInventory::COUNTRY_BELGIUM ) {
                                if(strcasecmp($uploadFileContent['delivery_note'], SupplierInventory::DN_STATUS_RECEIVED) == 0) {
                                    $supplierInventoryHistory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                }
                            }else{
                                if(is_numeric($uploadFileContent['delivery_note'])) {
                                    $supplierInventoryHistory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                }
                            }
                        }
                        $supplierInventoryHistory->updated_by = Auth::id();
                        $supplierInventoryHistory->save();
                    }
                    // to find deleted rows
                    // group the value pair to get count of duplicate data

                    $deletedRows = SupplierInventory::where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                        ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                        ->where('supplier_id', $request->supplier_id)
                        ->where('whole_sales', $request->whole_sales)
                        ->whereNotIn('id', $noChangeRowIds)
                        ->whereNotIn('id', $updatedRowsIds)
                        ->whereNotIn('id', $newlyAddedRowIds)->get();

                    // info("deleted rows");
                    // info($deletedRows->pluck('id'));
                    $previousFileDate = SupplierInventoryHistory::orderBy('date_of_entry', 'DESC')
                        ->groupBy('date_of_entry')
                        ->skip(1)
                        ->first();

                    foreach ($deletedRows as $deletedRow) {
                        // info("deleted row id");
                        // info($deletedRow);
                        $deletedRow->upload_status = SupplierInventory::VEH_STATUS_DELETED;
                        $deletedRow->save();

                        if($previousFileDate) {
                            $isSameRowExist = SupplierInventoryHistory::whereDate('date_of_entry', $previousFileDate->date_of_entry)
                                ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                                ->whereNot('upload_status', SupplierInventory::VEH_STATUS_DELETED)
                                ->where('supplier_id', $deletedRow->supplier_id)
                                ->where('whole_sales', $deletedRow->whole_sales)
                                ->where('master_model_id', $deletedRow->master_model_id)
                                ->where('chasis', $deletedRow->chasis)
                                ->where('engine_number', $deletedRow->engine_number)
                                ->where('color_code', $deletedRow->color_code)
                                ->where('pord_month', $deletedRow->pord_month)
                                // ->where('po_arm', $deletedRow->po_arm)
                                ->where('eta_import', $deletedRow->eta_import)
                                ->where('delivery_note', $deletedRow->delivery_note)
                                ->first();

                            // info("similar deleted rows");
                            // info($isSameRowExist);

                            if($isSameRowExist) {
//                                return $isSameRowExist;
                                $isSameRowExist->upload_status = SupplierInventory::VEH_STATUS_DELETED;
                                $isSameRowExist->save();
                            }
                        }

                        $action = "Inventory Item deleted";
                        $this->inventoryLog($action, $deletedRow->id);
                    }

                    // info("updated rows");
                    // info($updatedRowsIds);

                    // info("newly added row");
                    // info($newlyAddedRowIds);

                    // info("no change row Ids");
                    // info($noChangeRowIds);

                    $this->LOIItemIdMapping($request);

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
                        $isChasisExist = SupplierInventory::where('chasis', $uploadFileContent['chasis'])
                            ->whereNotNull('chasis')->first();
                        if($isChasisExist) {
                            // info("case 6");
                            return redirect()->back()->with('error', $uploadFileContent['chasis']." Chasis already existing");

                        }else{

                            $supplierInventory = new SupplierInventory();

                            $supplierInventory->master_model_id = $model->id;
                            $supplierInventory->chasis          = $uploadFileContent['chasis'];
                            $supplierInventory->engine_number   = $uploadFileContent['engine_number'];
                            $supplierInventory->color_code      = $uploadFileContent['color_code'];
                            $supplierInventory->pord_month      = $uploadFileContent['pord_month'];
                            // $supplierInventory->po_arm          = $uploadFileContent['po_arm'];
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
                            if($uploadFileContent['delivery_note'] ) {
                                if($country == SupplierInventory::COUNTRY_BELGIUM ) {
                                    if(strcasecmp($uploadFileContent['delivery_note'], SupplierInventory::DN_STATUS_RECEIVED) == 0) {
                                        $supplierInventory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                    }
                                }else{
                                    if(is_numeric($uploadFileContent['delivery_note'])) {
                                        $supplierInventory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                    }
                                }
                            }
                            $supplierInventory->updated_by = Auth::id();
                            $supplierInventory->save();

                            $action = "Inventory Item Added";
                            $this->inventoryLog($action, $supplierInventory->id);


                            $supplierInventoryHistory = new SupplierInventoryHistory();

                            $supplierInventoryHistory->master_model_id = $model->id;
                            $supplierInventoryHistory->chasis          = $uploadFileContent['chasis'];
                            $supplierInventoryHistory->engine_number   = $uploadFileContent['engine_number'];
                            $supplierInventoryHistory->color_code      = $uploadFileContent['color_code'];
                            $supplierInventoryHistory->pord_month      = $uploadFileContent['pord_month'];
                            // $supplierInventoryHistory->po_arm          = $uploadFileContent['po_arm'];
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
                                if($country == SupplierInventory::COUNTRY_BELGIUM ) {
                                    if(strcasecmp($uploadFileContent['delivery_note'], SupplierInventory::DN_STATUS_RECEIVED) == 0) {
                                        $supplierInventoryHistory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                    }
                                }else{
                                    if(is_numeric($uploadFileContent['delivery_note'])) {
                                        $supplierInventoryHistory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                                    }
                                }
                            }
                            $supplierInventoryHistory->updated_by = Auth::id();
                            $supplierInventoryHistory->save();
                        }
                    }

                    $this->LOIItemIdMapping($request);

                    DB::commit();

                    return redirect()->route('supplier-inventories.create')->with('message','supplier inventory updated successfully');
                }
            }
        }
    }
    public function LOIItemIdMapping(Request $request) {

        $inventoryItems = SupplierInventory::where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
            ->where('supplier_id', $request->supplier_id)
            ->where('whole_sales', $request->whole_sales)
            ->whereNull('purchase_order_id')
            ->where(function ($query)  {
                $query->whereNull('delivery_note')
                    ->orwhere('delivery_note', SupplierInventory::DN_STATUS_WAITING)
                    ->orwhere('delivery_note', "waiting");
            })
            ->get();

        $alreadyAddedIds = [];
        $QtyfullyAddedIds = [];
        $pfiQtyAddedIds = [];
        $PfiQtyfullyAddedIds = [];

        foreach ($inventoryItems as $inventoryItem) {

            $modelIds = MasterModel::where('model', $inventoryItem->masterModel->model)
                ->where('sfx', $inventoryItem->masterModel->sfx)
                ->pluck('id')->toArray();
            $dealer = $request->whole_sales;
            $loiMappingCriterias = LOIMappingCriteria::orderBy('order','ASC')->get();

            foreach ($loiMappingCriterias as $loiMappingCriteria) {
                $value = $loiMappingCriteria->value;

                if($loiMappingCriteria->value_type == LOIMappingCriteria::TYPE_MONTH) {
                    $whereColumn =  Carbon::now()->subMonth($value);
                }else{
                    $whereColumn =  Carbon::now()->subYear($value);
                }

                $LOIItem = LetterOfIndentItem::with('LOI')->whereIn('master_model_id', $modelIds)
                            ->whereHas('LOI', function ($query) use($modelIds, $dealer, $whereColumn) {
                                $query->where('submission_status', LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED)
                                    ->whereBetween('date',[$whereColumn, Carbon::now()])
                                    ->where('dealers', $dealer);
                            })
                            ->whereNotIn('id', $QtyfullyAddedIds)
                            ->whereRaw('utilized_quantity < quantity')
                            ->orderBy('letter_of_indent_id','ASC')
                            ->first();

                if($LOIItem) {
                     break;
                }
            }

            if($LOIItem) {
                $remaingQuantity = $LOIItem->quantity - $LOIItem->utilized_quantity;
                $assignedQuantity = 0;
                if(array_key_exists($LOIItem->id, $alreadyAddedIds)) {
                    $assignedQuantity = $alreadyAddedIds[$LOIItem->id];
                    $actualQuantityRemaining = $remaingQuantity - $assignedQuantity;
                }else{
                    $actualQuantityRemaining = $remaingQuantity;
                }
                if($actualQuantityRemaining <= $LOIItem->quantity) {

                    if($actualQuantityRemaining <= 1) {
                        $QtyfullyAddedIds[] = $LOIItem->id;
                    }
                    $alreadyAddedIds[$LOIItem->id] = $assignedQuantity + 1;
                    $inventoryItem->letter_of_indent_item_id = $LOIItem->id;
                    $inventoryItem->save();
                }

                $approvedLOI = ApprovedLetterOfIndentItem::where('letter_of_indent_item_id', $LOIItem->id)
                                                    ->where('is_pfi_created', true)
                                                    ->whereNotIn('id', $PfiQtyfullyAddedIds)
                                                    ->first();
                    if($approvedLOI) {
                        $assignedPfiQuantity = 0;
                        if(array_key_exists($approvedLOI->id, $pfiQtyAddedIds)) {
                            $assignedPfiQuantity =  $pfiQtyAddedIds[$approvedLOI->id];
                            $actualPfiQuantityRemaining = $approvedLOI->quantity - $assignedPfiQuantity;
                        }else{
                            $actualPfiQuantityRemaining = $approvedLOI->quantity;
                        }

                        if($actualPfiQuantityRemaining <= $approvedLOI->quantity) {

                            if($actualPfiQuantityRemaining <= 1) {
                                $PfiQtyfullyAddedIds[] = $approvedLOI->id;
                            }
                            $alreadyAddedIds[$LOIItem->id] = $assignedQuantity + 1;
                            $inventoryItem->pfi_id = $approvedLOI->pfi_id;
                            $inventoryItem->save();
                        }
                    }

            }
        }
    }
    public function updateInventory(Request $request) {
        (new UserActivityController)->createActivity('Update the Supplier Inventories');

        $updatedDatas = $request->selectedUpdatedDatas;
        // info($request->all());

        DB::beginTransaction();

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
                    $extColour = mb_substr($colourcode, 0, -2);
                    $intColour = mb_substr($colourcode,  -2);
                    info($extColour);
                    info($intColour);
                    if($extColour) {
                        $extColourRow = ColorCode::where('code', $extColour)
                            ->where('belong_to', ColorCode::EXTERIOR)
                            ->first();
                            info($extColourRow);
                        if ($extColourRow)
                        {
                            $exteriorColorId = $extColourRow->id;
                        }
                    }
                    if($intColour) {
                        $intColourRow = ColorCode::where('code', $intColour)
                            ->where('belong_to', ColorCode::INTERIOR)
                            ->first();
                        if ($intColourRow)
                        {
                            $interiorColorId = $intColourRow->id;
                        }
                    }
                    $inventory->$fieldName = $fieldValue;
                    $inventory->interior_color_code_id = $interiorColorId;
                    $inventory->exterior_color_code_id = $exteriorColorId;
                }
            }else if($fieldName == 'delivery_note') {
                $inventory->$fieldName = $fieldValue;
                if ($fieldValue) {
                    // info("delivery note found ");
                    if($inventory->country == SupplierInventory::COUNTRY_BELGIUM ) {
                        // info("country belgium");
                        if(strcasecmp($fieldValue, SupplierInventory::DN_STATUS_RECEIVED) == 0) {
                            $inventory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                        }
                    }else{
                        // info("country UAE");
                        if(is_numeric($fieldValue)) {
                            $inventory->veh_status = SupplierInventory::STATUS_DELIVERY_CONFIRMED;
                        }
                    }
                }
            }
            else{
               $inventory->$fieldName = $fieldValue;
            }

            $action = str_replace('_', ' ', $fieldName) ." updated";
            (new SupplierInventoryController)->inventoryLog($action, $inventory->id);

            $inventory->updated_by = Auth::id();
            $inventory->save();
        }
        DB::commit();

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
           ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
            ->where('supplier_id', $request->supplier_id)
            ->where('whole_sales', $request->whole_sales)
            // ->whereNull('chasis')
            ->get();
        $secondFileRowDetails = SupplierInventoryHistory::whereDate('date_of_entry', $request->second_file)
        //    ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
//            ->whereNull('delivery_note')
            ->where('supplier_id', $request->supplier_id)
            ->where('whole_sales', $request->whole_sales)
            ->get();

        $firstFileItems = [];
        $secondFileItems = [];

        foreach ($firstFileRowDetails as $firstFileRowDetail) {
            $firstFileItems[] = $firstFileRowDetail->masterModel->model .'_'.$firstFileRowDetail->masterModel->sfx;
        }
        // $secondFileDetails = $secondFileRowDetails->whereNull('chasis');
        foreach ($secondFileRowDetails as $secondFileDetail) {
            $secondFileItems[] = $secondFileDetail->masterModel->model .'_'.$secondFileDetail->masterModel->sfx;
        }

        $secondFileItemPairs =  array_count_values($secondFileItems);
        $firstFileItemPairs = array_count_values($firstFileItems);

        foreach ($secondFileRowDetails as $secondFileRowDetail)
        {
            // info("chasis");
            // info($secondFileDetail['chasis']);

            $model = $secondFileRowDetail->masterModel->model;
            $sfx = $secondFileRowDetail->masterModel->sfx;
            // info($model);
            // info($sfx);
            $modelIds = MasterModel::where('model', $model)
                ->where('sfx', $sfx)
                ->pluck('id')->toArray();

            $deliveryNote = $secondFileRowDetail['delivery_note'];
            // info("delivery note");
            // info($deliveryNote);
            // after having DN data their is no changes for data of thata ro.so consider the data without eta import for inventory
            $supplierInventories = SupplierInventoryHistory::whereIn('master_model_id', $modelIds)
                            ->whereDate('date_of_entry', $request->first_file)
                            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                            // ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                            ->where('supplier_id', $request->supplier_id)
                            ->where('whole_sales', $request->whole_sales)
                            ->where(function ($query) use($deliveryNote) {
                                $query->whereNull('delivery_note')
                                    ->orwhere('delivery_note', $deliveryNote);
                            });

                    // info($supplierInventories->count());

            if ($supplierInventories->count() <= 0)
            {
                // info("no row existing with model,sfx model year so => add new row");

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
                    // ->where('po_arm', $secondFileRowDetail['po_arm'])
                    ->whereNotIn('id', $noChangeRowIds)
                    ->whereNotIn('id', $updatedRowsIds)
                    ->where('eta_import', $secondFileRowDetail['eta_import'])
                    ->where('delivery_note', $secondFileRowDetail['delivery_note'])
                    ->first();

                // info($isSimilarRowExist);
                // info("model sfx modelyear combination row existing");
                if(empty($isSimilarRowExist)) {
                    if (!empty($secondFileRowDetail['chasis']))
                    {
                        // info($secondFileRowDetail['chasis']);
                        // info($modelIds);
                        // Store the Count into Update the Row with data
                        $supplierInventory = SupplierInventoryHistory::whereIn('master_model_id', $modelIds)
                            ->whereDate('date_of_entry', $request->first_file)
                            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                            // ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
//                            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                            ->where('supplier_id', $request->supplier_id)
                            ->where('whole_sales', $request->whole_sales)
                            ->whereNotIn('id', $noChangeRowIds)
                            ->whereNotIn('id', $updatedRowsIds)
                            ->where('chasis', $secondFileRowDetail['chasis'])
//                                        ->whereNull('delivery_note') unable to find row when have delivery note
                            // ->where(function ($query) use($deliveryNote) {
                            //     $query->whereNull('delivery_note')
                            //         ->orwhere('delivery_note', $deliveryNote);
                            // })
                            ->first();

                        // info($supplierInventory);

                        if (empty($supplierInventory)) {
                            // info("chasis matching row not avaialble, case of chasis updation ore new chaisi row add");
                            //adding new row simply
                            $isNullChaisis = SupplierInventoryHistory::whereIn('master_model_id', $modelIds)
                                ->whereDate('date_of_entry', $request->first_file)
                                ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
//                                ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                                ->where('supplier_id', $request->supplier_id)
                                ->where('whole_sales', $request->whole_sales)
                                ->whereNotIn('id', $noChangeRowIds)
                                ->whereNotIn('id', $updatedRowsIds)
                                ->whereNull('chasis');
                                // ->where(function ($query) use($deliveryNote) {
                                //     $query->whereNull('delivery_note')
                                //         ->orwhere('delivery_note', $deliveryNote);
                                // });
                            $isNullChaisisIds = $isNullChaisis->pluck('id');

                            // info($isNullChaisis->get());
                            if ($isNullChaisis->count() > 0) {
                                // info("null chasis row exist");
                                // find the null chaisis row
                                // null chaisis existing => updating row
                                // find the row with similar details.
                                $SimilarRowWithNullChaisis = $isNullChaisis->where('engine_number', $secondFileRowDetail['engine_number'])
                                    ->where('color_code', $secondFileRowDetail['color_code'])
                                    ->where('pord_month', $secondFileRowDetail['pord_month'])
                                    // ->where('po_arm', $secondFileRowDetail['po_arm'])
                                    ->where('eta_import', $secondFileRowDetail['eta_import'])
                                    ->where('delivery_note', $secondFileRowDetail['delivery_note'])
                                    ->first();

                                // info($SimilarRowWithNullChaisis);
                                if(!empty($SimilarRowWithNullChaisis)) {
                                    // info("null chasis with smilar data exist");

                                    $updatedRowsIds[] = $SimilarRowWithNullChaisis->id;
                                    $updatedRows[$i]['model'] = $model;
                                    $updatedRows[$i]['sfx'] = $sfx;
                                    $updatedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                                    $updatedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                                    $updatedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];

                                }else{
                                    // info("null chasis smilar data not exist => check if any similar model sfx row without having any update yet.");
                                    // info($updatedRowsIds);

                                    $rowWithoutUpdate = SupplierInventoryHistory::whereIn('id', $isNullChaisisIds)
                                        ->whereDate('date_of_entry', $request->first_file)
                                        ->whereNotIn('id', $updatedRowsIds)
                                        ->whereNotIn('id', $noChangeRowIds)->first();

                                    // info($rowWithoutUpdate);

                                    if(!empty($rowWithoutUpdate)) {
                                        // info("update row");
                                        $updatedRowsIds[] = $rowWithoutUpdate->id;

                                        $updatedRows[$i]['model'] = $model;
                                        $updatedRows[$i]['sfx'] = $sfx;
                                        $updatedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                                        $updatedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                                        $updatedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];
                                    }else{
                                        // info("no row found to update => add new");
                                        $newlyAddedRows[$i]['model'] = $secondFileRowDetail['model'];
                                        $newlyAddedRows[$i]['sfx'] = $secondFileRowDetail['sfx'];
                                        $newlyAddedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                                        $newlyAddedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                                        $newlyAddedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];

//                                        $newlyAddedRowIds[] = $supplierInventory->id;
                                    }
                                }

                            } else {
                                // info("new chaisis with existing model and sfx => add row");
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
                            // info("inventory with chasis existing.");
                            // inventory with chasis existing...

                            if(!empty($secondFileRowDetail['delivery_note'])) {
                                $supplierInventories = $supplierInventories->where('delivery_note', $secondFileRowDetail['delivery_note']);
                            }
                            $supplierInventories = $supplierInventories->where('engine_number', $secondFileRowDetail['engine_number'])
                                ->where('color_code', $secondFileRowDetail['color_code'])
                                ->where('pord_month', $secondFileRowDetail['pord_month'])
                                // ->where('po_arm', $secondFileRowDetail['po_arm'])
                                ->where('eta_import', $secondFileRowDetail['eta_import'])
                                ->first();

                            if (!$supplierInventories)
                            {
                                // info("chasis with detail row not exist update row");
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
                        // info("no chasis found=> chcek for updation or add new row");
                        $modelSfxValuePair = $model."_".$sfx;

                        $secondFilePairCount = $secondFileItemPairs[$modelSfxValuePair];
                        if(array_key_exists($modelSfxValuePair, $firstFileItemPairs)) {
                            $firstFilePairCount = $firstFileItemPairs[$modelSfxValuePair];

                        }else{
                            $firstFilePairCount = 0;

                        }
                        // info("first pair count");

                        // info($firstFilePairCount);
                        // info("second pair count");

                        // info($secondFilePairCount);

                        if($secondFilePairCount == $firstFilePairCount) {
                            $inventoryRow = SupplierInventoryHistory::whereIn('master_model_id', $modelIds)
                                ->whereDate('date_of_entry', $request->first_file)
                                ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
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

                            // info("both colum count equal => clear case of row updation find the row ");

                        }else if($secondFilePairCount > $firstFilePairCount) {
                            // check for engine number is changed or not
                            $nullChasisRow = SupplierInventoryHistory::whereIn('master_model_id', $modelIds)
                                ->whereDate('date_of_entry', $request->first_file)
                                 ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
//                                ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                                ->where('supplier_id', $request->supplier_id)
                                ->where('whole_sales', $request->whole_sales)
                                ->whereNull('chasis')
                                ->whereNotIn('id', $noChangeRowIds)
                                ->whereNotIn('id', $updatedRowsIds)
//                                ->whereNotIn('id', $newlyAddedRowIds)
                                ->first();

                            if(!empty($nullChasisRow)) {
                                // info("no existing row with chasis found => update row");
                                $updatedRowsIds[] = $nullChasisRow->id;

                                $updatedRows[$i]['model'] = $model;
                                $updatedRows[$i]['sfx'] = $sfx;
                                $updatedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                                $updatedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                                $updatedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];

                            }else{
                                // info("no existing row with no chasis found => add new row");

                                $newlyAddedRows[$i]['model'] = $model;
                                $newlyAddedRows[$i]['sfx'] = $sfx;
                                $newlyAddedRows[$i]['chasis'] = $secondFileRowDetail['chasis'];
                                $newlyAddedRows[$i]['engine_number'] = $secondFileRowDetail['engine_number'];
                                $newlyAddedRows[$i]['color_code'] = $secondFileRowDetail['color_code'];

//                                $newlyAddedRowIds[] = $supplierInventory->id;
                                // info("coming row count is > existing chance for adding row also check any row updation is there");
                            }

                        }else{
                            $nullChasisRow = SupplierInventoryHistory::whereIn('master_model_id', $modelIds)
                                ->whereDate('date_of_entry', $request->first_file)
                                ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
//                                ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                                ->where('supplier_id', $request->supplier_id)
                                ->where('whole_sales', $request->whole_sales)
                                ->whereNull('chasis')
                                ->whereNotIn('id', $noChangeRowIds)
                                ->whereNotIn('id', $updatedRowsIds)
//                                ->whereNotIn('id', $newlyAddedRowIds)
                                ->first();

                            if(!empty($nullChasisRow)) {
                                // info("no existing row with chasis found => update row");
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

        $deliveredInventories =  SupplierInventoryHistory::where('supplier_id', $request->supplier_id)
                                                ->where('whole_sales', $request->whole_sales)
                                                ->whereBetween('date_of_entry', [$request->first_file, $request->second_file])
                                                ->where('veh_status', SupplierInventory::STATUS_DELIVERY_CONFIRMED)
                                                ->get();

//        $updatedRows = SupplierInventoryHistory::where('supplier_id', $request->supplier_id)
//                                        ->where('whole_sales', $request->whole_sales)
//                                        ->whereIn('id', $updatedRowsIds)
//                                        ->orwhereIn('id', $deliveredInventories)
//                                        ->groupBy('id')
//                                        ->get();

//        return $updatedRows;
        // info("updated Rows");
        // info($updatedRowsIds);


        $deletedRows =  SupplierInventoryHistory::where('supplier_id', $request->supplier_id)
            ->where('whole_sales', $request->whole_sales)->whereDate('date_of_entry', '>=', $request->first_file)
            ->whereDate('date_of_entry', '<' ,$request->second_file)
            ->where('upload_status', SupplierInventory::VEH_STATUS_DELETED)
            ->get();

            // info($deletedRows->pluck('id'));

        return view('supplier_inventories.file_comparision',compact('newlyAddedRows',
            'deletedRows','updatedRows','suppliers','deliveredInventories'));
    }
    public function lists(Request $request) {
        (new UserActivityController)->createActivity('open the listing page of inventory.');

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

        $isChasisExist = SupplierInventory::where('chasis',  $request->chasis)
                                      ->whereNotNull('chasis');

        if($request->inventoryId) {
            $isChasisExist = $isChasisExist->whereNot('id', $request->inventoryId);
        }
        if($isChasisExist->count() > 0) {
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
        $extColour = mb_substr($colourcode, 0, -2);
        $intColour = mb_substr($colourcode,  -2);

        $extColourRow = ColorCode::where('code', $extColour)
            ->where('belong_to', ColorCode::EXTERIOR)
            ->first();
        $intColourRow = ColorCode::where('code', $intColour)
            ->where('belong_to', ColorCode::INTERIOR)
            ->first();
            info($extColour);
            info($intColour);
        $data = 0;
       if($intColourRow && $extColourRow) {
           $data = 1;
       }
       return response($data);
    }
    public function uniqueProductionMonth(Request $request) {

        $production_month = $request->prod_month;
        $modelYear = substr($production_month, 0, -2);
        $productionMonth = substr($production_month, -2);

        $modelYearCalculationCategories = ModelYearCalculationCategory::all();

        foreach ($modelYearCalculationCategories as $modelYearCalculationCategory) {

            $isItemExistCategory = MasterModel::select(['id', 'model', 'sfx', 'variant_id'])
                ->where('model', $request->model)
                ->where('sfx', $request->sfx)
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
        // info($modelYear);

        $isExistModelCombination =  MasterModel::where('model', $request->model)
            ->where('sfx', $request->sfx)
            ->where('model_year', $modelYear)
            ->first();

        if($isExistModelCombination) {
            $data = 1;
        }else{
            $data = $modelYear;
        }

        return response($data);

    }
    public function inventoryLogs($id)
    {
        (new UserActivityController)->createActivity('Open the supplier inventory log listing page');

        $supplierInventoryLogs = SupplierInventoryLog::where('supplier_inventory_id', $id)->orderBy('id', 'DESC')->get();

        return view('supplier_inventories.inventory_logs.index', compact('supplierInventoryLogs'));
    }
    public function checkDeliveryNote(Request $request)
    {
        $DN_WAITING = strcasecmp($request->delivery_note, SupplierInventory::DN_STATUS_WAITING);
        $DN_RECEIVED = strcasecmp($request->delivery_note, SupplierInventory::DN_STATUS_RECEIVED);
        $isValidDeliveryNote = 1;
        if($request->data_from == 'CREATE') {
            $country = $request->country[0];
        }else{
            $country = $request->country;
        }
        if($country == SupplierInventory::COUNTRY_BELGIUM ) {
            if($DN_WAITING != 0) {
                if($DN_RECEIVED != 0 ) {
                    $isValidDeliveryNote = 0;
                }
            }
        }else{
            if($DN_WAITING != 0) {
                if(!is_numeric($request->delivery_note)) {
                    $isValidDeliveryNote = 0;
                }
            }
        }
        return response($isValidDeliveryNote);
    }
}
