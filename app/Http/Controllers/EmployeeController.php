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
        return view('hrm.employee_relation.dashboard');
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
               dd($e);
           }
        }
    }
}
