<?php

namespace App\Http\Controllers\HRM\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HRM\Employee\Separation;
use App\Models\Masters\SeperationTypes;
use App\Models\Masters\SeparationReplacementTypes;

class SeparationController extends Controller
{
    public function create() {
        $employees = User::whereHas('empProfile', function($q) {
            $q = $q->where('type','employee');
        })->with('empProfile.department','empProfile.designation','empProfile.location')->get();
        $separationTypes = SeperationTypes::all();
        $replacementTypes = SeparationReplacementTypes::all();
        return view('hrm.overtime.create',compact('employees','separationTypes','replacementTypes'));
    }
    public function index() {
        $pendings = Separation::where('status','pending');
        if(Auth::user()->hasPermissionForSelectedRole(['list-all-separation-employee-handover'])) {
            $pendings = $pendings->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['list-current-user-separation-handover'])) {
            $pendings = $pendings->where('employee_id',$authId)->latest();
        }
        $pendings =$pendings->get();
        $approved = Separation::where('status','approved');
        if(Auth::user()->hasPermissionForSelectedRole(['list-all-separation-employee-handover'])) {
            $approved = $approved->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['list-current-user-separation-handover'])) {
            $approved = $approved->where('employee_id',$authId)->latest();
        }
        $approved =$approved->get();
        $rejected = Separation::where('status','rejected');
        if(Auth::user()->hasPermissionForSelectedRole(['list-all-separation-employee-handover'])) {
            $rejected = $rejected->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['list-current-user-separation-handover'])) {
            $rejected = $rejected->where('employee_id',$authId)->latest();
        }
        $rejected =$rejected->get();
        return view('hrm.seperation.index',compact('pendings','approved','rejected'));
    }
}
