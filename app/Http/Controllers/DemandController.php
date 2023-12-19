<?php

namespace App\Http\Controllers;

use App\Models\DemandList;
use App\Models\LetterOfIndent;
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
        (new UserActivityController)->createActivity('Open Demand List Section');

        $demands = Demand::orderBy('id','DESC')->get();
        return view('demands.index', compact('demands'));
    }
    public function create()
    {
        (new UserActivityController)->createActivity('Open Demand Create Section');

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
        (new UserActivityController)->createActivity('New Demand Created');

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
        (new UserActivityController)->createActivity('Open Demand Edit Page');

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
        info($request->all());
            if ($request->module == 'LOI')
            {
//                $masterModelIds = LetterOfIndentItem::where('letter_of_indent_id', $request->letter_of_indent_id)->pluck('master_model_id')->toArray();
//                $similarModelIds = MasterModel::where('model', $request->model)
//                    ->pluck('id')->toArray();
                $alreadyaddedModelIds = [];
//                if($similarModelIds) {
//                    foreach ($similarModelIds as $similarModelId) {
//                        if(in_array($similarModelId,$masterModelIds)) {
//                            array_push($alreadyaddedModelIds, $similarModelId);
//                        }
//                    }
//                }
            }
            if($request->module == 'Demand')
            {
                $demandLists = DemandList::where('demand_id', $request->demand_id)->get();
                $alreadyaddedModelIds = [];
                foreach ($demandLists as $demandList) {
                    $alreadyaddedModelIds[] = $demandList->master_model_id;
                }

            }
            $data = MasterModel::where('model', $request->model)->whereNotIn('id', $alreadyaddedModelIds)->groupBy('sfx')->pluck('sfx');

        return $data;
    }
    public function getModelYear(Request $request) {
        $LOI = LetterOfIndent::find($request->letter_of_indent_id);
//        $masterModelIds = $LOI->letterOfIndentItems->pluck('master_model_id')->toArray();
//            $similarModelIds = MasterModel::where('model', $request->model)
//                ->where('sfx', $request->sfx)
//                ->pluck('id')->toArray();
            $alreadyaddedModelIds = [];
//            if($similarModelIds) {
//                foreach ($similarModelIds as $similarModelId) {
//                   if(in_array($similarModelId,$masterModelIds)) {
//                       array_push($alreadyaddedModelIds, $similarModelId);
//                   }
//                }
//            }
        $data = MasterModel::where('model', $request->model)
            ->where('sfx', $request->sfx)
            ->whereNotIn('id', $alreadyaddedModelIds)
            ->pluck('model_year');

        return $data;
    }
    public function getLOIDescription(Request $request)
    {
//        $letterOfIndent = LetterOfIndent::find($request->letter_of_indent_id);

        $masterModel = MasterModel::where('model', $request->model)
                                    ->where('sfx', $request->sfx)
                                    ->where('model_year', $request->model_year)->first();

        if($request->dealer == 'Milele Motors') {
            $data['loi_description'] = $masterModel->milele_loi_description;
        }else{
            $data['loi_description'] = $masterModel->transcar_loi_description;
        }

        if ($request->module == 'LOI') {
            $inventory = SupplierInventory::with('masterModel')
                ->whereHas('masterModel', function ($query) use($request) {
                    $query->where('sfx', $request->sfx);
                    $query->where('model', $request->model);
                    $query->where('model_year', $request->model_year);
                })
                ->first();
            if($inventory) {
                $data['quantity'] = $inventory->actual_quantity;
            }else{
                $data['quantity'] = 0;
            }
        }

        return $data;
    }
}
