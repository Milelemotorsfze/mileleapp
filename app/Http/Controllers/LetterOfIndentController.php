<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Demand;
use App\Models\DemandList;
use App\Models\LetterOfIndent;
use App\Models\LetterOfIndentItem;
use App\Models\MasterModel;
use App\Models\Supplier;
use App\Models\SupplierInventory;
use Barryvdh\DomPDF\Facade\PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $newLOIs = LetterOfIndent::orderBy('id','DESC')
            ->where('status',LetterOfIndent::LOI_STATUS_NEW)
            ->cursor();
        $approvedLOIs = LetterOfIndent::orderBy('id','DESC')
            ->where('status',LetterOfIndent::LOI_STATUS_APPROVED)
            ->cursor();
        $partialApprovedLOIs =  LetterOfIndent::orderBy('id','DESC')
            ->where('status', LetterOfIndent::LOI_STATUS_PARTIAL_APPROVED)
            ->cursor();
        $supplierApprovedLOIs =  LetterOfIndent::orderBy('id','DESC')
            ->where('status', LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED)
            ->cursor();
        $rejectedLOIs =  LetterOfIndent::orderBy('id','DESC')
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
            ->get();

        $approvalPendingLOIs = LetterOfIndent::orderBy('id','DESC')
            ->where('status', LetterOfIndent::LOI_STATUS_NEW);

        $approvedLOIs = LetterOfIndent::orderBy('id','DESC')
            ->where('status',LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED);
        $rejectedLOIs =  LetterOfIndent::orderBy('id','DESC')
            ->where('status', LetterOfIndent::LOI_STATUS_REJECTED);

        if ($request->supplier_id)
        {
            $supplierId = $request->supplier_id;
            $approvalPendingLOIs = $approvalPendingLOIs->where('supplier_id', $request->supplier_id);
            $approvedLOIs =  $approvedLOIs->where('supplier_id', $request->supplier_id);
            $rejectedLOIs = LetterOfIndent::orderBy('id','DESC')
                ->where('status', LetterOfIndent::LOI_STATUS_REJECTED)
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
        $countries = CountryListFacade::getList('en');
        $customers = Customer::all();
        $suppliers = Supplier::with('supplierTypes')
            ->whereHas('supplierTypes', function ($query) {
                $query->where('supplier_type', Supplier::SUPPLIER_TYPE_DEMAND_PLANNING);
            })
            ->get();
        return view('letter_of_indents.create',compact('countries','customers','suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'category' => 'required',
            'date' => 'required',
            'shipment_method' => 'required',
            'dealers' => 'required',
            'supplier_id' => 'required'
        ]);

        $LOI = LetterOfIndent::where('customer_id', $request->customer_id)
            ->whereDate('date', Carbon::createFromFormat('Y-m-d', $request->date))
            ->where('category', $request->category)
            ->where('submission_status', LetterOfIndent::LOI_SUBMISION_STATUS_NEW)
            ->where('status', LetterOfIndent::LOI_STATUS_NEW)
            ->first();
        if (!$LOI)
        {
            $LOI = new LetterOfIndent();
            $LOI->customer_id = $request->customer_id;
            $LOI->date = Carbon::createFromFormat('Y-m-d', $request->date);
            $LOI->category = $request->category;
            $LOI->dealers = $request->dealers;
            $LOI->shipment_method = $request->shipment_method;
            $LOI->supplier_id = $request->supplier_id;
            $LOI->submission_status = LetterOfIndent::LOI_SUBMISION_STATUS_NEW;
            $LOI->status = LetterOfIndent::LOI_STATUS_NEW;
            $LOI->created_by = Auth::id();
            $LOI->save();
        }

        return redirect()->route('letter-of-indent-items.create',['id' => $LOI->id]);
    }
    public function getCustomers(Request $request)
    {
        $customers = Customer::where('country', $request->country)
            ->where('type', $request->customer_type)
            ->get();

        return $customers;
    }
    public function generateLOI(Request $request)
    {
        $letterOfIndent = LetterOfIndent::where('id',$request->id)->first();
        $letterOfIndentItems = LetterOfIndentItem::where('letter_of_indent_id', $request->id)->get();

        if ($letterOfIndent->dealers == 'Trans Cars') {
            $height = $request->height;
            $width = $request->width;

            if($request->download == 1) {
                $pdfFile = PDF::loadView('letter_of_indents.LOI-templates.trans_car_loi_download_view',
                    compact('letterOfIndent','letterOfIndentItems','height','width'));
                return $pdfFile->stream('LOI_'.$letterOfIndent->id.date('Y_m_d').'.pdf');
            }
            return view('letter_of_indents.LOI-templates.trans_car_loi_template', compact('letterOfIndent','letterOfIndentItems'));
        }else{
            if($request->download == 1) {
                $height = $request->height;
                $width = $request->width;

                $pdfFile = PDF::loadView('letter_of_indents.LOI-templates.milele_car_loi_download_view',
                    compact('letterOfIndent','letterOfIndentItems','height','width'));
               return $pdfFile->stream('LOI_'.$letterOfIndent->id.date('Y_m_d').'.pdf');
            }
            return view('letter_of_indents.LOI-templates.milele_car_loi_template', compact('letterOfIndent','letterOfIndentItems'));
        }

//        $pdfFile = PDF::loadView('letter_of_indents.loi_document', compact('letterOfIndent','letterOfIndentItems'));
//        return $pdfFile;
//        Storage::disk('local')->makeDirectory('/GENERATE_LOI');
//
//        $path = 'storage/GENERATE_LOI/LOI_'.$letterOfIndent->id.'.pdf';
//        $pdfFile->save($path);
//
//        $pdf = new Fpdi();
//        $pdf->setSourceFile($path);
//
//        // Remove metadata and date from each page
//        $pageCount = $pdf->setSourceFile($path);
////        return $pageCount;
//        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
//            $tplIdx = $pdf->importPage($pageNo);
//            $pdf->AddPage();
//            $pdf->useTemplate($tplIdx);
//            // Remove metadata
//            $pdf->SetTitle('');
//            $pdf->SetAuthor('');
//            $pdf->SetCreator('anna');
//            $pdf->SetSubject( '');
//            $pdf->SetKeywords( '');
//
//            // Remove the date
//            $pdf->SetX('CreationDate', '01/05/2021');
//        }
//
//        Storage::disk('local')->makeDirectory('/STORE_LOI');
//
//        $modifiedPdfPath = 'storage/STORE_LOI/LOI_'.$letterOfIndent->id.'.pdf';
//        $pdf->Output($modifiedPdfPath, 'F');

        // Download the modified PDF

        return $pdfFile->stream('LOI_'.$letterOfIndent->id.date('Y_m_d').'.pdf');
//        return response()->download($modifiedPdfPath);


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
        $countries = CountryListFacade::getList('en');
        $customers = Customer::all();
        $suppliers = Supplier::with('supplierTypes')
            ->whereHas('supplierTypes', function ($query) {
                $query->where('supplier_type', Supplier::SUPPLIER_TYPE_DEMAND_PLANNING);
            })
            ->get();

        return view('letter_of_indents.edit', compact('countries','customers','letterOfIndent','suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'customer_id' => 'required',
            'category' => 'required',
            'date' => 'required',
            'shipment_method' => 'required',
            'dealers' => 'required',
            'supplier_id' => 'required'
        ]);

        $LOI = LetterOfIndent::find($id);

        $LOI->customer_id = $request->customer_id;
        $LOI->date = Carbon::createFromFormat('Y-m-d', $request->date);
        $LOI->category = $request->category;
        $LOI->dealers = $request->dealers;
        $LOI->supplier_id = $request->supplier_id;
        $LOI->shipment_method = $request->shipment_method;
        $LOI->save();

        return redirect()->route('letter-of-indent-items.edit', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
