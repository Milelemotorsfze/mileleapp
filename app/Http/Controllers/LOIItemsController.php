<?php

namespace App\Http\Controllers;

use App\Models\ApprovedLetterOfIndentItem;
use App\Models\ColorCode;
use App\Models\LetterOfIndent;
use App\Models\LetterOfIndentItem;
use App\Models\MasterModel;
use App\Models\SupplierInventory;
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
            $model = MasterModel::where('model', $loiItem->model)
                ->where('sfx', $loiItem->sfx)
                ->first();
            $addedModelIds[] = $model->id;
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
        $LoiItem->model = $request->model;
        $LoiItem->sfx = $request->sfx;
        $LoiItem->variant_name = $request->variant;
        $LoiItem->quantity = $request->quantity;
        $LoiItem->save();

        if($request->page_name == 'EDIT-PAGE') {
            return redirect()->route('letter-of-indent-items.edit',$request->letter_of_indent_id);

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
            $model = MasterModel::where('model', $loiItem->model)
                ->where('sfx', $loiItem->sfx)
                ->first();
            $addedModelIds[] = $model->id;
        }

        $supplierInventoriesModels = SupplierInventory::with('masterModel')
            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
            ->whereNull('eta_import')
            ->whereNotIn('master_model_id', $addedModelIds)
            ->groupBy('master_model_id')
            ->pluck('master_model_id');

        $models = MasterModel::whereIn('id',$supplierInventoriesModels)->get();

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
    public function updateQuantity(Request $request) {
        $letterOfIndentItem = LetterOfIndentItem::find($request->id);
        $approvedQuantity = $letterOfIndentItem->approved_quantity + $request->quantity;

        $masterModel = MasterModel::where('model', $letterOfIndentItem->model)
            ->where('sfx', $letterOfIndentItem->sfx)->first();
        $quantity = $request->quantity;

        $supplierInventoriesIds = SupplierInventory::where('master_model_id', $masterModel->id)
            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
            ->take($quantity)
            ->pluck('id');
        SupplierInventory::whereIn('id', $supplierInventoriesIds)->update(['veh_status' => SupplierInventory::VEH_STATUS_LOI_APPROVED]);

        DB::beginTransaction();
        $letterOfIndentItem->approved_quantity = $approvedQuantity;
        $letterOfIndentItem->save();

        $approvedLOIItem = new ApprovedLetterOfIndentItem();
        $approvedLOIItem->letter_of_indent_item_id = $request->id;
        $approvedLOIItem->quantity = $request->quantity;
        $approvedLOIItem->created_by = Auth::id();
        $approvedLOIItem->letter_of_indent_id = $letterOfIndentItem->letter_of_indent_id;
        $approvedLOIItem->save();

        $letterOfIndentItems = LetterOfIndentItem::where('letter_of_indent_id', $request->id)->orderBy('id','DESC')->get();
        $loiItemIds = $letterOfIndentItems->pluck('id')->toArray();
        $approvedItems = [];
        $updatedItems = [];

        foreach ($letterOfIndentItems as $key => $letterOfIndentItem)
        {
            $letterOfIndentItem = LetterOfIndentItem::find($letterOfIndentItem->id);
            $latestApprovedQuantity = $letterOfIndentItem->approved_quantity + $request->quantity;
            if ($letterOfIndentItem->quantity == $latestApprovedQuantity && $letterOfIndentItem->latestApprovedQuantity != 0)
            {
                // get id of full quantity approved item and compare with previous ids
                $approvedItems[] = $letterOfIndentItem->id;
            }else{
                // get ids of partialy approved items
                $updatedItems[] = $letterOfIndentItem->id;
            }
        }
        $result = array_diff($loiItemIds,$approvedItems);
        $letterOfIndent = LetterOfIndent::find($letterOfIndentItem->letter_of_indent_id);
        if(empty($result)) {
            $letterOfIndent->status = "Approved";
        }
        if(!empty($updatedItems)) {
            $letterOfIndent->status = LetterOfIndent::LOI_STATUS_PARTIAL_APPROVED;
        }
        $letterOfIndent->save();
        DB::commit();

        return response(true);

    }

    public function approveLOIItem(Request $request) {

        $letterOfIndent = LetterOfIndent::find($request->id);
        $letterOfIndentItems = LetterOfIndentItem::where('letter_of_indent_id', $letterOfIndent->id)->orderBy('id','DESC')->get();
        $quantities = $request->quantities;
        $loiItemIds = $letterOfIndentItems->pluck('id')->toArray();
        $approvedItems = [];

        DB::beginTransaction();

        foreach ($letterOfIndentItems as $key => $letterOfIndentItem)
        {
            $letterOfIndentItem = LetterOfIndentItem::find($letterOfIndentItem->id);
            $letterOfIndentItem->approved_quantity = $letterOfIndentItem->approved_quantity + $quantities[$key];
            $letterOfIndentItem->save();
            if ($letterOfIndentItem->quantity == $letterOfIndentItem->approved_quantity)
            {
                $approvedItems[] = $letterOfIndentItem->id;
            }
        }

        $result = array_diff($loiItemIds,$approvedItems);
        if(empty($result)) {
          $letterOfIndent->status = LetterOfIndent::LOI_STATUS_APPROVED;

        }else{
            $letterOfIndent->status = LetterOfIndent::LOI_STATUS_PARTIAL_APPROVED;
        }
        $letterOfIndent->save();

        foreach ($quantities as $key => $quantity) {
            $approvedLOIItem = new ApprovedLetterOfIndentItem();
            $approvedLOIItem->letter_of_indent_item_id = $letterOfIndentItems[$key]['id'];
            $approvedLOIItem->quantity = $quantity;
            $approvedLOIItem->letter_of_indent_id = $letterOfIndent->id;
            $approvedLOIItem->save();
        }
        DB::commit();

        return redirect()->route('letter-of-indents.index')->with('success', 'LOI Item successfully approved with respective quantity');
    }

}
