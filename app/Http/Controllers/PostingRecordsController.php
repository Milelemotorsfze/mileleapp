<?php

namespace App\Http\Controllers;

use App\Models\posting_records;
use App\Models\UserActivities;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PostingRecordsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Open Pre Order View Purchasing";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        if ($request->ajax()) {
            $status = $request->input('status');
            $searchValue = $request->input('search.value');
            if($status === "Pending")
            {
                $preorders = PreOrdersItems::select([
                    'pre_orders.id as pre_order_number',
                    'pre_orders.status',
                    'pre_orders_items.id',
                    'so.so_number',
                    'so.notes',
                    'master_model_lines.model_line as model_line',
                    'pre_orders_items.qty',
                    'pre_orders_items.description',
                    'countries.name as countryname',
                    'color_codes_exterior.name as exterior', 
                    'color_codes_interior.name as interior', 
                    'pre_orders_items.modelyear',
                    'brands.brand_name',
                    'users.name as salesperson'
                ])
                ->leftJoin('pre_orders', 'pre_orders_items.preorder_id', '=', 'pre_orders.id')
                ->leftJoin('so', 'pre_orders.quotations_id', '=', 'so.quotation_id')
                ->leftJoin('users', 'pre_orders.requested_by', '=', 'users.id')
                ->leftJoin('master_model_lines', 'pre_orders_items.master_model_lines_id', '=', 'master_model_lines.id')
                ->leftJoin('brands', 'master_model_lines.brand_id', '=', 'brands.id')
                ->leftJoin('countries', 'pre_orders_items.countries_id', '=', 'countries.id')
                ->leftJoin('color_codes as color_codes_exterior', 'pre_orders_items.ex_colour', '=', 'color_codes_exterior.id') // distinct alias for exterior color
                ->leftJoin('color_codes as color_codes_interior', 'pre_orders_items.int_colour', '=', 'color_codes_interior.id') // distinct alias for interior color
                ->where('pre_orders_items.status', 'Approved')
                ->groupby('pre_orders_items.id');
            }
                return DataTables::of($preorders)
                ->toJson();
        }
        return view('preorder.index');
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
    public function show(posting_records $posting_records)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(posting_records $posting_records)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, posting_records $posting_records)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(posting_records $posting_records)
    {
        //
    }
}
