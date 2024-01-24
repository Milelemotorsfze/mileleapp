<?php

namespace App\Http\Controllers;

use App\Models\ColorCode;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Demand;
use App\Models\DemandList;
use App\Models\LetterOfIndent;
use App\Models\LetterOfIndentDocument;
use App\Models\LetterOfIndentItem;
use App\Models\MasterModel;
use App\Models\ModelYearCalculationCategory;
use App\Models\Supplier;
use App\Models\SupplierInventory;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Monarobase\CountryList\CountryListFacade;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Storage;

class LetterOfIndentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $newLOIs = LetterOfIndent::with('letterOfIndentItems','LOIDocuments')
            ->orderBy('id','DESC')
            ->where('status',LetterOfIndent::LOI_STATUS_NEW)
            ->cursor();
//        $approvedLOIs = LetterOfIndent::with('letterOfIndentItems','LOIDocuments')
//            ->orderBy('id','DESC')
//            ->whereIn('status',[LetterOfIndent::LOI_STATUS_APPROVED,LetterOfIndent::LOI_STATUS_PARTIAL_PFI_CREATED])
//            ->cursor();
        $partialApprovedLOIs =  LetterOfIndent::with('letterOfIndentItems','LOIDocuments')
            ->orderBy('id','DESC')
            ->whereIn('status', [LetterOfIndent::LOI_STATUS_PARTIAL_APPROVED,LetterOfIndent::LOI_STATUS_PARTIAL_PFI_CREATED,LetterOfIndent::LOI_STATUS_APPROVED])
            ->cursor();
        $supplierApprovedLOIs =  LetterOfIndent::with('letterOfIndentItems','LOIDocuments')
            ->orderBy('id','DESC')
            ->where('status', LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED)
            ->cursor();
        $rejectedLOIs =  LetterOfIndent::with('letterOfIndentItems','LOIDocuments')
            ->orderBy('id','DESC')
            ->where('status', LetterOfIndent::LOI_STATUS_SUPPLIER_REJECTED)
            ->cursor();

        return view('letter_of_indents.index', compact('newLOIs',
            'partialApprovedLOIs','supplierApprovedLOIs','rejectedLOIs'));
    }
    public function getSupplierLOI(Request $request)
    {
        $supplierId = null;
        $suppliers = Supplier::with('supplierTypes')
            ->whereHas('supplierTypes', function ($query) {
                $query->where('supplier_type', Supplier::SUPPLIER_TYPE_DEMAND_PLANNING);
            })
            ->where('status', Supplier::SUPPLIER_STATUS_ACTIVE)
            ->get();

        $approvalPendingLOIs = LetterOfIndent::with('letterOfIndentItems','LOIDocuments')
            ->orderBy('id','DESC')
            ->where('status', LetterOfIndent::LOI_STATUS_NEW);

        $approvedLOIs = LetterOfIndent::with('letterOfIndentItems','LOIDocuments')
            ->orderBy('id','DESC')
//            ->where('status',LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED)
            ->where('submission_status',LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED);

        $rejectedLOIs =  LetterOfIndent::with('letterOfIndentItems','LOIDocuments')
            ->orderBy('id','DESC')
            ->where('status', LetterOfIndent::LOI_STATUS_SUPPLIER_REJECTED)
            ->where('submission_status',LetterOfIndent::LOI_STATUS_SUPPLIER_REJECTED);


        if ($request->supplier_id)
        {
            $supplierId = $request->supplier_id;
            $approvalPendingLOIs = $approvalPendingLOIs->where('supplier_id', $request->supplier_id);
            $approvedLOIs =  $approvedLOIs->where('supplier_id', $request->supplier_id);
            $rejectedLOIs = LetterOfIndent::orderBy('id','DESC')
                ->where('status', LetterOfIndent::LOI_STATUS_SUPPLIER_REJECTED)
                ->where('supplier_id', $request->supplier_id);
        }

        $approvalPendingLOIs = $approvalPendingLOIs->get();
        $approvedLOIs = $approvedLOIs->get();
        $rejectedLOIs = $rejectedLOIs->get();

        return view('letter_of_indents.supplier_LOIs.index', compact('approvedLOIs',
            'approvalPendingLOIs','rejectedLOIs','suppliers','supplierId'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
//        $LOI = LetterOfIndent::where('submission_status', LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED)
//            ->whereBetween('date',[Carbon::now()->subMonth(6), Carbon::now()])
//            ->where('dealers', 'Trans Cars')
//            ->whereHas('letterOfIndentItems', function ($query) {
//                $query()
//            })
//            ->get();
//
//        dd($LOI->pluck('date'));

        $countries = Country::all();
        $customers = Customer::all();
        $models = MasterModel::whereNotNull('transcar_loi_description')->groupBy('model')->orderBy('id','ASC')->get();

        return view('letter_of_indents.create',compact('countries','customers','models'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

//        dd($request->all());

        $request->validate([
            'customer_id' => 'required',
            'category' => 'required',
            'date' => 'required',
            'dealers' => 'required',
        ]);

        $LOI = LetterOfIndent::where('customer_id', $request->customer_id)
            ->whereDate('date', Carbon::createFromFormat('Y-m-d', $request->date))
            ->where('category', $request->category)
            ->where('status', LetterOfIndent::LOI_STATUS_NEW)
            ->first();

        if (!$LOI)
        {
            DB::beginTransaction();

            $LOI = new LetterOfIndent();
            $LOI->customer_id = $request->customer_id;
            $LOI->date = Carbon::createFromFormat('Y-m-d', $request->date);
            $LOI->category = $request->category;
            $LOI->dealers = $request->dealers;
            $LOI->prefered_location = $request->prefered_location;
            $LOI->so_number = $request->so_number;
            $LOI->destination = $request->destination;
            $LOI->submission_status = LetterOfIndent::LOI_SUBMISION_STATUS_NEW;
            $LOI->status = LetterOfIndent::LOI_STATUS_NEW;
            $LOI->created_by = Auth::id();

            if ($request->has('loi_signature'))
            {
                $file = $request->file('loi_signature');
                $extension = $file->getClientOriginalExtension();
                $fileName = time().'.'.$extension;
                $destinationPath = 'LOI-Signature';
                $file->move($destinationPath, $fileName);

                if(!\Illuminate\Support\Facades\File::isDirectory($destinationPath)) {
                    \Illuminate\Support\Facades\File::makeDirectory($destinationPath, $mode = 0777, true, true);
                }
                $filePath = public_path('LOI-Signature/' . $file);
                $LOI->signature = $fileName;

            }

            $LOI->save();

            if ($request->has('files'))
            {
                foreach ($request->file('files') as $key => $file)
                {
                    $extension = $file->getClientOriginalExtension();
                    $fileName = $key.time().'.'.$extension;
                    $destinationPath = 'LOI-Documents';
                    $file->move($destinationPath, $fileName);
                    $LoiDocument = new LetterOfIndentDocument();

                    $LoiDocument->loi_document_file = $fileName;
                    $LoiDocument->letter_of_indent_id = $LOI->id;
                    $LoiDocument->save();
                }
            }
            $quantities = $request->quantity;
            foreach ($quantities as $key => $quantity) {
                $masterModel = MasterModel::where('sfx', $request->sfx[$key])
                    ->where('model', $request->models[$key])
                    ->where('model_year', $request->model_year[$key])
                    ->first();
                if($masterModel) {
                    $LOIItem = new LetterOfIndentItem();
                    $LOIItem->letter_of_indent_id  = $LOI->id;
                    $LOIItem->master_model_id = $masterModel->id ?? '';
                    $LOIItem->quantity = $quantity;
                    $LOIItem->save();
                }
            }

            DB::commit();

            return redirect()->route('letter-of-indents.index')->with('success',"LOI Created successfully");

        }else{

            return redirect()->back()->with('error', "LOI with this customer and date and category is already exist.");
        }
    }
    public function getCustomers(Request $request)
    {
        $customers = Customer::where('country_id', $request->country)
            ->where('type', $request->customer_type)
            ->get();

        return $customers;
    }
    public function generateLOI(Request $request)
    {
        $letterOfIndent = LetterOfIndent::where('id',$request->id)->first();
        $letterOfIndentItems = LetterOfIndentItem::where('letter_of_indent_id', $request->id)->get();

        if ($request->type == 'TRANS_CAR') {
            $height = $request->height;
            $width = $request->width;

            if($request->download == 1) {
                $pdfFile = Pdf::loadView('letter_of_indents.LOI-templates.trans_car_loi_download_view',
                    compact('letterOfIndent','letterOfIndentItems','height','width'));

                $filename = 'LOI_'.$letterOfIndent->id.date('Y_m_d').'.pdf';
                $directory = public_path('LOI');
                \Illuminate\Support\Facades\File::makeDirectory($directory, $mode = 0777, true, true);
                $pdfFile->save($directory . '/' . $filename);
                $pdf = $this->pdfMerge($letterOfIndent->id);
                return $pdf->Output('LOI_'.date('Y_m_d').'.pdf','D');

            }
            return view('letter_of_indents.LOI-templates.trans_car_loi_template', compact('letterOfIndent','letterOfIndentItems'));
        }else if($request->type == 'MILELE_CAR'){
            if($request->download == 1) {
                $height = $request->height;
                $width = $request->width;

                $pdfFile = Pdf::loadView('letter_of_indents.LOI-templates.milele_car_loi_download_view',
                    compact('letterOfIndent','letterOfIndentItems','height','width'));

                $filename = 'LOI_'.$letterOfIndent->id.date('Y_m_d').'.pdf';
                $directory = public_path('LOI');
                \Illuminate\Support\Facades\File::makeDirectory($directory, $mode = 0777, true, true);
                $pdfFile->save($directory . '/' . $filename);
                $pdf = $this->pdfMerge($letterOfIndent->id);
                return $pdf->Output('LOI_'.date('Y_m_d').'.pdf','D');

            }
            return view('letter_of_indents.LOI-templates.milele_car_loi_template', compact('letterOfIndent','letterOfIndentItems'));
        } else if($request->type == 'BUSINESS'){
            if($request->download == 1) {
                $height = $request->height;
                $width = $request->width;

                $pdfFile = Pdf::loadView('letter_of_indents.LOI-templates.business_download_view',
                    compact('letterOfIndent','letterOfIndentItems','height','width'));
                $filename = 'LOI_'.$letterOfIndent->id.date('Y_m_d').'.pdf';
                $directory = public_path('LOI');
                \Illuminate\Support\Facades\File::makeDirectory($directory, $mode = 0777, true, true);
                $pdfFile->save($directory . '/' . $filename);
                $pdf = $this->pdfMerge($letterOfIndent->id);
                return $pdf->Output('LOI_'.date('Y_m_d').'.pdf','D');

            }
            return view('letter_of_indents.LOI-templates.business_template', compact('letterOfIndent','letterOfIndentItems'));
        }
        else {
            if($request->download == 1) {
                $height = $request->height;
                $width = $request->width;

                $pdfFile = PDF::loadView('letter_of_indents.LOI-templates.individual_download_view',
                    compact('letterOfIndent','letterOfIndentItems','height','width'));

                $filename = 'LOI_'.$letterOfIndent->id.date('Y_m_d').'.pdf';
                $directory = public_path('LOI');
                \Illuminate\Support\Facades\File::makeDirectory($directory, $mode = 0777, true, true);
                $pdfFile->save($directory . '/' . $filename);
                $pdf = $this->pdfMerge($letterOfIndent->id);
                return $pdf->Output('LOI_'.date('Y_m_d').'.pdf','D');

            }
            return view('letter_of_indents.LOI-templates.individual_template', compact('letterOfIndent','letterOfIndentItems'));
        }

        return redirect()->back()->withErrors("error", "Something went wrong!Please try again");

    }
    public function pdfMerge($letterOfIndentId)
    {
        $letterOfIndent = LetterOfIndent::find($letterOfIndentId);
        $filename = 'LOI_'.$letterOfIndentId.date('Y_m_d').'.pdf';

        $pdf = new \setasign\Fpdi\Tcpdf\Fpdi();

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $files[] = 'LOI/'.$filename;

        foreach($letterOfIndent->LOIDocuments as $letterOfIndentDocument) {
            $files[] = 'LOI-Documents/'.$letterOfIndentDocument->loi_document_file;
        }
        foreach ($files as $file) {
            $pageCount = $pdf->setSourceFile($file);
            for ($i=0; $i < $pageCount; $i++)
            {
                $pdf->AddPage();
                $tplIdx = $pdf->importPage($i+1);
                $pdf->useTemplate($tplIdx,0,0);
            }
        }
        return $pdf;
    }
    public function approve(Request $request)
    {
        info($request->all());
        $letterOfIndent = LetterOfIndent::find($request->id);
        $letterOfIndent->status = $request->status;
        info($request->review);
        if($request->status = LetterOfIndent::LOI_STATUS_REJECTED) {
            $letterOfIndent->review = $request->review;
        }
        $letterOfIndent->save();
        return response($letterOfIndent, 200);
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
        $letterOfIndent = LetterOfIndent::find($id);
        $countries = Country::all();
        $customers = Customer::all();
        if($letterOfIndent->dealers == 'Trans Cars') {
            $models = MasterModel::whereNotNull('transcar_loi_description');
        }else{
            $models = MasterModel::whereNotNull('milele_loi_description');
        }

        $models = $models->groupBy('model')->orderBy('id','ASC')->get();
        $letterOfIndentItems = LetterOfIndentItem::where('letter_of_indent_id', $id)->get();
        foreach ($letterOfIndentItems as $letterOfIndentItem) {
            $letterOfIndentItem->sfxLists = MasterModel::where('model', $letterOfIndentItem->masterModel->model)->groupBy('sfx')->pluck('sfx');
            $letterOfIndentItem->modelYearLists = MasterModel::where('model', $letterOfIndentItem->masterModel->model)
                                        ->where('sfx', $letterOfIndentItem->masterModel->sfx)->pluck('model_year');
            if($letterOfIndent->dealers == 'Milele Motors') {
                $letterOfIndentItem->loi_description = $letterOfIndentItem->masterModel->milele_loi_description;

            }else{
                $letterOfIndentItem->loi_description = $letterOfIndentItem->masterModel->transcar_loi_description;
            }
        }

        return view('letter_of_indents.edit', compact('countries','customers','letterOfIndent','models',
        'letterOfIndentItems'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
//        dd($request->all());
        $request->validate([
            'customer_id' => 'required',
            'category' => 'required',
            'date' => 'required',
            'dealers' => 'required',
        ]);

        $LOI = LetterOfIndent::where('customer_id', $request->customer_id)
            ->whereDate('date', Carbon::createFromFormat('Y-m-d', $request->date))
            ->where('category', $request->category)
            ->whereNot('id',$id)
            ->where('status', LetterOfIndent::LOI_STATUS_NEW)
            ->first();

        if (!$LOI) {
            DB::beginTransaction();

            $LOI = LetterOfIndent::find($id);

            $LOI->customer_id = $request->customer_id;
            $LOI->date = Carbon::createFromFormat('Y-m-d', $request->date);
            $LOI->category = $request->category;
            $LOI->dealers = $request->dealers;
            $LOI->destination = $request->destination;
            $LOI->so_number = $request->so_number;
            $LOI->prefered_location = $request->prefered_location;

            if ($request->has('loi_signature'))
            {
                $file = $request->file('loi_signature');
                $extension = $file->getClientOriginalExtension();
                $fileName = time().'.'.$extension;
                $destinationPath = 'LOI-Signature';
                $file->move($destinationPath, $fileName);

                if(!\Illuminate\Support\Facades\File::isDirectory($destinationPath)) {
                    \Illuminate\Support\Facades\File::makeDirectory($destinationPath, $mode = 0777, true, true);
                }
                $filePath = public_path('LOI-Signature/' . $file);
                $LOI->signature = $fileName;

            }

            $LOI->save();

            if ($request->has('files')) {
                foreach ($request->file('files') as $key => $file) {
                    $extension = $file->getClientOriginalExtension();
                    $fileName = $key . time() . '.' . $extension;
                    $destinationPath = 'LOI-Documents';
                    $file->move($destinationPath, $fileName);
                    $LoiDocument = new LetterOfIndentDocument();

                    $LoiDocument->loi_document_file = $fileName;
                    $LoiDocument->letter_of_indent_id = $LOI->id;
                    $LoiDocument->save();
                }
            }
            $LOI->letterOfIndentItems()->delete();
            if($request->deletedIds) {
                LetterOfIndentDocument::whereIn('id', $request->deletedIds)->delete();
            }

            $quantities = $request->quantity;
            foreach ($quantities as $key => $quantity) {
                $masterModel = MasterModel::where('sfx', $request->sfx[$key])
                    ->where('model', $request->models[$key])
                    ->where('model_year', $request->model_year[$key])
                    ->first();
                if ($masterModel) {
                    $LOIItem = new LetterOfIndentItem();
                    $LOIItem->letter_of_indent_id = $LOI->id;
                    $LOIItem->master_model_id = $masterModel->id ?? '';
                    $LOIItem->quantity = $quantity;
                    $LOIItem->save();
                }
            }
            DB::commit();

            return redirect()->route('letter-of-indents.index')->with('success',"LOI Updated successfully");

        }else{
            return redirect()->back()->with('error', "LOI with this customer and date and category is already exist.");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        LetterOfIndentDocument::where('letter_of_indent_id', $id)->delete();
        LetterOfIndentItem::where('letter_of_indent_id', $id)->delete();
        LetterOfIndent::find($id)->delete();

        DB::commit();

        return response(true);

    }
    public function inventoryStore(Request $request)
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
//                    info("inside while loop");
                    $supplier_id = $request->input('supplier_id');
                    $country = $request->input('country');
//                    info($filedata[6]);
                    $colourcode = $filedata[5];
                    if($colourcode) {
//                        info("test ok");
//                        info($colourcode);
                        $colourcodecount = strlen($colourcode);
//                        info($colourcodecount);

                        if ($colourcodecount == 5) {
//                            info("colr code count is 5");
                            $extColour = substr($colourcode, 0, 3);
                            $intColour = substr($colourcode,  -2);
//                            info($intColour);
//                            info($extColour);
                        }
                        if ($colourcodecount == 4) {
//                            info("colr code count is 4");

                            $altercolourcode = "0" . $colourcode;
                            $extColour = substr($altercolourcode, 0, 3);
                            $intColour = substr($altercolourcode, -2);
//                            info($extColour);
//                            info("interior colour");
//                            info($intColour);
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

                        $colourname = $exteriorColor."-".$interiorColor;
                    }

                    $uploadFileContents[$i]['steering'] = $filedata[0];
                    $uploadFileContents[$i]['model'] = $filedata[1];
                    $uploadFileContents[$i]['sfx'] = $filedata[2];
                    $uploadFileContents[$i]['chasis'] = !empty($filedata[3]) ? $filedata[3] : NULL;
                    $uploadFileContents[$i]['engine_number'] = $filedata[4];
                    $uploadFileContents[$i]['color_code'] = $filedata[5];
//                    $uploadFileContents[$i]['color_name'] = $colourname;
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
//                        info("pod existing");
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

//                        $modelIds = MasterModel::where('model', $uploadFileContent['model'])
//                            ->where('sfx', $uploadFileContent['sfx'])
//                            ->where('steering', $uploadFileContent['steering'])
//                            ->pluck('id')->toArray();
                        // after having DN data their is no changes for data of thata ro.so consider the data without eta import for inventory
                        $supplierInventories = SupplierInventory::where('master_model_id', $modelId)
//                            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                            ->where('supplier_id', $request->supplier_id)
                            ->where('whole_sales', $request->whole_sales)
                            ->whereNull('delivery_note');

                        if ($supplierInventories->count() <= 0)
                        {
                            // model and sfx not existing in Suplr Invtry => new row
                            $newlyAddedRows[$i]['model'] = $uploadFileContent['model'];
                            $newlyAddedRows[$i]['sfx'] = $uploadFileContent['sfx'];
                            $newlyAddedRows[$i]['chasis'] = $uploadFileContent['chasis'];
                            $newlyAddedRows[$i]['engine_number'] = $uploadFileContent['engine_number'];
                            $newlyAddedRows[$i]['color_code'] = $uploadFileContent['color_code'];
                        } else {
//                            info("chaisis");
//                            info($uploadFileContent['chasis']);
                            if (!empty($uploadFileContent['chasis']))
                            {
                                // Store the Count into Update the Row with data
                                $supplierInventory = $supplierInventories->where('chasis', $uploadFileContent['chasis'])
                                    ->first();
                                info("chaisis avaialable");
//
                                info($supplierInventory);
                                $isNullChaisis = SupplierInventory::where('master_model_id', $modelId)
//                                    ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                                    ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                                    ->where('supplier_id', $request->supplier_id)
                                    ->where('whole_sales', $request->whole_sales)
                                    ->whereNull('delivery_note')
                                    ->whereNull('chasis');

                                if (empty($supplierInventory)) {
//                                    info("chaiss not existing");
                                    //adding new row simply
                                    $isNullChaisisExist = $isNullChaisis->first();
                                    if (!empty($isNullChaisisExist)) {
                                        // null chaisis existing => updating row
                                        $chasisUpdatedRow = $isNullChaisis->whereNotIn('id', $chasisUpdatedRowIds)->first();
//
                                        if($chasisUpdatedRow) {
                                            $chasisUpdatedRowIds[] = $chasisUpdatedRow->id;
                                        }
                                        $isNullChaisis = $isNullChaisis->first();

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
                                }
                                else
                                {
                                    info("inventory with chasis existing.");
                                    // inventory with chasis existing...

                                    $isExistInventoryRow = $supplierInventories->where('engine_number', $uploadFileContent['engine_number'])
                                        ->where('color_code', $uploadFileContent['color_code'])
                                        ->where('pord_month', $uploadFileContent['pord_month'])
                                        ->where('po_arm', $uploadFileContent['po_arm'])
                                        ->where('delivery_note', $uploadFileContent['delivery_note'])
                                        ->where('eta_import', $uploadFileContent['eta_import'])
                                        ->first();
                                    if (!$isExistInventoryRow)
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
                                $nullChaisisCount = SupplierInventory::where('master_model_id', $modelId)
                                    ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                                    ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                                    ->where('supplier_id', $request->supplier_id)
                                    ->where('whole_sales', $request->whole_sales)
                                    ->whereNotIn('id', $chasisUpdatedRowIds)
                                    ->whereNull('chasis')
                                    //->whereNull('delivery_note')
                                    ->count();
                                $modelSfxValuePair = $uploadFileContent['model']."_".$uploadFileContent['sfx'];
                                $countblankchasis[] = $modelSfxValuePair;
                                $groupedCountValue =  array_count_values($countblankchasis);
                                if ($groupedCountValue[$modelSfxValuePair] > $nullChaisisCount)
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
                            ->where('supplier_id', $request->supplier_id)
                            ->where('whole_sales', $request->whole_sales)
                            ->where('master_model_id', $model->id)
                            ->where('chasis', $uploadFileContent['chasis'])
                            ->where('engine_number', $uploadFileContent['engine_number'])
                            ->where('color_code', $uploadFileContent['color_code'])
                            ->where('pord_month', $uploadFileContent['pord_month'])
                            ->where('po_arm', $uploadFileContent['po_arm'])
                            // ->whereNull('delivery_note')
                            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE);
                        if ($isExistSupplier->count() > 0)
                        {
                            if ($isExistSupplier->count() > 1)
                            {
                                $dbRowCount = $isExistSupplier->count();
                                $csvValuePair = $uploadFileContent['model']."_".$uploadFileContent['sfx']."_".$uploadFileContent['chasis']."_".
                                    $uploadFileContent['engine_number']."_".$uploadFileContent['color_code']."_".$uploadFileContent['pord_month']."_".
                                    $uploadFileContent['po_arm'];
                                if ($groupedExcelCountValue[$csvValuePair] <= $dbRowCount)
                                {
                                    $ExcelExistingRowId = $isExistSupplier->orderBy('id','desc')->take($groupedExcelCountValue[$csvValuePair])->pluck('id');
                                    foreach ($ExcelExistingRowId as $ExcelExistingRowId) {
                                        $excelRows[] = $ExcelExistingRowId;
                                    }
                                }else{
                                    $ExcelExistingRowId = $isExistSupplier->take($dbRowCount)->pluck('id');
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
                        ->where('supplier_id', $request->supplier_id)
                        ->where('whole_sales', $request->whole_sales)
                        ->whereNotIn('id', $excelRows)
                        // ->whereNull('delivery_note')
                        ->get();
                    foreach ($deletedRows as $deletedRow) {
                        $deletedRow->status = SupplierInventory::VEH_STATUS_DELETED;
//                            $deletedRow->save();
                    }

                    $preivousDatas = SupplierInventory::where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                        ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                        ->where('supplier_id', $request->supplier_id)
                        ->where('whole_sales', $request->whole_sales)
                        // ->whereNull('delivery_note')
                        ->get();
                    if ($preivousDatas->count() > 0) {
                        foreach ($preivousDatas as $preivousData)
                        {
                            $preivousData->upload_status = SupplierInventory::UPLOAD_STATUS_INACTIVE;
//                            $preivousData->save();
                        }
                    }
//                    foreach ($uploadFileContents as $uploadFileContent)
//                    {
//                        $model = MasterModel::where('model', $uploadFileContent['model'])
//                            ->where('sfx', $uploadFileContent['sfx'])
//                            ->where('steering', $uploadFileContent['steering'])
//                            ->where('model_year', $uploadFileContent['model_year'])
//                            ->first();
//
//                        $supplierInventory = new SupplierInventory();
//                        $supplierInventory->master_model_id = $model->id;
//                        $supplierInventory->chasis          = $uploadFileContent['chasis'];
//                        $supplierInventory->engine_number   = $uploadFileContent['engine_number'];
//                        $supplierInventory->color_code      = $uploadFileContent['color_code'];
//                        $supplierInventory->pord_month      = $uploadFileContent['pord_month'];
//                        $supplierInventory->po_arm          = $uploadFileContent['po_arm'];
//                        $supplierInventory->eta_import      = $uploadFileContent['eta_import'];
//                        $supplierInventory->is_add_new     	= !empty($request->is_add_new) ? true : false;
//                        $supplierInventory->supplier_id       = $uploadFileContent['supplier_id'];
//                        $supplierInventory->whole_sales	    = $uploadFileContent['whole_sales'];
//                        $supplierInventory->country     	= $uploadFileContent['country'];
//                        $supplierInventory->delivery_note   = $uploadFileContent['delivery_note'];
//                        $supplierInventory->date_of_entry   = $date;
//                        $supplierInventory->upload_status   = SupplierInventory::UPLOAD_STATUS_ACTIVE;
//                        $supplierInventory->veh_status      = SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY;
//                        $supplierInventory->interior_color_code_id = $uploadFileContent['interior_color_code_id'];
//                        $supplierInventory->exterior_color_code_id = $uploadFileContent['exterior_color_code_id'];
//                        $supplierInventory->save();
//                    }

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
//                        $supplierInventory->delivery_note   = $uploadFileContent['delivery_note'];
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

}
