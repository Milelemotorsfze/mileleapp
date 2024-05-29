<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Agents;
use Yajra\DataTables\DataTables;
use App\Models\UserActivities;
use App\Models\AgentsCreating;
use App\Models\AgentCommission;
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
            if ($status === "quotationwise") {
                $data = Agents::select([
                    'agents.id',
                    'agents.name',
                    'agents.email',
                    'agents.phone',
                    'agents_commission.commission',
                    'agents_commission.quotation_id',
                    'users.name as names',
                ])
                ->leftJoin('agents_commission', 'agents_commission.agents_id', '=', 'agents.id')
                ->leftJoin('users', 'users.id', '=', 'agents_commission.created_by')
                ->where('agents_commission.status', "Quotation")
                ->groupBy('agents_commission.id');
            }
            if ($status === "sowise") {
                $data = Agents::select([
                    'agents.id',
                    'agents.name',
                    'agents.email',
                    'agents.phone',
                    'agents_commission.commission',
                    'agents_commission.quotation_id',
                    'agents_commission.so_id',
                    'users.name as names',
                ])
                ->leftJoin('agents_commission', 'agents_commission.agents_id', '=', 'agents.id')
                ->leftJoin('users', 'users.id', '=', 'agents_commission.created_by')
                ->where('agents_commission.status', "SO")
                ->groupBy('agents_commission.id');
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
    $existingAgent = null;
    if ($request->id_number != null) {
        $existingAgent = Agents::where('id_number', $request->id_number)
            ->where('id_category', $request->id_category)
            ->first();
    }
    if ($existingAgent == null && $request->email != null) {
        $existingAgent = Agents::where('email', $request->email)->first();
    }
    if ($existingAgent == null) {
        $existingAgent = Agents::where('name', $request->name)
            ->orWhere('phone', $request->phone)
            ->first();
    }
    if ($existingAgent) {
        $agentcreate = new AgentsCreating;
        $agentcreate->agents_id = $existingAgent->id;
        $agentcreate->created_by = Auth::id();
        $agentcreate->save();

        return response()->json([
            'agent_id' => $existingAgent->id,
            'name' => $existingAgent->name,
            'phone' => $existingAgent->phone,
        ]);
    }
    $agent = new Agents;
    $agent->name = $request->name;
    $agent->email = $request->email;
    $agent->phone = $request->phone;
    $agent->id_category = $request->id_category;
    $agent->id_number = $request->id_number;

    if ($request->hasFile('identification_file')) {
        $file = $request->file('identification_file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = 'agent_document/' . $fileName;
        $file->move(public_path('agent_document'), $fileName);
        $agent->identification_file = $filePath;
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
        $hasPermission = auth()->user()->hasPermissionForSelectedRole('sales-support-full-access');
        $agentData = Agents::select('agents.id', 'agents.name', 'agents.phone');
    if (!$hasPermission) {
        $agentData->join('agents_creating', 'agents.id', '=', 'agents_creating.agents_id')
                  ->where('agents_creating.created_by', $userId);
    }

    $agentData = $agentData->get();
        return response()->json($agentData);
    }
}
