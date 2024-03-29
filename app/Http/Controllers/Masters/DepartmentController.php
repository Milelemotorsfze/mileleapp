<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Masters\MasterDepartment;
use App\Models\User;

class DepartmentController extends Controller
{
    public function index() {
        $data = MasterDepartment::get();
        return view('hrm.masters.department.index',compact('data'));
    }
    public function edit($id) {
        $previous = $next = '';
        $data = MasterDepartment::where('id',$id)->first();
        $previous = MasterDepartment::where('id', '<', $id)->max('id');
        $next = MasterDepartment::where('id', '>', $id)->min('id');
        $divisionHeads = User::where('status','active')->whereNotIn('id',[1,16])->whereHas('empProfile')->with('empProfile.department','empProfile.designation','empProfile.location')->whereIn('id',[2,26,31,10])->get();
        // return view('hrm.masters.department.edit',compact('data','previous','next','divisionHeads'));
        $errorMsg ="This page will coming very soon !";
        return view('hrm.notaccess',compact('errorMsg'));
    }
    public function update(Request $request, $id) {
        $errorMsg ="This page will coming very soon !";
        return view('hrm.notaccess',compact('errorMsg'));
    }
    public function show($id) {
        $errorMsg ="This page will coming very soon !";
        return view('hrm.notaccess',compact('errorMsg'));
    }
    public function create() {
        $errorMsg ="This page will coming very soon !";
        return view('hrm.notaccess',compact('errorMsg'));
    }
}
