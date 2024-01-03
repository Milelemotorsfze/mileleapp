<?php

namespace App\Http\Controllers\HRM\OnBoarding;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HRM\Employee\JoiningReport;

class JoiningReportController extends Controller
{
    public function index() {
        $pendings = JoiningReport::where([
            ['joining_type','new_employee'],
            ['action_by_department_head','!=',['approved', 'rejected']]
        ])->get();
        $approved = JoiningReport::where([
            ['joining_type','new_employee'],
            ['action_by_department_head','!=','approved']
        ])->get();
        $rejected = JoiningReport::where([
            ['joining_type','new_employee'],
            ['action_by_department_head','!=','rejected']
        ])->get();
        return view('hrm.onBoarding.joiningReport.index',compact('pendings','approved','rejected'));
    }
}
