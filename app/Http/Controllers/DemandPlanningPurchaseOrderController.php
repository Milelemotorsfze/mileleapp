<?php

namespace App\Http\Controllers;

use App\Models\ColorCode;
use App\Models\PfiItemPurchaseOrder;
use App\Models\MasterModel;
use App\Models\PFI;
use App\Models\SupplierInventory;
use App\Models\Supplier;
use App\Models\SupplierType;
use App\Models\Varaint;
use App\Models\PaymentTerms;
use Illuminate\Http\Request;
use App\Models\PfiItem;
use App\Models\Country;
use Illuminate\Support\Facades\DB;
use App\Models\MasterShippingPorts;
use App\Models\PurchasingOrder;


class DemandPlanningPurchaseOrderController extends Controller
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
        (new UserActivityController)->createActivity('Open Purchase Order create Section');
        
        $pfi = Pfi::find($request->id);
        $pfiItemLatest = PfiItem::where('pfi_id', $request->id)
                            ->where('is_parent', false)
                            ->first();
        $isToyotaPO = 0;
        if($pfiItemLatest) {
            // only toyota PFI have child , so if child exist it will be toyota PO
            $isToyotaPO = 1;
        }
        // $dealer =  $pfiItemLatest->letterOfIndentItem->LOI->dealers ?? '';
        $pfiItems = PfiItem::where('pfi_id', $request->id)
                                ->where('is_parent', true)
                                ->get();

        $totalPOqty = 0;
        foreach ($pfiItems as $pfiItem) {

            $alreadyAddedQuantity = PfiItemPurchaseOrder::where('pfi_item_id', $pfiItem->id)
                                                        ->sum('quantity');
            $pfiItem->quantity = $pfiItem->pfi_quantity - $alreadyAddedQuantity;
            $totalPOqty = $totalPOqty + $pfiItem->pfi_quantity;
        
            $masterModel = MasterModel::find($pfiItem->masterModel->id);
            $pfiItem->masterModels = MasterModel::where('model', $masterModel->model)
                                            ->where('sfx', $masterModel->sfx)
                                            ->get();
            // $possibleModelIds = MasterModel::where('model', $masterModel->model)
            //                                 ->where('sfx', $masterModel->sfx)->pluck('id');
            // $pfiItem->inventoryQuantity = SupplierInventory::whereIn('master_model_id', $possibleModelIds)
            //                                             ->whereNull('purchase_order_id')
            //                                             ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
            //                                             ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
            //                                             ->where('supplier_id', $pfi->supplier_id)
            //                                             ->where('whole_sales', $dealer)
            //                                             ->count();
        }
        $exColours = ColorCode::where('belong_to', 'ex')->orderBy('name', 'ASC')->get();
        $intColours = ColorCode::where('belong_to', 'int')->orderBy('name', 'ASC')->get();
        $paymentTerms = PaymentTerms::all();
        $countries = Country::select('id','name')->get();
        return view('purchase-order.create', compact('pfiItems','exColours','isToyotaPO','intColours','pfi',
        'paymentTerms','countries','totalPOqty'));
    }
    // public function checkInventoryColour(Request $request) {
    //     $masterModels = $request->master_model_id;
    //     $pfi = Pfi::findOrFail($request->pfi_id);
    //     $childPfiItemLatest = PfiItem::where('pfi_id', $request->pfi_id)
    //                             ->where('is_parent', false)
    //                             ->first();
    //     $dealer =  $childPfiItemLatest->letterOfIndentItem->LOI->dealers ?? '';
    //     $alreadyAddedIds = [];
    //     $data = [];
    //     foreach($masterModels as $key => $masterModel)
    //     {
    //         // map to inventory
    //         $masterModel = MasterModel::find($masterModel);
    //         $possibleModelIds = MasterModel::where('model', $masterModel->model)
    //                             ->where('sfx', $masterModel->sfx)->pluck('id');

    //         $inventoryItem = SupplierInventory::whereIn('master_model_id', $possibleModelIds)
    //             ->whereNull('purchase_order_id')
    //             ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
    //             ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
    //             ->where('supplier_id', $pfi->supplier_id)
    //             ->whereNotIn('id', $alreadyAddedIds)
    //             ->where('whole_sales', $dealer);
               
    //          // if exterior colour is coming check same colour is existing with inventory
    //         if($request->ex_colours[$key] && $request->int_colours[$key]) {
    //            $inventoryItem->where('exterior_color_code_id', $request->ex_colours[$key])
    //                             ->where('interior_color_code_id', $request->int_colours[$key]);
    //         }
    //         if($inventoryItem->count() <= 0) {
    //             // exact match not found
    //             $data[] = $masterModel->model.'-'.$masterModel->sfx;
    //         }
    //     }
    //     return response($data);
           
    // }
    public function uniqueCheckPONumber(Request $request)
    {
        $poNumber = $request->input('poNumber');
        $existingPO = PurchasingOrder::select('po_number')->where('po_number', $poNumber)->first();
        if($existingPO) {
            return response(true);
        }else{
            return response(false);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
