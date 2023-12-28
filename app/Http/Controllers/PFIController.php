<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ApprovedLetterOfIndentItem;
use App\Models\LetterOfIndent;
use App\Models\LetterOfIndentItem;
use App\Models\LOIItemPurchaseOrder;
use App\Models\PFI;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\PdfReader\Page;
use setasign\Fpdi\Tcpdf\Fpdi;
use Illuminate\Support\Facades\File;

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
        $suppliers = Supplier::with('supplierTypes')
            ->whereHas('supplierTypes', function ($query) {
                $query->where('supplier_type', Supplier::SUPPLIER_TYPE_DEMAND_PLANNING);
            })
            ->where('status', Supplier::SUPPLIER_STATUS_ACTIVE)
            ->get();

        return view('pfi.create', compact('pendingPfiItems','approvedPfiItems','letterOfIndent','suppliers'));
    }
    public function addPFI(Request $request)
    {
        $approevdLOI = ApprovedLetterOfIndentItem::with('letterOfIndentItem.masterModel')
                                    ->findOrFail($request->id);

        if($request->action == 'REMOVE') {
            // remove from pfi
            if($request->pfi_id) {
                $approevdLOI->pfi_id = NULL;
            }

            $approevdLOI->is_pfi_created = false;
            // $approevdLOI->discount = $request->discount;
            // $approevdLOI->unit_price = $request->unit_price;
        }else{
            // add to pfi
            if($request->pfi_id) {
                $approevdLOI->pfi_id = $request->pfi_id;
            }

            $approevdLOI->is_pfi_created = true;
            // $approevdLOI->discount = $request->discount;
            // $approevdLOI->unit_price = $request->unit_price;
        }

        $approevdLOI->save();

        if($request->pfi_id) {
            $approvedItemCount = ApprovedLetterOfIndentItem::where('letter_of_indent_id', $approevdLOI->letter_of_indent_id)
                ->where('pfi_id', $request->pfi_id)
                ->where('is_pfi_created', true)
                ->count();
        }else{
            $approvedItemCount = ApprovedLetterOfIndentItem::where('letter_of_indent_id', $approevdLOI->letter_of_indent_id)
                ->whereNull('pfi_id')
                ->where('is_pfi_created', true)
                ->count();
        }

        $approevdLOI['approvedItems'] = $approvedItemCount;
       
        if($request->supplier_id) {
            $supplier = Supplier::find($request->supplier_id);
            $loiItem = LetterOfIndentItem::find($approevdLOI->letter_of_indent_item_id);
        //    if($approevdLOI->unit_price){
        //       $price = $approevdLOI->unit_price;
        //    }else{
                if($supplier->is_MMC == true) {
                    $price = $loiItem->masterModel->amount_belgium > 0 ? $loiItem->masterModel->amount_belgium : 0;
                }else if($supplier->is_AMS == true) {
                    $price = $loiItem->masterModel->amount_uae > 0 ? $loiItem->masterModel->amount_uae : 0;
                }else{
                    $price = 0;
                }
        //    }
            
            $approevdLOI['unit_price'] = $price;
            // $approevdLOI['unit_price'] = $price;
        }

        return response($approevdLOI);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
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
        $pfi->delivery_location = $request->delivery_location;
        $pfi->currency = $request->currency;
        $pfi->supplier_id = $request->supplier_id;
        $pfi->released_amount = $request->released_amount;

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
        foreach ($currentlyApprovedItems as $key => $currentlyApprovedItem)
        {
            $approvedLoiItem = ApprovedLetterOfIndentItem::find($currentlyApprovedItem->id);
            $approvedLoiItem->pfi_id = $pfi->id;
            $approvedLoiItem->discount = $request->discount[$key];
            $approvedLoiItem->unit_price = $request->unit_price[$key];
            $approvedLoiItem->save();
        }

        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($destinationPath.'/'.$fileName);

        for ($i=1; $i <= $pageCount; $i++)
        {
            $pdf->AddPage();
            $tplIdx = $pdf->importPage($i);
            $pdf->useTemplate($tplIdx);
            if($i == $pageCount) {
                $pdf->Image('milele_seal.png', 80, 230, 50,35);
            }
        }

        $signedFileName = 'signed_'.time().'.'.$extension;
        $directory = public_path('PFI_Document_with_sign');
        \Illuminate\Support\Facades\File::makeDirectory($directory, $mode = 0777, true, true);
        $pdf->Output($directory.'/'.$signedFileName,'F');
        $pfi->pfi_document_with_sign = $signedFileName;
        $pfi->save();

        DB::commit();

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
        $pfi = PFI::find($id);

        (new UserActivityController)->createActivity('Open PFI Edit Page');

        $letterOfIndent = LetterOfIndent::findOrFail($pfi->letter_of_indent_id);
        $approvedPfiItems = ApprovedLetterOfIndentItem::where('pfi_id', $id)
            ->where('is_pfi_created', true)
            ->get();
        $pendingPfiItems = ApprovedLetterOfIndentItem::where('letter_of_indent_id', $letterOfIndent->id)
            ->whereNull('pfi_id')
            ->where('is_pfi_created', false)
            ->get();
        $suppliers = Supplier::with('supplierTypes')
            ->whereHas('supplierTypes', function ($query) {
                $query->where('supplier_type', Supplier::SUPPLIER_TYPE_DEMAND_PLANNING);
            })
            ->where('status', Supplier::SUPPLIER_STATUS_ACTIVE)
            ->get();

        return view('pfi.edit', compact('pfi','pendingPfiItems','approvedPfiItems','letterOfIndent','suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
//        dd($request->all());
        (new UserActivityController)->createActivity('Updated PFI Details');

        $request->validate([
            'pfi_reference_number' => 'required',
            'pfi_date' => 'required',
            'amount'  => 'required',
            'file' => 'mimes:pdf'
        ]);

        DB::beginTransaction();
        $pfi = PFI::find($id);

        $pfi->pfi_reference_number = $request->pfi_reference_number;
        $pfi->pfi_date = Carbon::parse($request->pfi_date)->format('Y-m-d');
        $pfi->amount = $request->amount;
        $pfi->comment = $request->comment;
        $pfi->delivery_location = $request->delivery_location;
        $pfi->currency = $request->currency;
        $pfi->supplier_id = $request->supplier_id;
        $pfi->released_amount = $request->released_amount;

        $destinationPath = 'PFI_document_withoutsign';
        $destination = 'PFI_document_withsign';

        if ($request->has('file'))
        {
            if (File::exists(public_path('PFI_document_withoutsign/'.$pfi->pfi_document_without_sign))) {
                File::delete(public_path('PFI_document_withoutsign/'.$pfi->pfi_document_without_sign));
            }
            if (File::exists(public_path('PFI_document_withsign/'.$pfi->pfi_document_with_sign))) {
                File::delete(public_path('PFI_document_withsign/'.$pfi->pfi_document_with_sign));
            }
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'.'.$extension;
            $file->move($destinationPath, $fileName);
            $pfi->pfi_document_without_sign = $fileName;

            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile($destinationPath.'/'.$fileName);

            for ($i=1; $i <= $pageCount; $i++)
            {
                $pdf->AddPage();
                $tplIdx = $pdf->importPage($i);
                $pdf->useTemplate($tplIdx);
                if($i == $pageCount) {
                    $pdf->Image('milele_seal.png', 80, 230, 50,35);
                }
            }

            $signedFileName = 'signed_'.time().'.'.$extension;
            $directory = public_path('PFI_Document_with_sign');
            \Illuminate\Support\Facades\File::makeDirectory($directory, $mode = 0777, true, true);
            $pdf->Output($directory.'/'.$signedFileName,'F');
            $pfi->pfi_document_with_sign = $signedFileName;
        }

        $pfi->save();

        $currentlyApprovedItems = ApprovedLetterOfIndentItem::where('letter_of_indent_id', $pfi->letter_of_indent_id)
            ->where('is_pfi_created', true)
            ->get();

        $letterOfIndent = LetterOfIndent::find($pfi->letter_of_indent_id);

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

        return redirect()->route('pfi.index')->with('message', 'PFI Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pfi = PFI::find($id);
        $approvedItemsForPFIs = ApprovedLetterOfIndentItem::where('pfi_id', $id)->get();

        DB::beginTransaction();
        // make pfi creation reverse when it is deleting
        if($approvedItemsForPFIs) {
            foreach ($approvedItemsForPFIs as $approvedItemsForPFI) {
                $approvedItemsForPFI->pfi_id = NULL;
                $approvedItemsForPFI->is_pfi_created = false;
                $approvedItemsForPFI->save();
            }

            $letterOfIndent = LetterOfIndent::find($pfi->letter_of_indent_id);
            
            // change the status to previous while deleting PO
            if($letterOfIndent->total_loi_quantity == $letterOfIndent->total_approved_quantity) {
                $letterOfIndent->status = LetterOfIndent::LOI_STATUS_APPROVED;
            }else{
                $letterOfIndent->status = LetterOfIndent::LOI_STATUS_PARTIAL_APPROVED;
            }
            $letterOfIndent->save();
        }
        $pfi->delete();

        DB::commit();

        return response(true);

    }
    public function getUnitPrice(Request $request) {
      
        $supplier = Supplier::find($request->supplier_id);

        $approvedPfiItems = ApprovedLetterOfIndentItem::where('letter_of_indent_id', $request->letter_of_indent_id)
            ->whereNull('pfi_id')
            ->where('is_pfi_created', true)
            ->get();
        $pendingPfiItems = ApprovedLetterOfIndentItem::where('letter_of_indent_id', $request->letter_of_indent_id)
            ->whereNull('pfi_id')
            ->where('is_pfi_created', false)
            ->get();
        $approvedItemUnitPrices = [];
        $pendingPfiItemUnitPrices = [];
        foreach ($approvedPfiItems as $approvedPfiItem) {
            
            $loiItem = LetterOfIndentItem::find($approvedPfiItem->letter_of_indent_item_id);
           
            if($supplier->is_MMC == true) {
                $price = $loiItem->masterModel->amount_belgium > 0 ?  $loiItem->masterModel->amount_belgium : 0;;
            }else if($supplier->is_AMS == true) {
                $price = $loiItem->masterModel->amount_uae > 0 ? $loiItem->masterModel->amount_uae : 0;;
            }else{
                $price = 0;
            }
            $approvedItemUnitPrices[$approvedPfiItem->id] = $price;

        }
        if($pendingPfiItems->count() > 0) {
            foreach ($pendingPfiItems as $pendingPfiItem) {
              
                $loiItem = LetterOfIndentItem::find($pendingPfiItem->letter_of_indent_item_id);
            
                if($supplier->is_MMC == true) {
                    info("mmc");
                    $price = $loiItem->masterModel->amount_belgium > 0 ?  $loiItem->masterModel->amount_belgium : 0;
                }else if($supplier->is_AMS == true) {
                    info("ams");
                    $price = $loiItem->masterModel->amount_uae > 0 ? $loiItem->masterModel->amount_uae : 0;
                }else{
                    info("no price");
                    $price = 0;
                }
                $pendingPfiItemUnitPrices[$pendingPfiItem->id] = $price;
    
            }
            info($pendingPfiItemUnitPrices);
        }
       
        $data['approvedItemUnitPrices'] = $approvedItemUnitPrices;
        $data['pendingItemUnitPrices'] = $pendingPfiItemUnitPrices;
        return $data;

    }
}
