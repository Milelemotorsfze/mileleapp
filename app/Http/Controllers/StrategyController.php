<?php

namespace App\Http\Controllers;

use App\Models\Strategy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\StrategiesDates;
use App\Models\LeadSource;
use App\Http\Requests\StoreStrategyRequest;
use App\Http\Requests\UpdateStrategyRequest;
use Carbon\Carbon;

class StrategyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    $LeadSource = LeadSource::all();
    return view('calls.strategy', compact('LeadSource'));    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'start_date' => 'required',
            'end_date' => $request->input('one_day_activity') === 'auto-assign' ? 'nullable' : 'required',
        ], [
            'start_date.required' => 'Please enter your Current Date.',
            'end_date.required' => 'Please enter your Ending Date.',
        ]);        
        $data = [
            'name' => $request->input('name'),
            'lead_source_id' => $request->input('lead_source_id'),
            'created_by' => Auth::id(),
            'status' => "Active",
        ];
        $model = new Strategy($data);
        $model->save();
        $cost = $request->input('cost');
        $currency = $request->input('currency');
        $combinedValue = $cost . '  ' . $currency;
        $strategies_id = Strategy::where('created_by', Auth::id())
        ->latest('id')
        ->first()
        ->id;
        $start_date = Carbon::createFromFormat('Y-m-d', $request->input('start_date'));
        $isOneDayActivity = $request->input('one_day_activity') === 'auto-assign';
        if ($isOneDayActivity) {
            $end_date = $start_date;
        } else {
            $end_date = Carbon::createFromFormat('Y-m-d', $request->input('end_date'));
        }
        $datas = [
            'strategies_id' => $strategies_id,
            'cost' => $combinedValue,
            'starting_date' => $start_date,
            'ending_date' => $end_date,
        ];
        $model = new StrategiesDates($datas);
        $model->save();
        return redirect()->back()
        ->with('success','New Record Saved');
    }
    /**
     * Display the specified resource.
     */
    public function show(Strategy $strategy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
{
    $strategies = Strategy::where('lead_source_id', $id)->get();
    $strategiesDates = StrategiesDates::whereIn('strategies_id', $strategies->pluck('id'))->get();

    return view('calls.createstrategy', compact('id','strategies', 'strategiesDates'));
}
    
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStrategyRequest $request, Strategy $strategy)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Strategy $strategy)
    {
        //
    }
}
