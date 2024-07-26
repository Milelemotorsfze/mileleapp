<?php

namespace App\Http\Controllers;

use App\Models\ColorCode;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Clients;
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
use App\Models\LOIExpiryCondition;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;
use Monarobase\CountryList\CountryListFacade;
use setasign\Fpdi\Fpdi;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

use Illuminate\Support\Facades\Storage;

class LetterOfIndentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Builder $builder, Request $request)
    {
           

        (new UserActivityController)->createActivity('Open LOI Listing Page.');

        $tab = $request->tab;
        if($request->tab == 'NEW'){
            // LOI with status  new 
          $data =  DB::table('letter_of_indents as loi')
                ->select('users.name as createdBy','clients.name as cutomer_name','loi.id','uuid','category','dealers','loi_approval_date',
                'clients.customertype as customer_type','loi.deleted_at','loi.submission_status','loi.created_at','loi.updated_at','date','sales_person_id',
                'updated_by','countries.name as customer_country','is_expired','review')
                ->join('clients', 'loi.client_id', '=', 'clients.id')
                ->join('users', 'loi.created_by', '=', 'users.id')
                ->join('countries', 'clients.country_id', '=', 'countries.id')
                ->where('submission_status', LetterOfIndent::LOI_STATUS_NEW)
                ->orderBy('updated_at','DESC')
                ->whereNull('loi.deleted_at')
                ->get();
        }else if($request->tab == 'WAITING_FOR_APPROVAL'){
          $data = DB::table('letter_of_indents as loi')
            ->select('users.name as createdBy','clients.name as cutomer_name','loi.id','uuid','category','dealers','loi_approval_date',
            'clients.customertype as customer_type','loi.submission_status','loi.created_at','loi.deleted_at','loi.updated_at','date','sales_person_id',
            'updated_by','countries.name as customer_country','loi.deleted_at','is_expired','review')
            ->whereNull('loi.deleted_at')
            ->join('clients', 'loi.client_id', '=', 'clients.id')
            ->join('users', 'loi.created_by', '=', 'users.id')
            ->join('countries', 'clients.country_id', '=', 'countries.id')
            ->where('submission_status', LetterOfIndent::LOI_STATUS_WAITING_FOR_APPROVAL)
            ->orderBy('updated_at','DESC')
            ->get();

        }else if($request->tab == 'SUPPLIER_RESPONSE'){
            $data = DB::table('letter_of_indents as loi')
            ->select('users.name as createdBy','clients.name as cutomer_name','loi.id','uuid','category','dealers','sales_person_id',
            'clients.customertype as customer_type','loi.submission_status','loi.created_at','loi.updated_at','date','loi.deleted_at',
            'loi_approval_date','updated_by','countries.name as customer_country','utilized_quantity','review','is_expired')
            ->join('clients', 'loi.client_id', '=', 'clients.id')
            ->join('users', 'loi.created_by', '=', 'users.id')
            ->join('countries', 'clients.country_id', '=', 'countries.id')
            ->whereIn('submission_status',[LetterOfIndent::LOI_STATUS_SUPPLIER_REJECTED,LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED])
            ->orderBy('updated_at','DESC')
            ->whereNull('loi.deleted_at')
            ->get();
        }
            
        if (request()->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function($query) {
                    return Carbon::parse($query->created_at)->format('d M Y');
                })
                ->editColumn('loi_approval_date', function($query) {
                    return Carbon::parse($query->loi_approval_date)->format('d M Y') ?? '';
                })
                ->editColumn('updated_by', function($query) {
                    $updatedBy = User::select('id','name')
                    ->where('id',$query->updated_by)
                    ->first();
                    if($updatedBy){
                        return $updatedBy->name;
                    }
                    return '';
                })
                ->editColumn('sales_person', function($query) {
                    $salesPerson = User::select('id','name')
                    ->where('id',$query->sales_person_id)
                    ->first();
                    if($salesPerson){
                        return $salesPerson->name;
                    }
                    return '';
                 })
                ->editColumn('updated_at', function($query) {
                   return Carbon::parse($query->updated_at)->format('d M Y');
                })
                ->editColumn('client_id', function($query) {
                    return $query->client->name ?? '';
                 })
                 ->editColumn('date', function($query) {
                    return Carbon::parse($query->date)->format('d M Y');
                 })
                ->addColumn('so_number', function($query) {
                    $soNumbers = LoiSoNumber::where('letter_of_indent_id', $query->id)
                            ->pluck('so_number')->toArray();

                   return implode(",", $soNumbers);
                })
                ->addColumn('loi_templates', function($query) {
                    $templateTypes = LoiTemplate::where('letter_of_indent_id', $query->id)
                                    ->pluck('template_type')->toArray();
                    $letterOfIndent = LetterOfIndent::select('id')->find($query->id);
                    return view('letter_of_indents.actions.loi_template_links',compact('templateTypes','letterOfIndent'));
                })
                ->editColumn('is_expired', function($query) {
                    $LOI = LetterOfIndent::select('id','is_expired','client_id','date')->find($query->id);
                    $LOItype = $LOI->client->customertype;
                    $LOIExpiryCondition = LOIExpiryCondition::where('category_name', $LOItype)->first();
                    if($LOIExpiryCondition && $LOI->is_expired == false) {        
                        $currentDate = Carbon::now();
                        $year = $LOIExpiryCondition->expiry_duration_year;
                        $expiryDate = Carbon::parse($LOI->date)->addYears($year);
                        $test = $currentDate->gt($expiryDate);
                        // do not make status expired, becasue to know at which status stage it got expired
                        if($currentDate->gt($expiryDate) == true) {
                            $LOI->is_expired = true;              
                            $LOI->save();  
                        }else{
                            $LOI->is_expired = false;              
                            $LOI->save();  
                        }
                    }

                    if($LOI->is_expired == true) {
                        return  'Expired';
                    }else{
                        return 'Not Expired';
                    }
                                            
                 })
                ->addColumn('loi_quantity', function($query) {
                    $loiQuantity = LetterOfIndentItem::select('letter_of_indent_id','quantity')
                                ->where('letter_of_indent_id', $query->id)
                                ->sum('quantity');
                    return $loiQuantity;
                })
                ->addColumn('approval_button', function($query, Request $request) {
                    $type = $request->tab;

                    $letterOfIndent = LetterOfIndent::select('id','is_expired','client_id','category','date','submission_status')->find($query->id);
                    return view('letter_of_indents.actions.approval_actions',compact('letterOfIndent','type'));
                })
                ->addColumn('action', function($query,Request $request) {
                    $letterOfIndent = LetterOfIndent::select('id','is_expired','signature')->find($query->id);
                    $type = $request->tab;
                   
                    return view('letter_of_indents.actions.action',compact('letterOfIndent','type'));
                })
                ->rawColumns(['so_number','loi_templates','loi_quantity','action'])
                ->toJson();
        }

        return view('letter_of_indents.index');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        (new UserActivityController)->createActivity('Open LOI Create Page.');
        
        $LOICountries = LoiCountryCriteria::where('status', LoiCountryCriteria::STATUS_ACTIVE)->where('is_loi_restricted', false)->pluck('country_id');
        $countries = Country::whereIn('id', $LOICountries)->get();
        $customers = Clients::whereNotNull('country_id')->get();
        $models = MasterModel::where('is_milele', true)->groupBy('model')->orderBy('id','ASC')->get();
        $salesPersons = User::where('status','active')->get();

        return view('letter_of_indents.create',compact('countries','customers','models','salesPersons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required',
            'category' => 'required',
            'date' => 'required',
            'dealers' => 'required'
          
        ]);

        $LOI = LetterOfIndent::where('client_id', $request->client_id)
            ->whereDate('date', Carbon::createFromFormat('Y-m-d', $request->date))
            ->where('category', $request->category)
            ->where('status', LetterOfIndent::LOI_STATUS_NEW)
            ->first();

        if (!$LOI)
        {
            DB::beginTransaction();      
            try{

            $LOI = new LetterOfIndent();
            $LOI->client_id = $request->client_id;
            $LOI->date = Carbon::createFromFormat('Y-m-d', $request->date);
            $LOI->category = $request->category;
            $LOI->dealers = $request->dealers;
            $LOI->submission_status = LetterOfIndent::LOI_SUBMISION_STATUS_NEW;
            $LOI->status = LetterOfIndent::LOI_STATUS_NEW;
            $LOI->created_by = Auth::id();
            $LOI->sales_person_id = $request->sales_person_id;
           
            $customer = Clients::find($request->client_id);
            $country = Country::find($request->country);
            $countryName = strtoupper(substr($country->name, 0, 3));

            $customer->is_demand_planning_customer = true;
            $customer->save();

            $names = explode(" ", $customer->name);
            $customerNameCode = "";
            foreach ($names as $name) {
               $customerNameCode .= strtoupper(mb_substr($name, 0, 1));
            }
            $customerCode = str_pad($customerNameCode, 3, '0', STR_PAD_RIGHT);
            $yearCode = Carbon::now()->format('y');
            $year = Carbon::now()->format('Y');

            $customerTotalLoiCount = LetterOfIndent::where('client_id', $request->client_id)
                                ->whereYear('date', $year)->count();

            $nextLoiCount = str_pad($customerTotalLoiCount + 1, 2, '0', STR_PAD_LEFT);
            $uuid = $countryName . $customerCode ."-".$yearCode . $nextLoiCount;
            $customerYearCode = $yearCode.''.$nextLoiCount;

            $LOI->uuid = $uuid;
            $LOI->year_code = $customerYearCode;

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
                    ->orderBy('model_year','DESC')
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
        }catch (\Exception $e){
            return $e->getMessage();
        }

            DB::commit();

            (new UserActivityController)->createActivity('Created New LOI.');
        
            return redirect()->route('letter-of-indents.generate-loi',['id' => $LOI->id,'type' => $request->template_type[0] ]);

        }else{

            return redirect()->back()->with('error', "LOI with this customer and date and category is already exist.");
        }
    }
    public function getCustomers(Request $request)
    {
        $customers = Clients::where('country_id', $request->country)
            ->where('customertype', $request->customer_type)
            ->get();

        return $customers;
    }
    public function generateLOI(Request $request)
    {
        (new UserActivityController)->createActivity('Generated LOI Document.');
        
        $letterOfIndent = LetterOfIndent::select('id','date','signature','client_id','year_code')->where('id',$request->id)->first();
        $fileName = $letterOfIndent->client->name .'-'.$letterOfIndent->year_code.'.pdf';
        $letterOfIndentItems = LetterOfIndentItem::where('letter_of_indent_id', $request->id)->orderBy('id','DESC')->get();
      
        if ($request->type == 'trans_cars') {
            $width = $request->width;

            if($request->download == 1) {
                try{ 
                $pdfFile = PDF::loadView('letter_of_indents.LOI-templates.trans_car_loi_download_view',
                    compact('letterOfIndent','letterOfIndentItems','width'));
                }catch (\Exception $e){
                    return $e->getMessage();
                }
                 return $pdfFile->download($fileName);
               
            }
            return view('letter_of_indents.LOI-templates.trans_car_loi_template', compact('letterOfIndent','letterOfIndentItems'));
        }else if($request->type == 'milele_cars'){
            if($request->download == 1) {
                $width = $request->width;
                try{
                $pdfFile = PDF::loadView('letter_of_indents.LOI-templates.milele_car_loi_download_view',
                    compact('letterOfIndent','letterOfIndentItems','width'));
                }catch (\Exception $e){
                    return $e->getMessage();
                }
               
                 return $pdfFile->download($fileName);
            }
            return view('letter_of_indents.LOI-templates.milele_car_loi_template', compact('letterOfIndent','letterOfIndentItems'));
        } else if($request->type == 'business'){
            if($request->download == 1) {
                $width = $request->width;
                try{
                $pdfFile = PDF::loadView('letter_of_indents.LOI-templates.business_download_view',
                    compact('letterOfIndent','letterOfIndentItems','width'));
                }catch (\Exception $e){
                    return $e->getMessage();
                }
                return $pdfFile->download($fileName);
               
            }
            return view('letter_of_indents.LOI-templates.business_template', compact('letterOfIndent','letterOfIndentItems'));
        }
        else {
            if($request->download == 1) {
                $width = $request->width;
                try{
                $pdfFile = PDF::loadView('letter_of_indents.LOI-templates.individual_download_view',
                    compact('letterOfIndent','letterOfIndentItems','width'));
                }catch (\Exception $e){
                    return $e->getMessage();
                }
               
                return $pdfFile->download($fileName);
                
            }
            return view('letter_of_indents.LOI-templates.individual_template', compact('letterOfIndent','letterOfIndentItems'));
        }

        return redirect()->back()->withErrors("error", "Something went wrong!Please try again");

    }


  
  
  
    // public function pdfMerge($letterOfIndentId)
    // {
    //     $letterOfIndent = LetterOfIndent::find($letterOfIndentId);
    //     $filename = 'LOI_'.$letterOfIndentId.date('Y_m_d').'.pdf';
    //     $pdf = new \setasign\Fpdi\Tcpdf\Fpdi();
    //     // $pdf = new  \setasign\Fpdi\Tfpdf();

    //     $pdf->setPrintHeader(false);
    //     $pdf->setPrintFooter(false);
    //     $files[] = 'LOI/'.$filename;

    //     foreach($letterOfIndent->LOIDocuments as $letterOfIndentDocument) {
    //         $path = pathinfo(storage_path('LOI-Documents/'.$letterOfIndentDocument->loi_document_file));
    //         $extension = $path['extension'];
    //         if ($extension == 'pdf') {
    //             $files[] = 'LOI-Documents/'.$letterOfIndentDocument->loi_document_file;
    //         }
    //     }
    //     foreach ($files as $file) {
        
    //         $pageCount = $pdf->setSourceFile($file);
    //         for ($i=0; $i < $pageCount; $i++)
    //         {
    //             $pdf->AddPage();
    //             $tplIdx = $pdf->importPage($i+1);
    //             $pdf->useTemplate($tplIdx,0,0);
    //         }
    //     }
    //     return $pdf;
    // }
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
        (new UserActivityController)->createActivity('Open LOI Edit Page.');
       
        $letterOfIndent = LetterOfIndent::find($id);
        $LOICountries = LoiCountryCriteria::where('status', LoiCountryCriteria::STATUS_ACTIVE)->where('is_loi_restricted', false)->pluck('country_id');
        $countries = Country::whereIn('id', $LOICountries)->get();
        $customers = Clients::whereNotNull('country_id')->get();
        $possibleCustomers = Clients::where('country_id', $letterOfIndent->client->country_id)->get();
        $salesPersons = User::where('status','active')->get();

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
        (new UserActivityController)->createActivity('Updated LOI Details.');
      
        $request->validate([
            'client_id' => 'required',
            'category' => 'required',
            'date' => 'required',
            'dealers' => 'required'
        ]);

        $LOI = LetterOfIndent::where('client_id', $request->client_id)
            ->whereDate('date', Carbon::createFromFormat('Y-m-d', $request->date))
            ->where('category', $request->category)
            ->whereNot('id',$id)
            ->where('status', LetterOfIndent::LOI_STATUS_NEW)
            ->first();

        if (!$LOI) {
            DB::beginTransaction();
            try{

            $LOI = LetterOfIndent::find($id);

            $customer = Clients::find($request->client_id);
            $country = Country::find($request->country);
            $countryName = strtoupper(substr($country->name, 0, 3));

            $names = explode(" ", $customer->name);
            $customerNameCode = "";
            foreach ($names as $name) {
                $customerNameCode .= strtoupper(mb_substr($name, 0, 1));
            }
            $customerCode = str_pad($customerNameCode, 3, '0', STR_PAD_RIGHT);
            $yearCode = Carbon::now()->format('y');

            $customerTotalLoiCount = LetterOfIndent::where('client_id', $request->client_id)->count();
            $nextLoiCount = str_pad($customerTotalLoiCount + 1, 2, '0', STR_PAD_LEFT);

            $uuid = $countryName . $customerCode ."-".$yearCode . $nextLoiCount;
            $LOI->uuid = $uuid;

            $LOI->client_id = $request->client_id;
            $LOI->date = Carbon::createFromFormat('Y-m-d', $request->date);
            $LOI->category = $request->category;
            $LOI->dealers = $request->dealers;
            $LOI->sales_person_id = $request->sales_person_id;
            $LOI->updated_by = Auth::id();
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
                // delet ethe corresponding file also
            }

            $quantities = $request->quantity;
            foreach ($quantities as $key => $quantity) {
                $masterModel = MasterModel::where('sfx', $request->sfx[$key])
                    ->where('model', $request->models[$key])
                    ->orderBy('model_year','DESC')
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
                $LOI->LOITemplates()->delete();
                foreach ($request->template_type as $template) {
                    $LOITemplate = new  LoiTemplate();
                    $LOITemplate->template_type = $template;
                    $LOITemplate->letter_of_indent_id = $LOI->id;
                    $LOITemplate->save();
                }
            }

        }catch (\Exception $e){
            return $e->getMessage();
        }
            DB::commit();

            return redirect()->route('letter-of-indents.generate-loi',['id' => $LOI->id,'type' => $request->template_type[0]]);

        }else{
            return redirect()->back()->with('error', "LOI with this customer and date and category is already exist.");
        }
    }
    public function RequestSupplierApproval(Request $request)
    {
     (new UserActivityController)->createActivity('Requested for Supplier Approval of LOI.');
      
      $LOI = LetterOfIndent::find($request->id);

      $LOI->submission_status = LetterOfIndent::LOI_STATUS_WAITING_FOR_APPROVAL;
      $LOI->status = LetterOfIndent::LOI_STATUS_WAITING_FOR_APPROVAL;
      $LOI->updated_by = Auth::id();
      $LOI->save();

      return response(true);

    }
    public function supplierApproval(Request $request) {

        (new UserActivityController)->createActivity('Supplier Approved successfully.');
    
        $LOI = LetterOfIndent::find($request->id);
        if($request->status == 'REJECTED') {
            $LOI->status = LetterOfIndent::LOI_STATUS_SUPPLIER_REJECTED;
            $LOI->submission_status = LetterOfIndent::LOI_STATUS_SUPPLIER_REJECTED;
            $msg = 'Rejected';

        }elseif ($request->status == 'APPROVE') {
            $LOI->status = LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED;
            $LOI->submission_status = LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED;
            $msg = 'Approved';

        }
        $LOI->review = $request->review;
        $LOI->loi_approval_date = $request->loi_approval_date;
        $LOI->updated_by = Auth::id();
        $LOI->save();
        info($request->all());
        return redirect()->back()->with('success', 'Supplier'. $msg .' Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        (new UserActivityController)->createActivity('LOI Deleted successfully.');
    
        DB::beginTransaction();
        $LOI = LetterOfIndent::find($id);
        LoiTemplate::select('letter_of_indent_id')->where('letter_of_indent_id', $id)->delete();
        LoiSoNumber::select('letter_of_indent_id')->where('letter_of_indent_id', $id)->delete();
        LetterOfIndentDocument::select('letter_of_indent_id')->where('letter_of_indent_id', $id)->delete();
        LetterOfIndentItem::select('letter_of_indent_id')->where('letter_of_indent_id', $id)->delete();
        LetterOfIndent::find($id)->delete();
        $LOI->deleted_by  = Auth::id();
        $LOI->save();
    
        DB::commit();

        return response(true);

    }
    public function utilizationQuantityUpdate(Request $request,$id) {
        (new UserActivityController)->createActivity('LOI Utilization quantity updated.');
        
        $LOI = LetterOfIndent::find($id);
         DB::beginTransaction();
            if($request->letter_of_indent_item_ids) {
                foreach($request->letter_of_indent_item_ids as $key => $LOIItemId){
                    info($LOIItemId);
                    $LOIItem = LetterOfIndentItem::find($LOIItemId);
                    if($LOIItem->utilized_quantity !== $request->utilized_quantity[$key]) {
                        $LOIItem->utilized_quantity = $request->utilized_quantity[$key];
                        $LOIItem->save();
                    }
                }
            }
            $LoiUtilizationQuantity = LetterOfIndentItem::where('letter_of_indent_id', $id)->sum('utilized_quantity');
            $LOI->utilized_quantity = $LoiUtilizationQuantity;
            $LOI->updated_by = Auth::id(); 
            $LOI->save();
        DB::commit();

        // return response(true,200);
        return redirect()->back()->with('success', 'Utilization quantity updated Successfully.');
    }

}
