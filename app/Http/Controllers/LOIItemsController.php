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
        $letterOfIndent = LetterOfIndent::find($request->id);

        $letterOfIndentItems = LetterOfIndentItem::where('letter_of_indent_id', $letterOfIndent->id)
                    ->get();

        $addedModelIds = [];
        foreach ($letterOfIndentItems as $loiItem) {
            $addedModelIds[] = $loiItem->master_model_id;
        }

        $supplierInventoriesModels = SupplierInventory::with('masterModel')
            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
            ->whereNull('eta_import')
            ->whereNotIn('master_model_id', $addedModelIds)
            ->groupBy('master_model_id')
            ->pluck('master_model_id');

        $models = MasterModel::whereIn('id',$supplierInventoriesModels)->get();
       // $letterOfIndentItems = LetterOfIndentItem::where('letter_of_indent_id', $request->id)->get();

        return view('letter-of-indent-items.create',compact('letterOfIndent','letterOfIndentItems',
            'models'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'model' => 'required',
            'sfx' => 'required',
            'variant' => 'required',
            'quantity' => 'required',
        ]);

        $LoiItem = new LetterOfIndentItem();

        $LoiItem->letter_of_indent_id  = $request->letter_of_indent_id;
        $variant = Varaint::find($request->variant);
        if($variant) {
            $masterModel = MasterModel::where('sfx', $request->sfx)
                ->where('model', $request->model)
                ->where('variant_id', $variant->id)->first();
        }

        $LoiItem->master_model_id = $masterModel->id ?? '';
        $LoiItem->quantity = $request->quantity;
        $LoiItem->save();

        if($request->page_name == 'EDIT-PAGE') {
            return redirect()->route('letter-of-indent-items.edit', $request->letter_of_indent_id);

        }
        return redirect()->route('letter-of-indent-items.create',['id' => $request->letter_of_indent_id]);
    }

    public function UploadDealDocument(Request $request)
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
        $letterOfIndent = LetterOfIndent::find($id);
        $letterOfIndentItems = LetterOfIndentItem::where('letter_of_indent_id', $id)->get();
        $addedModelIds = [];
        foreach ($letterOfIndentItems as $loiItem) {
            $addedModelIds[] = $loiItem->master_model_id;
        }

        $supplierInventoriesModels = SupplierInventory::with('masterModel')
            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
            ->whereNull('eta_import')
            ->whereNotIn('master_model_id', $addedModelIds)
            ->groupBy('master_model_id')
            ->pluck('master_model_id');

        $models = MasterModel::whereIn('id', $supplierInventoriesModels)->get();

        return view('letter-of-indent-items.edit', compact('letterOfIndent','letterOfIndentItems','models'));
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
        $letterOfIndentItem = LetterOfIndentItem::find($id);
        $letterOfIndentItem->delete();
        return true;
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

            $masterModel = MasterModel::where('model', $letterOfIndentItem->model)
                ->where('sfx', $letterOfIndentItem->sfx)->first();
            // update approved qty in loi item table
            $letterOfIndentItem->approved_quantity = $letterOfIndentItem->approved_quantity + $quantity;
            $letterOfIndentItem->save();

            // add approved details in approvedLOIitem table
            $approvedLOIItem = new ApprovedLetterOfIndentItem();
            $approvedLOIItem->letter_of_indent_item_id = $letterOfIndentId;
            $approvedLOIItem->quantity = $quantity;
            $approvedLOIItem->created_by = Auth::id();
            $approvedLOIItem->letter_of_indent_id = $letterOfIndent->id;
            $approvedLOIItem->save();

            $supplierInventoriesIds = SupplierInventory::where('master_model_id', $masterModel->id)
                ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                ->whereNull('eta_import')
                ->take($quantity)
                ->pluck('id');
            SupplierInventory::whereIn('id', $supplierInventoriesIds)->update(['veh_status' => SupplierInventory::VEH_STATUS_LOI_APPROVED]);
        }

        if($letterOfIndent->total_loi_quantity == $letterOfIndent->total_approved_quantity) {
            $letterOfIndent->status = LetterOfIndent::LOI_STATUS_APPROVED;
        }else{
            $letterOfIndent->status = LetterOfIndent::LOI_STATUS_PARTIAL_APPROVED;
        }

        $letterOfIndent->save();

        DB::commit();

        return redirect()->route('letter-of-indents.index')->with('success', 'LOI Item successfully approved with respective quantity');
    }
    public function supplierApproval(Request $request) {

        $LOI = LetterOfIndent::find($request->id);
        if($request->status == 'REJECTED') {
            $LOI->status = LetterOfIndent::LOI_STATUS_SUPPLIER_REJECTED;
            $LOI->submission_status = LetterOfIndent::LOI_STATUS_SUPPLIER_REJECTED;

        }elseif ($request->status == 'APPROVE') {
            $LOI->status = LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED;
            $LOI->submission_status = LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED;

        }

        $LOI->save();
        return response(true);

    }

}
