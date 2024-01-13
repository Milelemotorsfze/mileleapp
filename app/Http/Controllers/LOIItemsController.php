<?php

namespace App\Http\Controllers;

use App\Models\ApprovedLetterOfIndentItem;
use App\Models\ColorCode;
use App\Models\LetterOfIndent;
use App\Models\LetterOfIndentItem;
use App\Models\MasterModel;
use App\Models\SupplierInventory;
use App\Models\Varaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LOIItemsController extends Controller
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

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

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

    }
    public function mileleApproval(Request $request)
    {
        $letterOfIndent = LetterOfIndent::find($request->id);
        $letterOfIndentItems = LetterOfIndentItem::where('letter_of_indent_id', $letterOfIndent->id)->orderBy('id','DESC')->get();

        return view('letter_of_indents.approvals.milele_approval', compact('letterOfIndent','letterOfIndentItems'));
    }
    public function approveLOIItem(Request $request) {

        $letterOfIndent = LetterOfIndent::find($request->id);
        $letterOfIndentItems = LetterOfIndentItem::where('letter_of_indent_id', $letterOfIndent->id)->orderBy('id','DESC')->get();
        $quantities = $request->quantities;

        DB::beginTransaction();
        // loi status change based on approval quantity

        foreach ($quantities as $key => $quantity) {
            $letterOfIndentId = $letterOfIndentItems[$key]['id'];
            $letterOfIndentItem = LetterOfIndentItem::find($letterOfIndentId);

//            $masterModel = MasterModel::where('model', $letterOfIndentItem->model)
//                ->where('sfx', $letterOfIndentItem->sfx)->first();
            // update approved qty in loi item table
            $letterOfIndentItem->approved_quantity = $letterOfIndentItem->approved_quantity + $quantity;
            $letterOfIndentItem->save();

            // add approved details in approvedLOIitem table
            if($quantity > 0) {
                $approvedLOIItem = new ApprovedLetterOfIndentItem();
                $approvedLOIItem->letter_of_indent_item_id = $letterOfIndentId;
                $approvedLOIItem->quantity = $quantity;
                $approvedLOIItem->created_by = Auth::id();
                $approvedLOIItem->letter_of_indent_id = $letterOfIndent->id;
                $approvedLOIItem->save();
            }
            // Supplier inventory will be unlisted when DELIVERY NOTE (DN) came
            // temperorly change the status of supplier inventory , and consider this status to inventory count.
            $masterModel = MasterModel::find($letterOfIndentItem->master_model_id);
            $masterModelIds = MasterModel::where('steering', $masterModel->steering)
                ->where('model', $masterModel->model)
                ->where('sfx', $masterModel->sfx)->pluck('id')->toArray();
            $supplierInventoriesIds = SupplierInventory::whereIn('master_model_id', $masterModelIds)
                ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                ->whereNull('eta_import')
                ->whereNull('status')
                ->take($quantity)
                ->pluck('id');

            SupplierInventory::whereIn('id', $supplierInventoriesIds)->update(['status' => SupplierInventory::VEH_STATUS_LOI_APPROVED]);
        }

        if($letterOfIndent->total_loi_quantity == $letterOfIndent->total_approved_quantity) {
            $letterOfIndent->status = LetterOfIndent::LOI_STATUS_APPROVED;
        }else{
            $letterOfIndent->status = LetterOfIndent::LOI_STATUS_PARTIAL_APPROVED;
        }

        $letterOfIndent->save();

        DB::commit();

        return redirect()->route('letter-of-indents.index')->with('success', 'LOI Item successfully updated with respective quantity');
    }
    public function supplierApproval(Request $request) {

        $LOI = LetterOfIndent::find($request->id);
        if($request->status == 'REJECTED') {
            $LOI->status = LetterOfIndent::LOI_STATUS_SUPPLIER_REJECTED;
            $LOI->submission_status = LetterOfIndent::LOI_STATUS_SUPPLIER_REJECTED;
            $LOI->review = $request->review;

        }elseif ($request->status == 'APPROVE') {
            $LOI->status = LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED;
            $LOI->submission_status = LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED;

        }

        $LOI->save();
        return response(true);

    }

}
