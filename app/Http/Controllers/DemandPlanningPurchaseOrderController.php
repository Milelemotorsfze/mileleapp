<?php

namespace App\Http\Controllers;

use App\Models\ApprovedLetterOfIndentItem;
use App\Models\ColorCode;
use App\Models\LOIItemPurchaseOrder;
use App\Models\MasterModel;
use App\Models\PFI;
use App\Models\SupplierInventory;
use App\Models\Supplier;
use App\Models\SupplierType;
use App\Models\Varaint;
use App\Models\PaymentTerms;
use Illuminate\Http\Request;
use App\Models\PFIItem;
use Illuminate\Support\Facades\DB;

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
        $dealer = $pfi->letterOfIndent->dealers ?? '';

        // $pfiVehicleVariants = ApprovedLetterOfIndentItem::select('*', DB::raw('sum(quantity) as quantity'))
        //     ->where('pfi_id', $request->id)
        //     ->groupBy('letter_of_indent_item_id')
        //     ->get();
        $pfiItems = PFIItem::where('pfi_id', $request->id)
                                ->where('is_parent', true)
                                ->get();

        foreach ($pfiItems as $pfiItem) {

            $alreadyAddedQuantity = LOIItemPurchaseOrder::where('approved_loi_id', $pfiItem->id)
                                                        ->sum('quantity');

            $pfiItem->quantity = $pfiItem->pfi_quantity - $alreadyAddedQuantity;

            $masterModel = MasterModel::find($pfiItem->masterModel->id);
            $pfiItem->masterModels = MasterModel::where('model', $masterModel->model)
                                            ->where('sfx', $masterModel->sfx)
                                            ->get();

            $possibleModelIds = MasterModel::where('model', $masterModel->model)
                                            ->where('sfx', $masterModel->sfx)->pluck('id');
            $pfiItem->inventoryQuantity = SupplierInventory::whereIn('master_model_id', $possibleModelIds)
                                                        ->whereNull('purchase_order_id')
                                                        ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                                                        ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                                                        ->where('supplier_id', $pfi->supplier_id)
                                                        ->where('whole_sales', $dealer)
                                                        ->count();
        }

        $exColours = ColorCode::where('belong_to', 'ex')->pluck('name', 'id')->toArray();
        $intColours = ColorCode::where('belong_to', 'int')->pluck('name', 'id')->toArray();
        $paymentTerms = PaymentTerms::all();
        return view('purchase-order.create', compact('pfiItems',
            'exColours','intColours','pfi','paymentTerms'));
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
