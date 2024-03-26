<?php

namespace App\Http\Controllers\HRM\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HRM\Employee\Insurance;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use DB;
use App\Http\Controllers\UserActivityController;

class InsuranceController extends Controller
{
    public function index() {
        $authId = Auth::id();
        if(Auth::user()->hasPermissionForSelectedRole(['view-ticket-listing-of-current-user'])) {
            $datas = Insurance::where('employee_id',$authId)->get();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['view-ticket-listing'])) {
            $datas = Insurance::all();
        }
        return view('hrm.insurance.index',compact('datas'));
    }
    public function create() {
        $employees = User::whereNotIn('id',[1,16])->whereHas('empProfile', function($q) {
            $q = $q->where('type','employee');
        })->with('empProfile.department','empProfile.designation','empProfile.location')->get();
        return view('hrm.insurance.create',compact('employees'));
    }
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'insurance_policy_number' => 'required',
            'insurance_card_number' => 'required',
            'insurance_policy_start_date' => 'required',
            'insurance_policy_end_date' => 'required',
            'insurance_image' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $authId = Auth::id();
                $input = $request->all();
                if($request->insurance_image) {                       
                    $insuranceFileName = auth()->id() . '_' . time() . '.'. $request->insurance_image->extension();
                    $type = $request->insurance_image->getClientMimeType();
                    $size = $request->insurance_image->getSize();
                    $request->insurance_image->move(public_path('hrm/employee/insurance'), $insuranceFileName);
                    $input['insurance_image'] = $insuranceFileName; 
                }
                $input['created_by'] = $authId; 
                $createRequest = Insurance::create($input);
                (new UserActivityController)->createActivity('Employee Insurance Created');
                $successMessage = "Employee Insurance Created Successfully";                   
                DB::commit();
                return redirect()->route('insurance.index')->with('success',$successMessage);
            }
            catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg ="Something went wrong! Contact your admin";
                return view('hrm.notaccess',compact('errorMsg'));
            }
        }       
    }
    public function edit($id) {
        $employees = User::whereNotIn('id',[1,16])->whereHas('empProfile', function($q) {
            $q = $q->where('type','employee');
        })->with('empProfile.department','empProfile.designation','empProfile.location')->get();
        $data = Insurance::where('id',$id)->with('user.empProfile.department','user.empProfile.designation','user.empProfile.location')->first();
        return view('hrm.insurance.edit',compact('employees','data'));
    }
    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'insurance_policy_number' => 'required',
            'insurance_card_number' => 'required',
            'insurance_policy_start_date' => 'required',
            'insurance_policy_end_date' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $authId = Auth::id();
                $update = Insurance::where('id',$id)->first();
                if($update) {
                    if($request->insurance_image) {                       
                        $insuranceFileName = auth()->id() . '_' . time() . '.'. $request->insurance_image->extension();
                        $type = $request->insurance_image->getClientMimeType();
                        $size = $request->insurance_image->getSize();
                        $request->insurance_image->move(public_path('hrm/employee/insurance'), $insuranceFileName);
                        $update->insurance_image = $insuranceFileName; 
                    }
                    else if($request->is_insurance_delete == 1) {
                        $update->insurance_image = NULL;
                    }
                    $update->employee_id = $request->employee_id;
                    $update->insurance_policy_number = $request->insurance_policy_number;
                    $update->insurance_card_number = $request->insurance_card_number;
                    $update->insurance_policy_start_date = $request->insurance_policy_start_date;
                    $update->insurance_policy_end_date = $request->insurance_policy_end_date;
                    $update->updated_by = $authId;
                    $update->update();
                }
                (new UserActivityController)->createActivity('Employee Insurance Updated');
                $successMessage = "Employee Insurance Updated Successfully";                   
                DB::commit();
                return redirect()->route('insurance.index')->with('success',$successMessage);
            }
            catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg ="Something went wrong! Contact your admin";
                return view('hrm.notaccess',compact('errorMsg'));
            }
        }       
    }
    public function show($id) {
        $data = Insurance::where('id',$id)->first();
        $previous = Insurance::where('id', '<', $id)->max('id');
        $next = Insurance::where('id', '>', $id)->min('id');
        $all = Insurance::where('employee_id',$data->employee_id)->latest('insurance_policy_end_date')->get();
        return view('hrm.insurance.show',compact('data','previous','next','all'));
    }
}
