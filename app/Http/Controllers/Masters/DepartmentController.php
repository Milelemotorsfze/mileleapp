<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Masters\MasterDepartment;
use App\Models\User;

class DepartmentController extends Controller
{
    public function index() {
        $data = MasterDepartment::orderBy('name', 'ASC')->whereNot('name','Management')->get();
        return view('hrm.masters.department.index',compact('data'));
    }
    public function edit($id) {
        $previous = $next = '';
        $data = MasterDepartment::whereNot('name','Management')->where('id',$id)->first();
        $previous = MasterDepartment::whereNot('name','Management')->where('id', '<', $id)->max('id');
        $next = MasterDepartment::whereNot('name','Management')->where('id', '>', $id)->min('id');
        $divisionHeads = User::orderBy('name', 'ASC')->where('status','active')->whereNotIn('id',[1,16])->whereHas('empProfile')->with('empProfile.department','empProfile.designation','empProfile.location')->whereIn('id',[2,26,31,10])->get();
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
