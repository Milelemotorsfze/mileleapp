<?php

namespace App\Http\Controllers;

use App\Models\calls;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ModelHasRoles;
use App\Models\SalesPersonLaugauges;
use Monarobase\CountryList\CountryListFacade;
use Carbon\Carbon;

class CallsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Calls::orderBy('status','DESC')->whereIn('status',['new','active'])->get();
        $convertedleads = Calls::where('status','Converted To Leads')->get();
        return view('calls.index',compact('data','convertedleads'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = CountryListFacade::getList('en');
        return view('calls.create', compact('countries'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
            'source' => 'required',
            'demand' => 'required',
            'location' => 'required',
            'email' => 'required|email|unique:users,email',
        ]);
        $email = $request->input('email');
        $phone = $request->input('phone');
        $language = $request->input('language');
        $sales_persons = ModelHasRoles::where('role_id', 4)->get();
        $sales_person_id = null;
        $existing_email_count = null;
        $existing_phone_count = null;
        $existing_language_count = null;
        
        foreach ($sales_persons as $sales_person) {
            if($language == "English") {
                $existing_email_count = Calls::where('email', $email)
                                             ->where('sales_person', $sales_person->model_id)
                                             ->count();
                $existing_phone_count = Calls::where('phone', $phone)
                                             ->where('sales_person', $sales_person->model_id)
                                             ->count();
                if($existing_email_count != 0 || $existing_phone_count != 0) {
                    $sales_person_id = $sales_person->model_id;
                    break;
                } else {
                    $new_calls_count = Calls::where('status', 'New')
                                             ->where('sales_person', $sales_person->model_id)
                                             ->count();
                    if ($new_calls_count < 5) {
                        $sales_person_id = $sales_person->model_id;
                        break;
                    }
                }
            } else {
                $existing_language_count = SalesPersonLaugauges::where('language', $language)
                                                                ->where('sales_person', $sales_person->model_id)
                                                                ->count(); 
                if($existing_language_count != 0){
                    $sales_person_id = $sales_person->model_id;
                    break;
                } else {
                    $new_calls_count = Calls::where('status', 'New')
                                             ->where('sales_person', $sales_person->model_id)
                                             ->count();
                    if ($new_calls_count < 5) {
                        $sales_person_id = $sales_person->model_id;
                        break;
                    }
                }
            }
        }
        
        if ($sales_person_id == null) {
            $sales_person_id = Calls::select('sales_person', DB::raw('COUNT(*) as count'))
                                     ->where('status', 'New')
                                     ->groupBy('sales_person')
                                     ->orderBy('count', 'ASC')
                                     ->first()
                                     ->sales_person;
        }
        
        // Assign the call to the selected sales persons        
        // create a new Carbon instance with the current time
        $date = Carbon::now();
        // set the timezone to UAE
        $date->setTimezone('Asia/Dubai');
        // format the date and time as desired
        $formattedDate = $date->format('Y-m-d H:i:s');
        $data = [
            'name' => $request->input('name'),
            'demand' => $request->input('demand'),
            'source' => $request->input('source'),
            'email' => $request->input('email'),
            'sales_person' => $sales_person_id,
            'remarks' => $request->input('remarks'),
            'location' => $request->input('location'),
            'phone' => $request->input('phone'),
            'language' => $request->input('language'),
            'created_at' => $formattedDate,
            'created_by' => Auth::id(),
            'status' => "New",
        ];
        $model = new Calls($data);
        $model->save();
        return redirect()->route('calls.index')
        ->with('success','User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(calls $calls)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(calls $calls)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, calls $calls)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(calls $calls)
    {
        //
    }
}
