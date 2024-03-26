<?php

namespace App\Http\Controllers\HRM\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HRM\Employee\BirthdayGift;
use App\Models\User;
use Validator;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserActivityController;

class BirthDayGiftPOController extends Controller
{
    public function index() {
        $datas = BirthdayGift::all();
        return view('hrm.birthDayGiftPO.index',compact('datas'));
    }
    public function create() {
        $employees = User::whereNotIn('id',[1,16])->whereHas('empProfile', function($q) {
            $q = $q->where('type','employee');
        })->with('empProfile.department','empProfile.designation','empProfile.location')->get();
        return view('hrm.birthDayGiftPO.create',compact('employees'));
    }
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'po_year' => 'required',
            'po_number' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $authId = Auth::id();
                $input = $request->all();
                $input['created_by'] = $authId; 
                $createRequest = BirthdayGift::create($input);
                (new UserActivityController)->createActivity('Employee Birthday Gift PO Created');
                $successMessage = "Employee Birthday Gift PO Created Successfully";                   
                DB::commit();
                return redirect()->route('birthday_gift.index')->with('success',$successMessage);
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
        $data = BirthdayGift::where('id',$id)->with('user.empProfile.department','user.empProfile.designation','user.empProfile.location')->first();
        return view('hrm.birthDayGiftPO.edit',compact('employees','data'));
    }
    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'po_year' => 'required',
            'po_number' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $authId = Auth::id();
                $update = BirthdayGift::where('id',$id)->first();
                if($update) {
                    $update->employee_id = $request->employee_id;
                    $update->po_year = $request->po_year;
                    $update->po_number = $request->po_number;
                    $update->updated_by = $authId;
                    $update->update();
                }
                (new UserActivityController)->createActivity('Employee Birthday Gift PO Updated');
                $successMessage = "Employee Birthday Gift PO Updated Successfully";                   
                DB::commit();
                return redirect()->route('birthday_gift.index')->with('success',$successMessage);
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
        $data = BirthdayGift::where('id',$id)->first();
        $previous = BirthdayGift::where('id', '<', $id)->max('id');
        $next = BirthdayGift::where('id', '>', $id)->min('id');
        $all = BirthdayGift::where('employee_id',$data->employee_id)->latest('po_year')->get();
        return view('hrm.birthDayGiftPO.show',compact('data','previous','next','all'));
    }
}
