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
        $useractivities = new UserActivities();
        $useractivities->activity = "Open to View the Payment Terms";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        if ($request->ajax()) {
            $data = PaymentTerms::select([
                'payment_terms.id',
                'payment_terms.name',
                'payment_terms.description',
            ])
            ->leftJoin('milestone', 'milestone.payment_terms_id', '=', 'payment_terms.id')
            ->groupBy('payment_terms.id')
            ->get();
            $formattedData = $data->map(function ($paymentTerm) {
                $milestones = Milestone::where('payment_terms_id', $paymentTerm->id)->get();
                $paymentTerm->milestones = $milestones;
                return $paymentTerm;
            });
            return DataTables::of($formattedData)->toJson();
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
