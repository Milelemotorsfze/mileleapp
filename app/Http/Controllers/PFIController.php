<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ApprovedLetterOfIndentItem;
use App\Models\LetterOfIndent;
use App\Models\LetterOfIndentItem;
use App\Models\LOIItemPurchaseOrder;
use App\Models\PFI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\PdfReader\Page;
use setasign\Fpdi\Tcpdf\Fpdi;

class PFIController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        (new UserActivityController)->createActivity('Open PFI List Section');

        $pfis = PFI::orderBy('id','DESC')->get();
        foreach ($pfis as $pfi) {
            $approvedLOIItemIds = ApprovedLetterOfIndentItem::where('pfi_id', $pfi->id)->pluck('id');
            $pfiTotalQuantity = ApprovedLetterOfIndentItem::where('pfi_id', $pfi->id)->sum('quantity');

            $totalPoCreatedQuantity = LOIItemPurchaseOrder::whereIn('approved_loi_id', $approvedLOIItemIds)
                                        ->sum('quantity');
            if($pfiTotalQuantity == $totalPoCreatedQuantity) {
               $pfi->is_po_active = false;
            }else{
               $pfi->is_po_active = true;
            }
        }
        return view('pfi.index', compact('pfis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        (new UserActivityController)->createActivity('Open PFI Create Page');

        $letterOfIndent = LetterOfIndent::findOrFail($request->id);
        $approvedPfiItems = ApprovedLetterOfIndentItem::where('letter_of_indent_id', $request->id)
                                        ->whereNull('pfi_id')
                                        ->where('is_pfi_created', true)
                                        ->get();
        $pendingPfiItems = ApprovedLetterOfIndentItem::where('letter_of_indent_id', $request->id)
                                        ->whereNull('pfi_id')
                                        ->where('is_pfi_created', false)
                                        ->get();

        return view('pfi.create', compact('pendingPfiItems','approvedPfiItems','letterOfIndent'));
    }
    public function addPFI(Request $request)
    {
        $approevdLOI = ApprovedLetterOfIndentItem::findOrFail($request->id);
        if($request->action == 'REMOVE') {
            $approevdLOI->is_pfi_created = false;
        }else{
            $approevdLOI->is_pfi_created = true;
        }
        $approevdLOI->save();

        return response(true);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        (new UserActivityController)->createActivity('New PFI Created');

        $request->validate([
            'pfi_reference_number' => 'required',
            'pfi_date' => 'required',
            'amount'  => 'required',
            'file' => 'required|mimes:pdf'
        ]);

        DB::beginTransaction();
        $pfi = new PFI();

        $pfi->pfi_reference_number = $request->pfi_reference_number;
        $pfi->pfi_date = $request->pfi_date;
        $pfi->amount = $request->amount;
        $pfi->letter_of_indent_id = $request->letter_of_indent_id;
        $pfi->created_by = Auth::id();
        $pfi->comment = $request->comment;
        $pfi->status = PFI::PFI_STATUS_NEW;

        $destinationPath = 'PFI_document_withoutsign';
        $destination = 'PFI_document_withsign';

        if ($request->has('file'))
        {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'.'.$extension;
            $file->move($destinationPath, $fileName);
            $pfi->pfi_document_without_sign = $fileName;
        }

        $pfi->save();

        $currentlyApprovedItems = ApprovedLetterOfIndentItem::where('letter_of_indent_id', $request->letter_of_indent_id)
                                                        ->where('is_pfi_created', true)
                                                        ->whereNull('pfi_id')
                                                        ->get();

        $letterOfIndent = LetterOfIndent::find($request->letter_of_indent_id);

        $pfiApprovedQuantity = $currentlyApprovedItems->sum('quantity');

        // status change in LOI table by checking quantity of pfi created untill now
        if($pfiApprovedQuantity == $letterOfIndent->total_loi_quantity) {
            $letterOfIndent->status = LetterOfIndent::LOI_STATUS_PFI_CREATED;
        }else{
            $letterOfIndent->status = LetterOfIndent::LOI_STATUS_PARTIAL_PFI_CREATED;
        }
        $letterOfIndent->save();
        // update pfiId FOR EACH ADDED ITEM
        foreach ($currentlyApprovedItems as $currentlyApprovedItem)
        {
            $approvedLoiItem = ApprovedLetterOfIndentItem::find($currentlyApprovedItem->id);
            $approvedLoiItem->pfi_id = $pfi->id;
            $approvedLoiItem->save();
        }
        DB::commit();
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($destinationPath.'/'.$fileName);

        for ($i=1; $i <= $pageCount; $i++)
        {
            $pdf->AddPage();
            $tplIdx = $pdf->importPage($i);
            $pdf->useTemplate($tplIdx);
            if($i==$pageCount) {
                if($letterOfIndent->dealers == 'Trans Car')
                $pdf->Image('milele_seal.png', 80, 230, 50,35);
            }
        }

        $signedFileName = 'signed_'.time().'.'.$extension;
        $directory = public_path('PFI_Document_with_sign');
        \Illuminate\Support\Facades\File::makeDirectory($directory, $mode = 0777, true, true);
        $pdf->Output($directory.'/'.$signedFileName,'F');
        $pfi->pfi_document_with_sign = $signedFileName;
        $pfi->save();

        return redirect()->route('pfi.index')->with('message', 'PFI created Successfully');
    }
    public function uniqueCheckPfiReferenceNumber(Request $request) {
//         return $request->all();
        $pfi = PFI::where('pfi_reference_number', $request->pfi_reference_number)->first();
        if($pfi) {
            return response(true);
        }else{
            return response(false);
        }
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
