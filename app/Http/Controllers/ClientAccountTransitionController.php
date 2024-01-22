<?php

namespace App\Http\Controllers;

use App\Models\clientAccountTransition;
use App\Models\Clients;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Models\ClientAccount;
use Illuminate\Http\Request;

class ClientAccountTransitionController extends Controller
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
    public function create()
    {
        //
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
    public function show(clientAccountTransition $clientAccountTransition)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(clientAccountTransition $clientAccountTransition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, clientAccountTransition $clientAccountTransition)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(clientAccountTransition $clientAccountTransition)
    {
        //
    }
    public function clienttransitionsview(Request $request, $client_id)
{
    if ($request->ajax()) {
        $data = ClientAccountTransition::select([
            'client_account_transition.id',
            DB::raw("DATE_FORMAT(CONVERT_TZ(client_account_transition.created_at, '+00:00', '+03:00'), '%e %b %Y - %h:%i %p') AS formatted_created_at"),
                'client_account_transition.transition_type',
                'client_account_transition.amount',
                'client_account_transition.currency',
                'client_account_transition.remarks',
                'users.name',
            ])
            ->leftJoin('client_account', 'client_account_transition.client_account_id', '=', 'client_account.id')
            ->leftJoin('clients', 'client_account.clients_id', '=', 'clients.id')
            ->leftJoin('users', 'client_account_transition.created_by', '=', 'users.id')
            ->where('clients.id', $client_id)
            ->get();

        return DataTables::of($data)->toJson();
    }

    // Pass the $client variable to the view
    $client = Clients::find($client_id);
    return view('clients.accountinfo', compact('client'));
}
}
