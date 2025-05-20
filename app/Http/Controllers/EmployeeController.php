<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\HRM\Employee\EmployeeProfile;
use Exception;

class EmployeeController extends Controller
{
    public function index()
    {
        $activeEmployees = EmployeeProfile::where('current_status','active')->where('type','employee')->orderBy('first_name','ASC')->get();
        $inactiveEmployees = EmployeeProfile::where('current_status','inactive')->where('type','employee')->orderBy('first_name','ASC')->get();
        return view('hrm.employee.index',compact('activeEmployees','inactiveEmployees'));
    }

    public function create()
    {
        return view('hrm.employee_relation.createchecklistform');
    }
    public function uniquePassport(Request $request) {
        $validator = Validator::make($request->all(), [
            'passportNumber' => 'required',
            'candidateId' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            try {
                $passport = EmployeeProfile::whereNot('interview_summary_id',$request->candidateId)->where('passport_number',$request->passportNumber)->get();
                if(count($passport) > 0) {
                    return false;
                }
                else {
                    return true;
                }
           } 
           catch (\Exception $e) {
           }
        }
    }
    public function uniqueCandidateEmpCode(Request $request) {
        $validator = Validator::make($request->all(), [
            'employeeCode' => 'required',
            'employeeId' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            try {
                $passport = EmployeeProfile::whereNot('id',$request->employeeId[0])->where('employee_code',$request->employeeCode)->get();
                if(count($passport) > 0) {
                    return false;
                }
                else {
                    return true;
                }
           } 
           catch (\Exception $e) {
               info($e);
           }
        }
    }
    public function show($id) {
        $data = EmployeeProfile::where('id',$id)->first();
        $previous = EmployeeProfile::where('id', '<', $id)->max('id');
        $next = EmployeeProfile::where('id', '>', $id)->min('id');
        return view('hrm.employee.show',compact('data','previous','next'));
    }
}
