<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\SalesTargets;
use App\Models\SalesTargetsLeadTime;
use App\Models\ModelHasRoles;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\UserActivities;

class SalesTargetsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $useractivities = new UserActivities();
    $useractivities->activity = "Open All Sales Target Info Page";
    $useractivities->users_id = Auth::id();
    $useractivities->save();

    if ($request->ajax()) {
        $data = SalesTargets::select([
            'users.name as sales_person_name',
            'sales_targets.month',
            'sales_targets.id',
        ])
        ->join('users', 'users.id', '=', 'sales_targets.sales_person_id')
        ->groupBy('sales_targets.id');

        return DataTables::of($data)
            ->addColumn('formatted_month', function ($row) {
                return Carbon::createFromFormat('Y-m', $row->month)->format('M-Y');
            })
            ->addColumn('lead_time', function ($row) {
                $leadTimes = SalesTargetsLeadTime::where('sales_targets_id', $row->id)->get();
                $html = '<table>';
                foreach ($leadTimes as $leadTime) {
                    $html .= '<tr>';
                    $html .= '<td>' . $leadTime->lead_from . '</td>';
                    $html .= '<td>' . $leadTime->lead_to . '</td>';
                    $html .= '<td>' . $leadTime->leads_days . '</td>';
                    $html .= '</tr>';
                }
                $html .= '</table>';
                return $html;
            })
            ->toJson();
    }
    return view('Targets.sales.index');
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sales_persons = ModelHasRoles::where('role_id', 7)->with('user')->get();
        $useractivities =  New UserActivities();
        $useractivities->activity = "Create New Lead";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        return view('Targets.sales.create', compact('sales_persons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sales_person_id' => 'required',
        ]);
        $salesTarget = new SalesTargets;
        $salesTarget->sales_person_id = $request->input('sales_person_id');
        $salesTarget->walkingleads = $request->input('walkingleads');
        $salesTarget->marketTarget = $request->input('marketTarget');
        $salesTarget->productbasemarketing = $request->input('productbasemarketing');
        $salesTarget->exportsale = $request->input('exportsale');
        $salesTarget->localsale = $request->input('localsale');
        $salesTarget->lease = $request->input('lease');
        $salesTarget->googlereview = $request->input('googlereview');
        $salesTarget->kits = $request->input('kits');
        $salesTarget->shipping = $request->input('shipping');
        $salesTarget->spareparts = $request->input('spareparts');
        $salesTarget->accessiores = $request->input('accessiores');
        $salesTarget->uniquecustomers = $request->input('uniquecustomers');
        $salesTarget->month = $request->input('month');
        $salesTarget->save();
        $leadFromArray = $request->input('leadfrom');
        $leadToArray = $request->input('leadto');
        $leadsDaysArray = $request->input('leadsdays');
        for ($i = 0; $i < count($leadFromArray); $i++) {
            $leadTime = new SalesTargetsLeadTime;
            $leadTime->lead_from = $leadFromArray[$i];
            $leadTime->lead_to = $leadToArray[$i];
            $leadTime->leads_days = $leadsDaysArray[$i];
            $leadTime->sales_targets_id = $salesTarget->id;
            $leadTime->save();
        }
        return redirect()->route('salestargets.index')->with('success', 'Sales Target Setting successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SalesTargets $salesTargets)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalesTargets $salesTargets)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalesTargets $salesTargets)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesTargets $salesTargets)
    {
        //
    }
}
