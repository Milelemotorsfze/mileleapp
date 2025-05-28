<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentTerms;
use App\Models\Milestone;
use App\Models\UserActivities;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Facades\DataTables; 

class PaymentTermsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        (new UserActivityController)->createActivity('Open to View the Payment Terms');
        
        $data = PaymentTerms::with([
                'milestones'  => function ($query) {
                    $query->select('id','type','percentage','payment_terms_id');
                    }
                ]);

        if (request()->ajax()) {
            return DataTables::of($data)
                ->filterColumn('payment_milestone', function($query, $keyword) {
                    $query->whereHas('milestones', function ($q) use ($keyword) {
                        $q->whereRaw("CONCAT(type, ': ', percentage, '%') LIKE ?", ["%{$keyword}%"]);
                    });
                })
                ->addColumn('payment_milestone', function($query) {
                    if ($query->milestones->isNotEmpty()) {
                        return $query->milestones->map(function ($milestone) {
                            return $milestone->type . ': ' . $milestone->percentage . '%';
                        })->implode('<br>'); 
                    }
                    return " ";
                
                })
                ->rawColumns(['payment_milestone'])
                ->toJson();
            }
        return view('purchase-order.paymentterms');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('purchase-order.createpaymentterms');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $useractivities = new UserActivities();
        $useractivities->activity = "Save New Payment Term";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $paymentTerm = new PaymentTerms;
        $paymentTerm->name = $request->input('name');
        $paymentTerm->description = $request->input('description');
        $paymentTerm->save();
        foreach ($request->input('milestones') as $milestoneName => $percentage) {
            if($percentage){
            $milestone = new Milestone;
            $milestone->type = $milestoneName;
            $milestone->percentage = $percentage;
            $milestone->payment_terms_id = $paymentTerm->id;
            $milestone->save();
            }
        }
        return redirect()->route('paymentterms.index')->with('success', 'Payment Term Added Successfully');
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
