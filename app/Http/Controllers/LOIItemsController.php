<?php

namespace App\Http\Controllers;

use App\Models\ApprovedLetterOfIndentItem;
use App\Models\ColorCode;
use App\Models\LetterOfIndent;
use App\Models\LetterOfIndentItem;
use App\Models\MasterModel;
use App\Models\SupplierInventory;
use Illuminate\Http\Request;
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
//        return $request->all();
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
            if ($letterOfIndentItem->quantity == $letterOfIndentItem->approved_quantity)
            {
                $approvedItems[] = $letterOfIndentItem->id;
            }
            $letterOfIndentItem->save();
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
            $approvedLOIItem->save();
        }
        DB::commit();

        return redirect()->route('letter-of-indents.index')->with('success', 'LOI Item successfully approved with respective quantity');
    }

}
