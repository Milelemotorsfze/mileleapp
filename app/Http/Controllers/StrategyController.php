<?php
namespace App\Http\Controllers;
use App\Models\Strategy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\StrategiesDates;
use App\Models\LeadSource;
use App\Models\CallsPriority;
use App\Http\Requests\StoreStrategyRequest;
use App\Http\Requests\UpdateStrategyRequest;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
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
        $validator = Validator::make($request->all(), [
            'start_date' => 'required',
            'end_date' => $request->input('one_day_activity') === 'auto-assign' ? 'nullable' : 'required|after:start_date',
        ], [
            'start_date.required' => 'Please enter your Current Date.',
            'end_date.required' => 'Please enter your Ending Date.',
            'end_date.after' => 'The Ending Date must be after the Current Date.',
        ]);
        
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }         
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
    public function show($id)
    {
        $strategies = Strategy::where('id', $id)->get();
        $strategiesDates = StrategiesDates::whereIn('strategies_id', $strategies->pluck('id'))->get();
    
        return view('calls.editstrategy', compact('id','strategies', 'strategiesDates'));
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
public function updaters(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'start_date' => 'required',
        'end_date' => $request->input('one_day_activity') === 'auto-assign' ? 'nullable' : 'required|after:start_date',
    ], [
        'start_date.required' => 'Please enter the Current Date.',
        'end_date.required' => 'Please enter the Ending Date.',
        'end_date.after' => 'The Ending Date must be after the Current Date.',
    ]);

    if ($validator->fails()) {
        return back()
            ->withErrors($validator)
            ->withInput();
    }

    $strategy = Strategy::where('id', $id)->first(); // Assuming Strategy is the correct model class name

    $strategy->name = $request->input('name');
    $strategy->save();
    $cost = $request->input('cost');
    $currency = $request->input('currency');
    $combinedValue = $cost . ' ' . $currency;

    $start_date = Carbon::createFromFormat('Y-m-d', $request->input('start_date'));
    $isOneDayActivity = $request->has('one_day_activity');

    if ($isOneDayActivity) {
        $end_date = $start_date;
    } else {
        $end_date = Carbon::createFromFormat('Y-m-d', $request->input('end_date'));
    }

    $strategiesDates = StrategiesDates::where('strategies_id', $id)->first();
    if ($strategiesDates) {
        $strategiesDates->cost = $combinedValue;
        $strategiesDates->starting_date = $start_date;
        $strategiesDates->ending_date = $end_date;
        $strategiesDates->save();
    } 
    return redirect()->back()
        ->with('success', 'Record Updated Successfully');
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        StrategiesDates::where('strategies_id', $id)->delete();
        Strategy::findOrFail($id)->delete();
        return response()->json(['message' => 'Strategy deleted successfully']);
    }
    // In your controller
public function updatePriority(Request $request)
{
    $leadSourceId = $request->input('lead_source_id');
    $priority = $request->input('priority');
    $leadSource = LeadSource::find($leadSourceId);
    $leadSource->priority = $priority;
    $leadSource->save();
    $callpriority = New CallsPriority();
    $callpriority->priority = $priority;
    $callpriority->lead_source_id = $leadSourceId;
    $callpriority->set_by_id = Auth::id();
    $callpriority->save();
    return response()->json(['message' => 'Priority updated successfully']);
}
}
