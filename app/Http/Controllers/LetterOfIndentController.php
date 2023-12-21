<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Customer;
use App\Models\Demand;
use App\Models\DemandList;
use App\Models\LetterOfIndent;
use App\Models\LetterOfIndentDocument;
use App\Models\LetterOfIndentItem;
use App\Models\MasterModel;
use App\Models\Supplier;
use App\Models\SupplierInventory;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $approvedLOIs = LetterOfIndent::with('letterOfIndentItems','LOIDocuments')
            ->orderBy('id','DESC')
            ->whereIn('status',[LetterOfIndent::LOI_STATUS_APPROVED,LetterOfIndent::LOI_STATUS_PARTIAL_PFI_CREATED])
            ->cursor();
        $partialApprovedLOIs =  LetterOfIndent::with('letterOfIndentItems','LOIDocuments')
            ->orderBy('id','DESC')
            ->whereIn('status', [LetterOfIndent::LOI_STATUS_PARTIAL_APPROVED,LetterOfIndent::LOI_STATUS_PARTIAL_PFI_CREATED])
            ->cursor();
        $supplierApprovedLOIs =  LetterOfIndent::with('letterOfIndentItems','LOIDocuments')
            ->orderBy('id','DESC')
            ->where('status', LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED)
            ->cursor();
        $rejectedLOIs =  LetterOfIndent::with('letterOfIndentItems','LOIDocuments')
            ->orderBy('id','DESC')
            ->where('status', LetterOfIndent::LOI_STATUS_REJECTED)
            ->cursor();

        return view('letter_of_indents.index', compact('newLOIs','approvedLOIs',
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
        $countries = Country::all();
        $customers = Customer::all();

        $models = MasterModel::groupBy('model')->orderBy('id','ASC')->get();

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
                $pdf->useTemplate($tplIdx);
            }
        }
        return $pdf;
    }
    public function approve(Request $request)
    {

        $letterOfIndent = LetterOfIndent::find($request->id);
        $letterOfIndent->status = $request->status;
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

        $models = MasterModel::groupBy('model')->orderBy('id','ASC')->get();
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
}
