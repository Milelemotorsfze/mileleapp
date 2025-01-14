<?php

namespace App\Http\Controllers;

use App\Models\ColorCode;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Clients;
use App\Models\ClientDocument;
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
use App\Models\PfiItem;
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
        $data = LetterOfIndent::orderBy('updated_at','DESC')->with([
                'client' => function ($query) {
                    $query->select('id', 'name','customertype','country_id');
                },
                'country'  => function ($query) {
                    $query->select('id', 'name');
                },
                'createdBy' => function ($query) {
                    $query->select('id', 'name');
                },
                'updatedBy' => function($query){
                    $query->select('id', 'name');
                },
                'salesPerson' => function($query){
                    $query->select('id', 'name');
                },
                'soNumbers' => function($query){
                    $query->select('so_number');
                }]);
            if($request->tab == 'NEW'){
                    $data = $data->where('submission_status', LetterOfIndent::LOI_STATUS_NEW);           
            }else if($request->tab == 'WAITING_FOR_APPROVAL'){
                    $data = $data->where('submission_status', LetterOfIndent::LOI_STATUS_WAITING_FOR_APPROVAL);

            }else if($request->tab == 'WAITING_FOR_TTC_APPROVAL'){
                $data = $data->where('submission_status', LetterOfIndent::LOI_STATUS_WAITING_FOR_TTC_APPROVAL);

            }else if($request->tab == 'SUPPLIER_RESPONSE'){
                    $data = $data->whereIn('submission_status',[LetterOfIndent::LOI_STATUS_SUPPLIER_REJECTED,
                    LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED,LetterOfIndent::LOI_STATUS_TTC_APPROVED,
                    LetterOfIndent::LOI_STATUS_TTC_REJECTED]);
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
                ->addColumn('updated_by', function($query) {                  
                    if($query->updated_by){
                        return $query->updatedBy->name ?? '';
                    }
                })
                ->addColumn('created_by', function($query) {
                    if($query->created_by){
                        return $query->createdBy->name ?? '';
                    }
                })
                ->addColumn('sales_person_id', function($query) {                    
                    if($query->sales_person_id){
                        return $query->salesPerson->name ?? '';
                    }
                 })
                ->editColumn('updated_at', function($query) {
                   return Carbon::parse($query->updated_at)->format('d M Y');
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

                    if($query->is_expired == true) {
                        $msg = 'Expired';
                        return  '<button class="btn btn-sm btn-secondary">'.$msg.'</button>';
                    }else{
                        
                        $msg = '<button class="btn btn-sm btn-info loi-expiry-status-update" data-url="' . route('letter-of-indents.loi-expiry-status-update', $query->id) . '">Not Expired</button>';
                       return $msg;
                    }                                           
                 })
                ->addColumn('loi_quantity', function($query) {
                    $loiQuantity = LetterOfIndentItem::select('letter_of_indent_id','quantity')
                                ->where('letter_of_indent_id', $query->id)
                                ->sum('quantity');
                    return $loiQuantity;
                })
                ->editColumn('status', function($query, Request $request) {
                    if($request->tab == 'SUPPLIER_RESPONSE') {
                        if($query->submission_status == LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED) {
                            $msg = LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED;
                            return '<button class="btn btn-sm btn-success">'.$msg.'</button>';
                        }else if($query->submission_status == LetterOfIndent::LOI_STATUS_SUPPLIER_REJECTED){
                            $msg = LetterOfIndent::LOI_STATUS_SUPPLIER_REJECTED;
                            return '<button class="btn btn-sm btn-danger">'.$msg.'</button>';
                        }else if($query->submission_status == LetterOfIndent::LOI_STATUS_TTC_APPROVED){
                            $msg = LetterOfIndent::LOI_STATUS_TTC_APPROVED;
                            return '<button class="btn btn-sm btn-success">'.$msg.'</button>';
                        }else if($query->submission_status == LetterOfIndent::LOI_STATUS_TTC_REJECTED){
                            $msg = LetterOfIndent::LOI_STATUS_TTC_REJECTED;
                            return '<button class="btn btn-sm btn-danger">'.$msg.'</button>';
                        }
                    }
                        
                })
                ->addColumn('approval_button', function($query, Request $request) {
                    $type = $request->tab;
                   
                    $letterOfIndent = LetterOfIndent::select('id','is_expired','client_id','category','date','submission_status',
                    'country_id')->find($query->id);
                    return view('letter_of_indents.actions.approval_actions',compact('letterOfIndent','type'));
                })
                ->addColumn('action', function($query,Request $request) {
                    $letterOfIndent = LetterOfIndent::select('id','is_expired','signature','comments','utilized_quantity',
                    'status')->find($query->id);
                    $type = $request->tab;
                     
                    return view('letter_of_indents.actions.action',compact('letterOfIndent','type'));
                })
                ->rawColumns(['so_number','loi_templates','loi_quantity','created_by','updated_by',
                        'sales_person_id','is_expired','action','status'])
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
        $models = MasterModel::where('is_milele', true)->groupBy('model')->orderBy('id','ASC')->get();
        $salesPersons = User::where('status','active')->get();

        return view('letter_of_indents.create',compact('countries','models','salesPersons'));
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
            $LOI->country_id = $request->country;
            $LOI->sales_person_id = $request->sales_person_id;
           
            $customer = Clients::find($request->client_id);
            $country = Country::find($request->country);
            $countryName = strtoupper(substr($country->name, 0, 3));

            // $customer->is_demand_planning_customer = true;
            $customer->save();

            $names = explode(" ", $customer->name, 3);
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
            // return $customerNameCode;

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

          
            if($request->customer_other_documents_Ids) {
                foreach($request->customer_other_documents_Ids as $customerDocumentId) {
                    $clientDoc = ClientDocument::find($customerDocumentId);

                    $LoiDocument = new LetterOfIndentDocument();
                    $LoiDocument->loi_document_file = $clientDoc->document;
                    $LoiDocument->letter_of_indent_id = $LOI->id;
                    $LoiDocument->save();
                }
            }
            if($request->is_passport_added == 1) {
                    $LoiDocument = new LetterOfIndentDocument();
                    $LoiDocument->loi_document_file = $customer->passport;
                    $LoiDocument->letter_of_indent_id = $LOI->id;
                    $LoiDocument->is_passport = true;
                    $LoiDocument->save();
            }
            if($request->is_trade_license_added == 1) {
                $LoiDocument = new LetterOfIndentDocument();
                $LoiDocument->loi_document_file = $customer->tradelicense;
                $LoiDocument->letter_of_indent_id = $LOI->id;
                $LoiDocument->is_trade_license = true;
                $LoiDocument->save();
            }
            
            $quantities = $request->quantity;

            foreach ($quantities as $key => $quantity) {
                $masterModel = MasterModel::where('sfx', $request->sfx[$key])
                    ->where('model', $request->models[$key])
                    ->orderBy('model_year','DESC')
                    ->first();
                
                if($masterModel) {
                    $latestRow = LetterOfIndentItem::withTrashed()->orderBy('id', 'desc')->first();
                    $length = 6;
                    $offset = 2;
                    $prefix = "L ";
                    if($latestRow){
                        $latestUUID =  $latestRow->code;
                        $latestUUIDNumber = substr($latestUUID, $offset, $length);

                        $newCode =  str_pad($latestUUIDNumber + 1, 3, 0, STR_PAD_LEFT);
                        $code =  $prefix.$newCode;
                    }else{
                        $code = $prefix.'001';
                    }

                    $LOIItem = new LetterOfIndentItem();
                    $LOIItem->letter_of_indent_id  = $LOI->id;
                    $LOIItem->master_model_id = $masterModel->id ?? '';
                    $LOIItem->code = $code;
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

            if(in_array('general',$request->template_type)) {
                $type = 'general';
            }else{
                $type = $request->template_type[0];
            }
        
            return redirect()->route('letter-of-indents.generate-loi',['id' => $LOI->id,'type' => $type]);

        }else{

            return redirect()->back()->with('error', "LOI with this customer and date and category is already exist.");
        }
    }
    public function getCustomers(Request $request)
    {
        $customers = Clients::with('clientCountries')
            ->whereHas('clientCountries', function($query) use($request){
                $query->where('country_id', $request->country);
            })
            ->where('customertype', $request->customer_type)
            ->get();

        return $customers;
    }
    public function generateLOI(Request $request)
    {
        (new UserActivityController)->createActivity('Generated LOI Document.');
        
        $letterOfIndent = LetterOfIndent::select('id','date','signature','client_id','year_code','country_id')->where('id',$request->id)->first();
        $fileName = $letterOfIndent->client->name .'-'.$letterOfIndent->year_code.'.pdf';
        $letterOfIndentItems = LetterOfIndentItem::where('letter_of_indent_id', $request->id)->orderBy('id','DESC')->get();
        $isCustomerPassport = LetterOfIndentDocument::where('letter_of_indent_id',$letterOfIndent->id)
                            ->where('is_passport',true)->first();
    
        $isCustomerTradeLicense = LetterOfIndentDocument::where('letter_of_indent_id',$letterOfIndent->id)
                        ->where('is_trade_license',true)->first();
        $customerOtherDocAdded = LetterOfIndentDocument::where('letter_of_indent_id',$letterOfIndent->id)
                                        ->where('is_passport', false)
                                        ->where('is_trade_license',false)
                                        ->get(); 



        if ($request->type == 'trans_cars') {
            $width = $request->width;

            if($request->download == 1) {
                
                if($isCustomerPassport) {
                    $isCustomerPassport->order = $request->passport_order;
                    $isCustomerPassport->save();
                }
                if($isCustomerTradeLicense) {
                    $isCustomerTradeLicense->order = $request->trade_license_order;
                    $isCustomerTradeLicense->save();
                }

                if($customerOtherDocAdded->count() > 0) {
                    foreach($customerOtherDocAdded as $otherDoc) {
                        $otherDoc->order = $request->other_document_order;
                        $otherDoc->save();
                    }
                }
            $documents = $letterOfIndent->LOIDocuments()->orderBy('order','ASC')->get();

                try{ 
                $pdfFile = PDF::loadView('letter_of_indents.LOI-templates.trans_car_loi_download_view',
                    compact('letterOfIndent','letterOfIndentItems','width','documents'));
                }catch (\Exception $e){
                    return $e->getMessage();
                }
                 return $pdfFile->download($fileName);
               
            }
            return view('letter_of_indents.LOI-templates.trans_car_loi_template', compact('letterOfIndent','letterOfIndentItems',
            'customerOtherDocAdded','isCustomerTradeLicense','isCustomerPassport'));

        }else if($request->type == 'milele_cars'){
            if($request->download == 1) {

                $width = $request->width;

                if($isCustomerPassport) {
                    $isCustomerPassport->order = $request->passport_order;
                    $isCustomerPassport->save();
                }
                if($isCustomerTradeLicense) {
                    $isCustomerTradeLicense->order = $request->trade_license_order;
                    $isCustomerTradeLicense->save();
                }

                if($customerOtherDocAdded->count() > 0) {
                    foreach($customerOtherDocAdded as $customerOtherDocAdded) {
                        $customerOtherDocAdded->order = $request->other_document_order;
                        $customerOtherDocAdded->save();
                    }

                }
                $documents = $letterOfIndent->LOIDocuments()->orderBy('order','ASC')->get();


                try{
                $pdfFile = PDF::loadView('letter_of_indents.LOI-templates.milele_car_loi_download_view',
                    compact('letterOfIndent','letterOfIndentItems','width','documents'));
                }catch (\Exception $e){
                    return $e->getMessage();
                }
               
                 return $pdfFile->download($fileName);
            }
            return view('letter_of_indents.LOI-templates.milele_car_loi_template', compact('letterOfIndent','letterOfIndentItems',
            'customerOtherDocAdded','isCustomerTradeLicense','isCustomerPassport'));
        } else if($request->type == 'business'){

            if($request->download == 1) {
                $width = $request->width;

                if($isCustomerPassport) {
                    $isCustomerPassport->order = $request->passport_order;
                    $isCustomerPassport->save();
                }
                if($isCustomerTradeLicense) {
                    $isCustomerTradeLicense->order = $request->trade_license_order;
                    $isCustomerTradeLicense->save();
                }

                if($customerOtherDocAdded->count() > 0) {
                    foreach($customerOtherDocAdded as $otherDoc) {
                        $otherDoc->order = $request->other_document_order;
                        $otherDoc->save();
                    }
                }

                $documents = $letterOfIndent->LOIDocuments()->orderBy('order','ASC')->get();


                try{
                $pdfFile = PDF::loadView('letter_of_indents.LOI-templates.business_download_view',
                    compact('letterOfIndent','letterOfIndentItems','width','documents'));
                }catch (\Exception $e){
                    return $e->getMessage();
                }
                return $pdfFile->download($fileName);
               
            }
            return view('letter_of_indents.LOI-templates.business_template', compact('letterOfIndent','letterOfIndentItems',
        'customerOtherDocAdded','isCustomerTradeLicense','isCustomerPassport'));
        }
        else if($request->type == 'individual') {
            if($request->download == 1) {
                $width = $request->width;
                if($isCustomerPassport) {
                    $isCustomerPassport->order = $request->passport_order;
                    $isCustomerPassport->save();
                }
                if($isCustomerTradeLicense) {
                    $isCustomerTradeLicense->order = $request->trade_license_order;
                    $isCustomerTradeLicense->save();
                }

                if($customerOtherDocAdded->count() > 0) {
                    foreach($customerOtherDocAdded as $otherDoc) {
                        $otherDoc->order = $request->other_document_order;
                        $otherDoc->save();
                    }
                }
                $documents = $letterOfIndent->LOIDocuments()->orderBy('order','ASC')->get();

                try{
                $pdfFile = PDF::loadView('letter_of_indents.LOI-templates.individual_download_view',
                    compact('letterOfIndent','letterOfIndentItems','width','documents'));
                }catch (\Exception $e){
                    return $e->getMessage();
                }
               
                return $pdfFile->download($fileName);
                
            }
            return view('letter_of_indents.LOI-templates.individual_template', compact('letterOfIndent','letterOfIndentItems',
            'customerOtherDocAdded','isCustomerTradeLicense','isCustomerPassport'));
        }else{
            if($request->download == 1) {
                if($isCustomerPassport) {
                    $isCustomerPassport->order = $request->passport_order;
                    $isCustomerPassport->save();
                }
                if($isCustomerTradeLicense) {
                    $isCustomerTradeLicense->order = $request->trade_license_order;
                    $isCustomerTradeLicense->save();
                }

                if($customerOtherDocAdded->count() > 0) {
                    foreach($customerOtherDocAdded as $otherDoc) {
                        $otherDoc->order = $request->other_document_order;
                        $otherDoc->save();
                    }
                }
                $documents = $letterOfIndent->LOIDocuments()->orderBy('order','ASC')->get();
                try{
                    $pdfFile = PDF::loadView('letter_of_indents.LOI-templates.general_download_view',
                        compact('letterOfIndent','documents'));
                }catch (\Exception $e){
                    return $e->getMessage();
                }
               
                return $pdfFile->download($fileName);
                
            }
            return view('letter_of_indents.LOI-templates.general_template', compact('letterOfIndent','customerOtherDocAdded','isCustomerTradeLicense','isCustomerPassport'));
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

    public function getCustomerDocuments(Request $request) {

        $client = Clients::find($request->client_id);
        $data = [];
        $data['customer_documents'] = $client->clientDocuments;
        $data['passport_file'] = $client->passport;
        $data['trade_license_file'] =  $client->tradelicense;
        
        return $data;

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
        (new UserActivityController)->createActivity('Open LOI Edit Page.');
       
        $letterOfIndent = LetterOfIndent::find($id);
        $LOICountries = LoiCountryCriteria::where('status', LoiCountryCriteria::STATUS_ACTIVE)->where('is_loi_restricted', false)->pluck('country_id');
        $countries = Country::whereIn('id', $LOICountries)->get();
        $possibleCustomers = Clients::with('clientCountries')
                        ->whereHas('clientCountries', function($query) use($letterOfIndent){
                            $query->where('country_id', $letterOfIndent->country_id);
                        })
                    ->where('customertype', $letterOfIndent->client->customertype)
                    ->get();
        $isCustomerPassport = LetterOfIndentDocument::where('letter_of_indent_id',$letterOfIndent->id)
                                ->where('is_passport',true)->first();
                            
        $isCustomerTradeLicense = LetterOfIndentDocument::where('letter_of_indent_id',$letterOfIndent->id)
                                    ->where('is_trade_license',true)->first();
        $customerOtherDocAdded = LetterOfIndentDocument::where('letter_of_indent_id',$letterOfIndent->id)
                                    ->where('is_passport', false)
                                    ->where('is_trade_license',false)
                                    ->get(); 
        $customerOtherDocAddedArray = $customerOtherDocAdded->pluck('loi_document_file')->toArray();
        $customerOtherDocNotAdded = clientDocument::where('client_id',$letterOfIndent->client_id)
                                        ->whereNotIn('document', $customerOtherDocAddedArray)
                                        ->get(); 
                                        // return $customerOtherDocAdded;

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

        return view('letter_of_indents.edit', compact('countries','letterOfIndent','models','letterOfIndentItems',
        'salesPersons','possibleCustomers','LOITemplates','isCustomerPassport','isCustomerTradeLicense','customerOtherDocAdded',
        'customerOtherDocNotAdded'));
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
        // return $request->all();

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

                $isCustomerPassport = LetterOfIndentDocument::where('letter_of_indent_id', $LOI->id)
                ->where('is_passport',true)->first();

                $isCustomerTradeLicense = LetterOfIndentDocument::where('letter_of_indent_id', $LOI->id)
                        ->where('is_trade_license',true)->first();

                $customer = Clients::find($request->client_id);
                $country = Country::find($request->country);
                $countryName = strtoupper(substr($country->name, 0, 3));

                $names = explode(" ", $customer->name, 3);
                $customerNameCode = "";
                foreach ($names as $name) {
                    $customerNameCode .= strtoupper(mb_substr($name, 0, 1));
                }
                $customerCode = str_pad($customerNameCode, 3, '0', STR_PAD_RIGHT);
                
                $customerYearCode = $LOI->year_code;
                
                $uuid = $countryName . $customerCode ."-".$customerYearCode;
               
                $LOI->uuid = $uuid;
                $LOI->client_id = $request->client_id;
                $LOI->country_id = $request->country;
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
            
                $LOI->LOITemplates()->delete();
                if($request->deletedIds) {
                    LetterOfIndentDocument::whereIn('id', $request->deletedIds)->delete();
                    // delete the corresponding file also
                }
                if($request->customer_other_documents_Ids) {
                    foreach($request->customer_other_documents_Ids as $customerDocumentId) {
                        $clientDoc = ClientDocument::find($customerDocumentId);
    
                        $LoiDocument = new LetterOfIndentDocument();
                        $LoiDocument->loi_document_file = $clientDoc->document;
                        $LoiDocument->letter_of_indent_id = $LOI->id;
                        $LoiDocument->save();
                    }
                }
                 // if is_passport_added value 2 remove old passport and add latest passport for the loi
                //    if is_passport_added value 1 chcek passport is alredy existing or not , if not existing create new loi document
                // if is_passport_added value 0 remove all the passport
                if($request->is_passport_added == 1) {
                    if(!$isCustomerPassport) {
                        $LoiDocument = new LetterOfIndentDocument();
                        $LoiDocument->loi_document_file = $customer->passport;
                        $LoiDocument->letter_of_indent_id = $LOI->id;
                        $LoiDocument->is_passport = true;
                        $LoiDocument->save();
                    }
                   
                }else if($request->is_passport_added == 2){
                    // value 2
                    // update new passport
                    if($isCustomerPassport) {
                        $isCustomerPassport->loi_document_file = $customer->passport;
                        $isCustomerPassport->save();
                    }                       
                 }else{

                    LetterOfIndentDocument::where('is_passport', true)
                                        ->where('letter_of_indent_id', $LOI->id)->delete();
                }
                if($request->is_trade_license_added == 1 ) {
                    if(!$isCustomerTradeLicense) {
                        $LoiDocument = new LetterOfIndentDocument();
                        $LoiDocument->loi_document_file = $customer->tradelicense;
                        $LoiDocument->letter_of_indent_id = $LOI->id;
                        $LoiDocument->is_trade_license = true;
                        $LoiDocument->save();
                    }
                  
                }else if($request->is_trade_license_added == 2){
                    // update new passport
                    if($isCustomerTradeLicense) {
                        $isCustomerTradeLicense->loi_document_file = $customer->tradelicense;
                        $isCustomerTradeLicense->save();
                    }                       
                 }else{
                    LetterOfIndentDocument::where('is_trade_license', true)
                                ->where('letter_of_indent_id', $LOI->id)->delete();
                }

                $alreadyAddedRows = LetterOfIndentItem::where('letter_of_indent_id', $LOI->id)->pluck('id')->toArray();
                $updatedRows = [];
                $quantities = $request->quantity;
                foreach ($quantities as $key => $quantity) {
                    $masterModel = MasterModel::where('sfx', $request->sfx[$key])
                    ->where('model', $request->models[$key])
                    ->pluck('id')->toArray();

                    if($masterModel) 
                    {
                        $isItemExist = LetterOfIndentItem::where('letter_of_indent_id', $LOI->id)
                                ->whereIn('master_model_id', $masterModel)                      
                                ->first();
                        if($isItemExist) {
                            $isItemExist->quantity = $quantity;
                            $isItemExist->update();
                            $updatedRows[] = $isItemExist->id;
                            
                        }else{
                            $latestRow = LetterOfIndentItem::withTrashed()->orderBy('id', 'desc')->first();
                            
                            $length = 6;
                            $offset = 2;
                            $prefix = "L ";
                            if($latestRow){
                                $latestUUID =  $latestRow->code;
                                $latestUUIDNumber = substr($latestUUID, $offset, $length);
                                $newCode =  str_pad($latestUUIDNumber + 1, 3, 0, STR_PAD_LEFT);
                                $code =  $prefix.$newCode;
                            }else{
                                $code = $prefix.'001';
                            }
                            $model = MasterModel::where('sfx', $request->sfx[$key])
                                            ->where('model', $request->models[$key])
                                            ->orderBy('model_year','DESC')
                                            ->first();
                            $LOIItem = new LetterOfIndentItem();
                            $LOIItem->letter_of_indent_id = $LOI->id;
                            $LOIItem->master_model_id = $model->id ?? '';
                            $LOIItem->quantity = $quantity;
                            $LOIItem->code = $code;
                            $LOIItem->save();
                        }
                    }
                }  
                $deletedRows = array_diff($alreadyAddedRows,$updatedRows);
                LetterOfIndentItem::whereIn('id', $deletedRows)->delete();

                $LOI->soNumbers()->delete();
                if($request->so_number) {
                
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

            if(in_array('general',$request->template_type)) {
                $type = 'general';
            }else{
                $type = $request->template_type[0];
            }

            return redirect()->route('letter-of-indents.generate-loi',['id' => $LOI->id,'type' => $type]);

        }else{
            return redirect()->back()->with('error', "LOI with this customer and date and category is already exist.");
        }
    }
    public function RequestApproval(Request $request)
    {
        if($request->type) {
            (new UserActivityController)->createActivity('LOI (ttc approval)'. $request->type .'Successfully.');

        }else{
            (new UserActivityController)->createActivity('Requested for Supplier Approval of LOI.');
        }
      
      $LOI = LetterOfIndent::find($request->id);

      $LOI->submission_status = $request->status;
      $LOI->status = $request->status;
      $LOI->updated_by = Auth::id();
      $LOI->save();

      return response(true);

    }
    public function supplierApproval(Request $request) {
    
        $LOI = LetterOfIndent::find($request->id);
        DB::beginTransaction();

        $msg = '';
        if($request->status == 'REJECTED') {
            $LOI->status = LetterOfIndent::LOI_STATUS_SUPPLIER_REJECTED;
            $LOI->submission_status = LetterOfIndent::LOI_STATUS_SUPPLIER_REJECTED;
            (new UserActivityController)->createActivity('Supplier Rejected successfully.');

        }elseif ($request->status == 'APPROVE') {
            (new UserActivityController)->createActivity('Supplier Approved successfully.');

            // get LOI Items 

            $loiModels =  MasterModel::whereHas('loiItems', function($query)use($request){
                                $query->where('letter_of_indent_id', $request->id);
                            })->pluck('model')->toArray();
            $possibleMasterModels = MasterModel::whereIn('model', $loiModels)->pluck('id')->toArray();
            $isTTCApprovalRequired = 0;
            $country_id = $LOI->country_id;
            $TTCApprovalModels =  MasterModel::whereHas('TTCApprovalCountry', function($query)use($country_id){
                                $query->where('country_id', $country_id);
                            })->pluck('id')->toArray();
                    
            foreach($possibleMasterModels as $possibleMasterModel) {
            if(in_array($possibleMasterModel, $TTCApprovalModels)) {
                    $isTTCApprovalRequired = 1;
                    break;
                }
            }
            if($isTTCApprovalRequired == 1) {
                $LOI->status = LetterOfIndent::LOI_STATUS_WAITING_FOR_TTC_APPROVAL;
                $LOI->submission_status = LetterOfIndent::LOI_STATUS_WAITING_FOR_TTC_APPROVAL;
                $msg = 'LOI Status Changed to Waiting for TTC Approval';

            }else{
                $LOI->status = LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED;
                $LOI->submission_status = LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED;
            }

        }
        $LOI->review = $request->review;
        $LOI->loi_approval_date = $request->loi_approval_date;
        $LOI->updated_by = Auth::id();
        $LOI->save();

        DB::commit();

        return response()->json($msg);
        // return redirect()->back()->with('success', 'Supplier'. $msg .' Successfully.');
    }
    public function updateComment(Request $request) {
        (new UserActivityController)->createActivity('LOI Comment updated successfully.');
        $LOI = LetterOfIndent::find($request->id);
        $LOI->comments = $request->comments;
        $LOI->updated_by = Auth::id();
        $LOI->save();

        return response(true);
        // return redirect()->back()->with('success', 'LOI Comment updated successfully.');
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
    public function statusUpdate(Request $request, $id) {
        (new UserActivityController)->createActivity('LOI Status updated as New.');
        
        $LOI = LetterOfIndent::find($id);
        $LOI->status = $request->status;
        $LOI->submission_status = $request->status;
        $LOI->updated_by = Auth::id();
        $LOI->save();

        return redirect()->back()->with('success', 'Status updated as "New" successfully.');

    }
    public function ExpiryStatusUpdate($id) {

        (new UserActivityController)->createActivity('LOI Expiry Status updated as Expired.');
        
        $LOI = LetterOfIndent::find($id);
        $LOI->is_expired = true;
        $LOI->expired_date = Carbon::now()->format('Y-m-d');
        $LOI->updated_by = Auth::id();
        $LOI->timestamps = false;
        $LOI->save();

        return response(true);
    }

    public function getIsLOIEditable(Request $request) {
        $loi_id = $request->id;
        $pfiItems = PfiItem::where('is_parent', false)
                ->WhereHas('letterOfIndentItem', function($query)use($loi_id) {
                    $query->where('letter_of_indent_id', $loi_id);
                });
        $data = [];
        if($pfiItems->count() <= 0) {
            // not editable
            $data['is_editable'] = 1;
           
        }else{
            $alreadyUsedLOIItems = [];
            foreach($pfiItems->get() as $pfiItem) {
                $alreadyUsedLOIItems[] = $pfiItem->letterOfIndentItem->code." - (PFI Number - ".$pfiItem->pfi->pfi_reference_number." ) ";

            }
            $data['is_editable'] = $alreadyUsedLOIItems;
        }
        $data['editUrl'] = route('letter-of-indents.edit', $loi_id);

        return response($data);
    }

}
