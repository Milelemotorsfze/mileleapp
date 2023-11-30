<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Agents;
use Yajra\DataTables\DataTables;
use App\Models\UserActivities;
use App\Models\AgentsCreating;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AgentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $useractivities = new UserActivities();
        $useractivities->activity = "View the Agents Master Details";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        if ($request->ajax()) {
            $status = $request->input('status');
            $searchValue = $request->input('search.value');
            if ($status === "summary") {
                $data = Agents::select([
                    'agents.id',
                    'agents.name',
                    'agents.email',
                    'agents.phone',
                    'agents.created_at',
                    \DB::raw('SUM(agents_commission.commission) as total_commission'),
                    \DB::raw('COUNT(DISTINCT agents_commission.quotation_id) as total_quotations'),
                    \DB::raw('COUNT(DISTINCT agents_commission.so_id) as total_sales_orders'),
                    \DB::raw('GROUP_CONCAT(DISTINCT users.name ORDER BY quotations.created_at DESC SEPARATOR \', \') as created_by_names'),
                ])
                ->leftJoin('agents_commission', 'agents_commission.agents_id', '=', 'agents.id')
                ->leftJoin('quotations', 'quotations.id', '=', 'agents_commission.quotation_id')
                ->leftJoin('users', 'users.id', '=', 'quotations.created_by')
                ->groupBy('agents.id', 'agents.name', 'agents.email', 'agents.phone');
            }
            return Datatables::of($data)
                ->addColumn('action', function ($row) {
                    // Add any additional columns or actions here
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('Agents.index');
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
    // Check if an agent with the same id_number and id_category exists
    $existingAgent = Agents::where('id_number', $request->id_number)
        ->where('id_category', $request->id_category)
        ->first();
    if ($existingAgent) {
        return response()->json([
            'agent_id' => $existingAgent->id,
            'name' => $request->name,
            'phone' => $request->phone,
        ]);
    }
    // Check if an agent with the same name and phone exists
    $existingAgent = Agents::where('name', $request->name)
        ->where('phone', $request->phone)
        ->first();
    if ($existingAgent) {
        return response()->json([
            'agent_id' => $existingAgent->id,
            'name' => $request->name,
            'phone' => $request->phone,
        ]);
    }
    // Check if an agent with the same phone exists
    $existingAgent = Agents::where('phone', $request->phone)->first();
    if ($existingAgent) {
        return response()->json([
            'agent_id' => $existingAgent->id,
            'name' => $request->name,
            'phone' => $request->phone,
        ]);
    }
    // If no existing agent is found, create a new one
    $agent = new Agents;
    $agent->name = $request->name;
    $agent->email = $request->email;
    $agent->phone = $request->phone;
    $agent->id_category = $request->id_category;
    $agent->id_number = $request->id_number;
    if ($request->hasFile('identification_file')) {
        // Process file upload here
    }
    $agent->save();
    $agentcreate = new AgentsCreating;
    $agentcreate->agents_id = $agent->id;
    $agentcreate->created_by = Auth::id();
    $agentcreate->save();
    return response()->json([
        'agent_id' => $agent->id,
        'name' => $agent->name,
        'phone' => $agent->phone,
    ]);
}
    /**
     * Display the specified resource.
     */
    public function show(Agents $agents)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agents $agents)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Agents $agents)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agents $agents)
    {
        //
    }
    public function getAgentNames()
    {
        $userId = auth()->user()->id;
        $agentData = Agents::join('agents_creating', 'agents.id', '=', 'agents_creating.agents_id')
            ->where('agents_creating.created_by', $userId)
            ->select('agents.id', 'agents.name', 'agents.phone')
            ->get();
info($userId);
        return response()->json($agentData);
    }
}
