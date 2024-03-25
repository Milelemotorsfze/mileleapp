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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//         dd($request->all());
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
        $pfi->payment_status = PFI::PFI_PAYMENT_STATUS_UNPAID;

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

        $currentlyApprovedItems = $request->selectedIds;
        $letterOfIndent = LetterOfIndent::find($request->letter_of_indent_id);
        $pfiApprovedQuantity = ApprovedLetterOfIndentItem::whereIn('id', $currentlyApprovedItems)->sum('quantity');

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
            $approvedLoiItem = ApprovedLetterOfIndentItem::find($currentlyApprovedItem);
            $approvedLoiItem->is_pfi_created = true;
            $approvedLoiItem->pfi_id = $pfi->id;
            $approvedLoiItem->unit_price = $request->unit_price[$key];
            $approvedLoiItem->save();
        }
        try {
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
        }catch (\Exception $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }
        $pfi->save();

        DB::commit();

        return redirect()->route('pfi.index')->with('message', 'PFI created Successfully');
    }
    public function uniqueCheckPfiReferenceNumber(Request $request) {
//         return $request->all();
        $pfi = PFI::where('pfi_reference_number', $request->pfi_reference_number)
                ->whereYear('created_at', Carbon::now()->year)->first();
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

        foreach ($pendingPfiItems as $pendingPfiItem) {
            $loiItem = LetterOfIndentItem::find($pendingPfiItem->letter_of_indent_item_id);

            if($pfi->supplier->is_MMC == true) {
                $price = $loiItem->masterModel->amount_belgium > 0 ?  $loiItem->masterModel->amount_belgium : 0;
            }else if($pfi->supplier->is_AMS == true) {
                $price = $loiItem->masterModel->amount_uae > 0 ? $loiItem->masterModel->amount_uae : 0;
            }else{
                $price = 0;
            }
           $pendingPfiItem->unit_price = $price;
        }

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

        $currentlyApprovedItems = $request->selectedIds;

        $letterOfIndent = LetterOfIndent::find($pfi->letter_of_indent_id);

        $pfiApprovedQuantity = ApprovedLetterOfIndentItem::whereIn('id', $currentlyApprovedItems)->sum('quantity');

        // status change in LOI table by checking quantity of pfi created untill now
        if($pfiApprovedQuantity == $letterOfIndent->total_loi_quantity) {
            $letterOfIndent->status = LetterOfIndent::LOI_STATUS_PFI_CREATED;
        }else{
            $letterOfIndent->status = LetterOfIndent::LOI_STATUS_PARTIAL_PFI_CREATED;
        }
        $letterOfIndent->save();

        // make all existing items as new items
        $existingPfiItems = ApprovedLetterOfIndentItem::where('pfi_id', $id)
            ->where('is_pfi_created', true)
            ->get();
        foreach ($existingPfiItems as $pfiItem) {
            $pfiItem->pfi_id = null;
            $pfiItem->is_pfi_created = false;
            $pfiItem->unit_price = 0;
            $pfiItem->save();
        }
        // update pfiId FOR EACH ADDED ITEM
        foreach ($currentlyApprovedItems as $key => $currentlyApprovedItem)
        {
            $approvedLoiItem = ApprovedLetterOfIndentItem::find($currentlyApprovedItem);
            $approvedLoiItem->pfi_id = $pfi->id;
            $approvedLoiItem->is_pfi_created = true;
            $approvedLoiItem->unit_price = $request->unit_price[$key];
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

        $pendingPfiItems = ApprovedLetterOfIndentItem::where('letter_of_indent_id', $request->letter_of_indent_id)
            ->whereNull('pfi_id')
            ->where('is_pfi_created', false)
            ->get();
        $approvedItemUnitPrices = [];
        $pendingPfiItemUnitPrices = [];
        if($request->action == 'EDIT') {
            $approvedPfiItems = ApprovedLetterOfIndentItem::where('pfi_id', $request->pfi_id)
                ->where('is_pfi_created', true)
                ->get();
            foreach ($approvedPfiItems as $approvedPfiItem) {

                $loiItem = LetterOfIndentItem::find($approvedPfiItem->letter_of_indent_item_id);

                if($supplier->is_MMC == true) {
                    $price = $loiItem->masterModel->amount_belgium > 0 ?  $loiItem->masterModel->amount_belgium : 0;
                }else if($supplier->is_AMS == true) {
                    $price = $loiItem->masterModel->amount_uae > 0 ? $loiItem->masterModel->amount_uae : 0;
                }else{
                    $price = 0;
                }
                $approvedItemUnitPrices[$approvedPfiItem->id] = $price;
            }
        }

        if($pendingPfiItems->count() > 0) {
            foreach ($pendingPfiItems as $pendingPfiItem) {

                $loiItem = LetterOfIndentItem::find($pendingPfiItem->letter_of_indent_item_id);

                if($supplier->is_MMC == true) {
                    $price = $loiItem->masterModel->amount_belgium > 0 ?  $loiItem->masterModel->amount_belgium : 0;
                }else if($supplier->is_AMS == true) {
                    $price = $loiItem->masterModel->amount_uae > 0 ? $loiItem->masterModel->amount_uae : 0;
                }else{
                    $price = 0;
                }
                $pendingPfiItemUnitPrices[$pendingPfiItem->id] = $price;
            }
        }

        $data['approvedItemUnitPrices'] = $approvedItemUnitPrices;
        $data['pendingItemUnitPrices'] = $pendingPfiItemUnitPrices;
        return $data;

    }
    public function paymentStatusUpdate(Request $request, $id) {
        info($id);
        $pfi = PFI::find($id);
        $pfi->payment_status = $request->payment_status;
        $pfi->save();
        return redirect()->back()->with('success', 'Payment Status Updated Successfully.');
    }
}
