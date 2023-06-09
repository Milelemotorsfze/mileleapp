<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ApprovedLetterOfIndentItem;
use App\Models\LetterOfIndent;
use App\Models\LetterOfIndentItem;
use App\Models\PFI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PFIController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
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
        $request->validate([
            'pfi_reference_number' => 'required',
            'pfi_date' => 'required',
            'amount'  => 'required',
            'file' => 'required|mimes:pdf'
        ]);

//        DB::beginTransaction();
        $pfi = new PFI();

        $pfi->pfi_reference_number = $request->pfi_reference_number;
        $pfi->pfi_date = $request->pfi_date;
        $pfi->amount = $request->amount;
        $pfi->letter_of_indent_id = $request->letter_of_indent_id;
        $pfi->created_by = Auth::id();
        $pfi->comment = $request->comment;
        $pfi->status = PFI::PFI_STATUS_NEW;
        if ($request->has('file'))
        {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'.'.$extension;
            $destinationPath = 'PFI_document_withoutsign';
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

        if($pfiApprovedQuantity == $letterOfIndent->total_loi_quantity) {
            $letterOfIndent->status = LetterOfIndent::LOI_STATUS_PFI_CREATED;
        }else{
            $letterOfIndent->status = LetterOfIndent::LOI_STATUS_PARTIAL_PFI_CREATED;
        }
        $letterOfIndent->save();

        foreach ($currentlyApprovedItems as $currentlyApprovedItem)
        {
            $approvedLoiItem = ApprovedLetterOfIndentItem::find($currentlyApprovedItem->id);
            $approvedLoiItem->pfi_id = $pfi->id;
            $approvedLoiItem->save();
        }

//        DB::commit();

        return redirect()->route('letter-of-indents.index')->with('message', 'PFI created successfully');
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
