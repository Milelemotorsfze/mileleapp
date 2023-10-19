<?php

namespace App\Http\Controllers;

use App\Models\DemandList;
use App\Models\LetterOfIndentItem;
use App\Models\MasterModel;
use App\Models\MonthlyDemand;
use App\Models\Supplier;
use App\Models\SupplierInventory;
use App\Models\Varaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Demand;

class DemandController extends Controller
{
    public function index()
    {

    }
    public function create()
    {
        $suppliers = Supplier::with('supplierTypes')
            ->whereHas('supplierTypes', function ($query) {
                $query->where('supplier_type', Supplier::SUPPLIER_TYPE_DEMAND_PLANNING);
            })
            ->where('status', Supplier::SUPPLIER_STATUS_ACTIVE)
            ->get();
        return view('demands.create', compact('suppliers'));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'whole_saler' => 'required',
            'steering' => 'required',
            'supplier_id' => 'required'
        ]);

        $demand = Demand::where('supplier_id',$request->supplier_id)
            ->where('whole_saler', $request->whole_saler)
            ->where('steering', $request->steering)
            ->first();

        if (!$demand) {
            $demand = new Demand();
            $demand->whole_saler = $request->input('whole_saler');
            $demand->steering = $request->input('steering');
            $demand->supplier_id = $request->input('supplier_id');
            $demand->created_by = Auth::id();
            $demand->save();
        }
        return redirect()->route('demands.edit',['demand' => $demand->id]);

    }
    public function edit(string $id)
    {
        $cu = date('n') - 2;
        $ru   = $cu + 4;
        $totalYearlyQuantities = [];
        for($k=$cu;$k<=$ru;$k++) {
            $month = date('M', mktime(0, 0, 0, $k, 10));
            $year = date('y', mktime(0, 0, 0, $k, 10));
            $data = MonthlyDemand::where('demand_id',$id)
                ->where('month',$month)
                ->where('year',$year)
                ->sum('quantity');

            $totalYearlyQuantities[] = $data;
        }

        $demand = Demand::findOrFail($id);
        $demandLists = DemandList::where('demand_id',$id)->get();

        $months = [];
        $years = [];
        $currentMonths = [];
        $currentMonth = date('n') - 2;
        $endMonth = $currentMonth + 4;
        for ($i=$currentMonth; $i<=$endMonth; $i++) {
            $months[] = date('M y', mktime(0,0,0,$i, 1, date('Y')));
            $years[] = date('y', mktime(0,0,0,$i, 1, date('Y')));
            $currentMonths[] = date('M', mktime(0,0,0,$i, 1, date('Y')));
        }
        $addedModelIds = [];
        foreach ($demandLists as $demandList) {
//            $model = MasterModel::where('model', $demandList->model)
//                ->where('sfx', $demandList->sfx)
//                ->first();
            $addedModelIds[] = $demandList->master_model_id;
        }
        $models = MasterModel::whereNotIn('model',$addedModelIds)
                            ->groupBy('model')->get();
        return view('demands.edit', compact('demand','demandLists','models','months','totalYearlyQuantities'));
    }
    public function getSFX(Request $request)
    {
        $supplierInventoriesModels = SupplierInventory::with('masterModel')
            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
            ->whereNull('eta_import')
            ->groupBy('master_model_id')
            ->pluck('master_model_id');

        $data = MasterModel::where('model', $request->model);
            if ($request->module == 'LOI')
            {
                $loiItems = LetterOfIndentItem::where('letter_of_indent_id', $request->letter_of_indent_id)->get();
                $addedModelIds = [];
                foreach ($loiItems as $loiItem) {
                    $addedModelIds[] = $loiItem->master_model_id;
                }
                $data = $data->whereNotIn('id', $addedModelIds)
                            ->whereIn('id', $supplierInventoriesModels);
            }
            if($request->module == 'Demand')
            {
                $demandLists = DemandList::where('demand_id', $request->demand_id)->get();
                $addedModelIds = [];
                foreach ($demandLists as $demandList) {
                    $addedModelIds[] = $demandList->master_model_id;
                }
                $data = $data->whereNotIn('id', $addedModelIds);
            }
            $data = $data->pluck('sfx');

        return $data;
    }
    public function getVariant(Request $request)
    {
        $variantId = MasterModel::where('model', $request->model)
                                    ->where('sfx', $request->sfx)
                                    ->pluck('variant_id');
        $data['variants'] = Varaint::with('master_model_lines','masterModel')->whereIn('id', $variantId)
                                     ->get();


        if ($request->module == 'LOI') {
            $inventory = SupplierInventory::with('masterModel')
                ->whereHas('masterModel', function ($query) use($request) {
                    $query->where('sfx', $request->sfx);
                    $query->where('model', $request->model);
                })
                ->first();
            $data['quantity'] = $inventory->actual_quantity;
        }

        return $data;
    }
}
