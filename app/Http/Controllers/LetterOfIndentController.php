<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\LetterOfIndent;
use App\Models\LetterOfIndentItem;
use Barryvdh\DomPDF\Facade\PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = CountryListFacade::getList('en');
        $customers = Customer::all();

        return view('letter_of_indents.create',compact('countries','customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'category' => 'required',
            'date' => 'required'
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
            $LOI->submission_status = LetterOfIndent::LOI_SUBMISION_STATUS_NEW;
            $LOI->status = LetterOfIndent::LOI_STATUS_NEW;
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

//        return view('letter_of_indents.LOI-templates.milele_car_loi_template', compact('letterOfIndent','letterOfIndentItems'));
        if ($letterOfIndent->dealers == 'Trans Car') {
            $pdfFile = PDF::loadView('letter_of_indents.LOI-templates.trans_car_loi_template', compact('letterOfIndent','letterOfIndentItems'));

        }else{
            $pdfFile = PDF::loadView('letter_of_indents.LOI-templates.milele_car_loi_template', compact('letterOfIndent','letterOfIndentItems'));

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

        return $pdfFile->download('LOI_'.$letterOfIndent->id.date('Y_m_d').'.pdf');
//        return response()->download($modifiedPdfPath);


    }
    public function approve(Request $request) {
        $letterOfIndent = LetterOfIndent::find($request->id);

        $letterOfIndent->status = $request->status;
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
