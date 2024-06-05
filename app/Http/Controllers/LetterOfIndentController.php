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
use App\Models\LoiCountryCriteria;
use App\Models\LoiRestrictedCountry;
use App\Models\LoiTemplate;
use App\Models\MasterModel;
use App\Models\ModelYearCalculationCategory;
use App\Models\Supplier;
use App\Models\LoiSoNumber;
use App\Models\SupplierInventory;
use App\Models\User;
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
        $approvalWaitingLOIs = LetterOfIndent::with('letterOfIndentItems','LOIDocuments')
            ->orderBy('id','DESC')
            ->where('status', LetterOfIndent::LOI_STATUS_WAITING_FOR_APPROVAL)
            ->cursor();
        $partialApprovedLOIs =  LetterOfIndent::with('letterOfIndentItems','LOIDocuments')
            ->orderBy('id','DESC')
            ->whereIn('status', [LetterOfIndent::LOI_STATUS_PARTIAL_APPROVED,LetterOfIndent::LOI_STATUS_PARTIAL_PFI_CREATED,LetterOfIndent::LOI_STATUS_APPROVED])
            ->get();
        foreach ($partialApprovedLOIs as $partialApprovedLOI) {
            $partialApprovedLOI->utilized_quantity = LetterOfIndentItem::where('letter_of_indent_id', $partialApprovedLOI->id)
                ->sum('utilized_quantity');
            $partialApprovedLOI->total_quantity = LetterOfIndentItem::where('letter_of_indent_id', $partialApprovedLOI->id)
                ->sum('quantity');
        }
        $supplierApprovedLOIs =  LetterOfIndent::with('letterOfIndentItems','LOIDocuments')
            ->orderBy('id','DESC')
            ->where('submission_status', LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED)
            ->cursor();
        $rejectedLOIs =  LetterOfIndent::with('letterOfIndentItems','LOIDocuments')
            ->orderBy('id','DESC')
            ->where('status', LetterOfIndent::LOI_STATUS_SUPPLIER_REJECTED)
            ->cursor();

        return view('letter_of_indents.index', compact('newLOIs','approvalWaitingLOIs',
            'partialApprovedLOIs','supplierApprovedLOIs','rejectedLOIs'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $LOICountries = LoiCountryCriteria::where('status', LoiCountryCriteria::STATUS_ACTIVE)->where('is_loi_restricted', false)->pluck('country_id');
        $countries = Country::whereIn('id', $LOICountries)->get();
        $customers = Customer::all();
        $models = MasterModel::whereNotNull('transcar_loi_description')->groupBy('model')->orderBy('id','ASC')->get();
        $salesPersons = User::where('status','active')->where('sales_rap', 'Yes')->get();

        return view('letter_of_indents.create',compact('countries','customers','models','salesPersons'));
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
            $LOI->submission_status = LetterOfIndent::LOI_SUBMISION_STATUS_NEW;
            $LOI->status = LetterOfIndent::LOI_STATUS_NEW;
            $LOI->created_by = Auth::id();
            $LOI->sales_person_id = $request->sales_person_id;
            $customer = Customer::find($request->customer_id);
            $country = Country::find($request->country);
            $countryName = strtoupper(substr($country->name, 0, 3));

            $names = explode(" ", $customer->name);
            $customerNameCode = "";
            foreach ($names as $name) {
               $customerNameCode .= strtoupper(mb_substr($name, 0, 1));
            }
            $customerCode = str_pad($customerNameCode, 3, '0', STR_PAD_RIGHT);
            $yearCode = Carbon::now()->format('y');

            $customerTotalLoiCount = LetterOfIndent::where('customer_id', $request->customer_id)->count();
            $nextLoiCount = str_pad($customerTotalLoiCount + 1, 2, '0', STR_PAD_LEFT);

            $uuid = $countryName . $customerCode ."-".$yearCode . $nextLoiCount;
            $LOI->uuid = $uuid;

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
                    $latestRow = LetterOfIndentItem::withTrashed()->orderBy('id', 'desc')->first();
                    $length = 7;
                    $offset = 4;
                    $prefix = "LOI-";
                    if($latestRow){
                        $latestUUID =  $latestRow->uuid;
                        $latestUUIDNumber = substr($latestUUID, $offset, $length);

                        $newCode =  str_pad($latestUUIDNumber + 1, 3, 0, STR_PAD_LEFT);
                        $code =  $prefix.$newCode;
                    }else{
                        $code = $prefix.'001';
                    }

                    $LOIItem = new LetterOfIndentItem();
                    $LOIItem->letter_of_indent_id  = $LOI->id;
                    $LOIItem->master_model_id = $masterModel->id ?? '';
                    $LOIItem->uuid = $code;
                    $LOIItem->quantity = $quantity;
                    $LOIItem->save();
                }
            }

            if ($request->so_number) {

                $soNumbers = $request->so_number;
                foreach($soNumbers as $soNumber) {
                    if(!empty($soNumber)) {
                        info($soNumber);
                        $loiSoNumber = new LoiSoNumber();
                        $loiSoNumber->letter_of_indent_id = $LOI->id;
                        $loiSoNumber->so_number = $soNumber;
                        $loiSoNumber->save();
                    }

                }
            }
            if ($request->template_type) {
                foreach ($request->template_type as $template) {
                    $LOITemplate = new  LoiTemplate();
                    $LOITemplate->template_type = $template;
                    $LOITemplate->letter_of_indent_id = $LOI->id;
                    $LOITemplate->save();
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
                try{
                    $pdf = $this->pdfMerge($letterOfIndent->id);
                    return $pdf->Output('LOI_'.date('Y_m_d').'.pdf','D');
                }catch (\Exception $e){
                    return $e->getMessage();
                }
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
                try{
                    $pdf = $this->pdfMerge($letterOfIndent->id);
                    return $pdf->Output('LOI_'.date('Y_m_d').'.pdf','D');
                }catch (\Exception $e){
                    return $e->getMessage();
                }

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
                try{
                    $pdf = $this->pdfMerge($letterOfIndent->id);
                    return $pdf->Output('LOI_'.date('Y_m_d').'.pdf','D');
                }catch (\Exception $e){
                    return $e->getMessage();
                }

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
                try{
                    $pdf = $this->pdfMerge($letterOfIndent->id);
                    return $pdf->Output('LOI_'.date('Y_m_d').'.pdf','D');
                }catch (\Exception $e){
                    return $e->getMessage();
                }
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
        $LOICountries = LoiCountryCriteria::where('status', LoiCountryCriteria::STATUS_ACTIVE)->where('is_loi_restricted', false)->pluck('country_id');
        $countries = Country::whereIn('id', $LOICountries)->get();
        $customers = Customer::all();
        $possibleCustomers = Customer::where('country_id', $letterOfIndent->customer->country_id)->get();
        $salesPersons = User::where('status','active')->where('sales_rap', 'Yes')->get();

        if($letterOfIndent->dealers == 'Trans Cars') {
            $models = MasterModel::where('is_transcar', true);
        }else{
            $models = MasterModel::where('is_milele', true);
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
        $LOITemplates = LoiTemplate::where('letter_of_indent_id', $id)->pluck('template_type')->toArray();
        return view('letter_of_indents.edit', compact('countries','customers','letterOfIndent','models',
                                'letterOfIndentItems','salesPersons','possibleCustomers','LOITemplates'));
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
            'dealers' => 'required'
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

            $customer = Customer::find($request->customer_id);
            $country = Country::find($request->country);
            $countryName = strtoupper(substr($country->name, 0, 3));

            $names = explode(" ", $customer->name);
            $customerNameCode = "";
            foreach ($names as $name) {
                $customerNameCode .= strtoupper(mb_substr($name, 0, 1));
            }
            $customerCode = str_pad($customerNameCode, 3, '0', STR_PAD_RIGHT);
            $yearCode = Carbon::now()->format('y');

            $customerTotalLoiCount = LetterOfIndent::where('customer_id', $request->customer_id)->count();
            $nextLoiCount = str_pad($customerTotalLoiCount + 1, 2, '0', STR_PAD_LEFT);

            $uuid = $countryName . $customerCode ."-".$yearCode . $nextLoiCount;
            $LOI->uuid = $uuid;

            $LOI->customer_id = $request->customer_id;
            $LOI->date = Carbon::createFromFormat('Y-m-d', $request->date);
            $LOI->category = $request->category;
            $LOI->dealers = $request->dealers;
            $LOI->sales_person_id = $request->sales_person_id;
            if($request->is_signature_removed == 1) {
                $LOI->signature = NULL;
            }
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
            $LOI->LOITemplates()->delete();
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
                    $latestRow = LetterOfIndentItem::withTrashed()->orderBy('id', 'desc')->first();
                    $length = 7;
                    $offset = 4;
                    $prefix = "LOI-";
                    if($latestRow){
                        $latestUUID =  $latestRow->uuid;
                        $latestUUIDNumber = substr($latestUUID, $offset, $length);
                        $newCode =  str_pad($latestUUIDNumber + 1, 3, 0, STR_PAD_LEFT);
                        $code =  $prefix.$newCode;
                    }else{
                        $code = $prefix.'001';
                    }
                    $LOIItem = new LetterOfIndentItem();
                    $LOIItem->letter_of_indent_id = $LOI->id;
                    $LOIItem->master_model_id = $masterModel->id ?? '';
                    $LOIItem->quantity = $quantity;
                    $LOIItem->uuid = $code;
                    $LOIItem->save();
                }
            }

            if($request->so_number) {
                $LOI->soNumbers()->delete();
                $soNumbers = $request->so_number;
                foreach($soNumbers as $soNumber) {
                    if(!empty($soNumber)) {
                        $loiSoNumber = new LoiSoNumber();
                        $loiSoNumber->letter_of_indent_id = $LOI->id;
                        $loiSoNumber->so_number = $soNumber;
                        $loiSoNumber->save();
                    }
                }
            }
            if ($request->template_type) {
                foreach ($request->template_type as $template) {
                    $LOITemplate = new  LoiTemplate();
                    $LOITemplate->template_type = $template;
                    $LOITemplate->letter_of_indent_id = $LOI->id;
                    $LOITemplate->save();
                }
            }


            DB::commit();

            return redirect()->route('letter-of-indents.index')->with('success',"LOI Updated successfully");

        }else{
            return redirect()->back()->with('error', "LOI with this customer and date and category is already exist.");
        }
    }
    public function RequestSupplierApproval(Request $request)
    {
//       dd("test");
//        info($request->all());
      $LOI = LetterOfIndent::find($request->id);

      $LOI->submission_status = LetterOfIndent::LOI_STATUS_WAITING_FOR_APPROVAL;
      $LOI->status = LetterOfIndent::LOI_STATUS_WAITING_FOR_APPROVAL;
      $LOI->save();

      return response(true);

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

}
