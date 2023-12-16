<?php

namespace App\Http\Controllers;
use App\Models\SalesPersonStatus;
use App\Models\User;
use App\Models\UserActivities;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SalesPersonStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Open Sales Persons status";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        if ($request->ajax()) { 
            $data = User::select( [
                    'users.name as salespersonname',
                    'sales_person_status.remarks',
                    'sales_person_status.status',
                    'sales_person_status.created_by',
                    DB::raw("DATE_FORMAT(sales_person_status.created_at, '%d-%b-%Y') as created_at"),
                ])
                ->leftJoin('sales_person_status', 'sales_person_status.sale_person_id', '=', 'users.id')
                ->where('selected_role', "7")
                ->groupBy('users.id');
                return DataTables::of($data)
                ->toJson();
        }
        return view('calls.salespersonstatus');
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
