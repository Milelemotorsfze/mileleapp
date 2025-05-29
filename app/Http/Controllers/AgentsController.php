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
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class AgentsController extends Controller
{
    private const ACTIVITY_VIEW = "View the Agents Master Details";
    private const UPLOAD_PATH = 'agent_document';
    
    protected UserActivities $userActivities;
    protected DataTables $datatables;

    public function __construct(UserActivities $userActivities, DataTables $datatables)
    {
        $this->userActivities = $userActivities;
        $this->datatables = $datatables;
    }

    public function index(Request $request): View|JsonResponse
    {
        $this->logUserActivity();

        if ($request->ajax()) {
            $status = $request->input('status');
            return $this->handleDatatableRequest($status);
        }

        return view('Agents.index');
    }

    private function logUserActivity(): void
    {
        $this->userActivities->create([
            'activity' => self::ACTIVITY_VIEW,
            'users_id' => Auth::id()
        ]);
    }

    private function handleDatatableRequest(?string $status): JsonResponse
    {
        $query = match ($status) {
            'summary' => $this->getSummaryQuery(),
            'quotationwise' => $this->getQuotationWiseQuery(),
            'sowise' => $this->getSoWiseQuery(),
            default => Agents::query(),
        };

        return $this->datatables->of($query)
            ->addColumn('action', function ($row) {
                // Add any additional columns or actions here
                return '';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    private function getSummaryQuery()
    {
        return Agents::select([
            'agents.id',
            'agents.name',
            'agents.email',
            'agents.phone',
            'agents.created_at',
            DB::raw('SUM(agents_commission.commission) as total_commission'),
            DB::raw('COUNT(DISTINCT agents_commission.quotation_id) as total_quotations'),
            DB::raw('COUNT(DISTINCT agents_commission.so_id) as total_sales_orders'),
            DB::raw('GROUP_CONCAT(DISTINCT users.name ORDER BY quotations.created_at DESC SEPARATOR \', \') as created_by_names'),
        ])
        ->leftJoin('agents_commission', 'agents_commission.agents_id', '=', 'agents.id')
        ->leftJoin('quotations', 'quotations.id', '=', 'agents_commission.quotation_id')
        ->leftJoin('users', 'users.id', '=', 'quotations.created_by')
        ->groupBy('agents.id', 'agents.name', 'agents.email', 'agents.phone');
    }

    private function getQuotationWiseQuery()
    {
        return Agents::select([
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

    private function getSoWiseQuery()
    {
        return Agents::select([
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

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'id_category' => 'nullable|string',
            'id_number' => 'nullable|string',
            'identification_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $existingAgent = $this->findExistingAgent($request);

        if ($existingAgent) {
            $this->createAgentCreating($existingAgent->id);
            return $this->agentResponse($existingAgent);
        }

        $agent = $this->createNewAgent($request);
        $this->createAgentCreating($agent->id);

        return $this->agentResponse($agent);
    }

    private function findExistingAgent(Request $request): ?Agents
    {
        if ($request->id_number) {
            $agent = Agents::where('id_number', $request->id_number)
                ->where('id_category', $request->id_category)
                ->first();
            if ($agent) return $agent;
        }

        if ($request->email) {
            $agent = Agents::where('email', $request->email)->first();
            if ($agent) return $agent;
        }

        return Agents::where('name', $request->name)
            ->orWhere('phone', $request->phone)
            ->first();
    }

    private function createNewAgent(Request $request): Agents
    {
        $agent = new Agents();
        $agent->fill($request->only(['name', 'email', 'phone', 'id_category', 'id_number']));

        if ($request->hasFile('identification_file')) {
            $agent->identification_file = $this->handleFileUpload($request->file('identification_file'));
        }

        $agent->save();
        return $agent;
    }

    private function handleFileUpload($file): string
    {
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = self::UPLOAD_PATH . '/' . $fileName;
        $file->move(public_path(self::UPLOAD_PATH), $fileName);
        return $filePath;
    }

    private function createAgentCreating(int $agentId): void
    {
        AgentsCreating::create([
            'agents_id' => $agentId,
            'created_by' => Auth::id()
        ]);
    }

    private function agentResponse(Agents $agent): JsonResponse
    {
        return response()->json([
            'agent_id' => $agent->id,
            'name' => $agent->name,
            'phone' => $agent->phone,
        ]);
    }

    public function getAgentNames(): JsonResponse
    {
        $query = Agents::select('agents.id', 'agents.name', 'agents.phone');
        
        if (!auth()->user()->hasPermissionForSelectedRole('sales-support-full-access')) {
            $query->join('agents_creating', 'agents.id', '=', 'agents_creating.agents_id')
                  ->where('agents_creating.created_by', auth()->id());
        }

        return response()->json($query->get());
    }
}
