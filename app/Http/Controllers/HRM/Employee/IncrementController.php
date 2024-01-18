<?php

namespace App\Http\Controllers\HRM\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HRM\Employee\Increment;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use DB;
use App\Http\Controllers\UserActivityController;

class IncrementController extends Controller
{
    public function index() {
        $authId = Auth::id();
        if(Auth::user()->hasPermissionForSelectedRole(['view-ticket-listing-of-current-user'])) {
            $datas = Increment::where('employee_id',$authId)->get();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['view-ticket-listing'])) {
            $datas = Increment::all();
        }
        return view('hrm.increment.index',compact('datas'));
    }
    public function create() {
        $employees = User::whereHas('empProfile', function($q) {
            $q = $q->where('type','employee');
        })->with('empProfile.department','empProfile.designation','empProfile.location')->get();
        return view('hrm.increment.create',compact('employees'));
    }
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'increment_policy_number' => 'required',
            'increment_card_number' => 'required',
            'increment_policy_start_date' => 'required',
            'increment_policy_end_date' => 'required',
            'increment_image' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $authId = Auth::id();
                $input = $request->all();
                if($request->increment_image) {                       
                    $incrementFileName = auth()->id() . '_' . time() . '.'. $request->increment_image->extension();
                    $type = $request->increment_image->getClientMimeType();
                    $size = $request->increment_image->getSize();
                    $request->increment_image->move(public_path('hrm/employee/increment'), $incrementFileName);
                    $input['increment_image'] = $incrementFileName; 
                }
                $input['created_by'] = $authId; 
                $createRequest = Increment::create($input);
                (new UserActivityController)->createActivity('Employee increment Created');
                $successMessage = "Employee increment Created Successfully";                   
                DB::commit();
                return redirect()->route('increment.index')->with('success',$successMessage);
            }
            catch (\Exception $e) {
                DB::rollback();
                dd($e);
            }
        }       
    }
    public function edit($id) {
        $employees = User::whereHas('empProfile', function($q) {
            $q = $q->where('type','employee');
        })->with('empProfile.department','empProfile.designation','empProfile.location')->get();
        $data = Increment::where('id',$id)->with('user.empProfile.department','user.empProfile.designation','user.empProfile.location')->first();
        return view('hrm.increment.edit',compact('employees','data'));
    }
    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'increment_policy_number' => 'required',
            'increment_card_number' => 'required',
            'increment_policy_start_date' => 'required',
            'increment_policy_end_date' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $authId = Auth::id();
                $update = Increment::where('id',$id)->first();
                if($update) {
                    if($request->increment_image) {                       
                        $incrementFileName = auth()->id() . '_' . time() . '.'. $request->increment_image->extension();
                        $type = $request->increment_image->getClientMimeType();
                        $size = $request->increment_image->getSize();
                        $request->increment_image->move(public_path('hrm/employee/increment'), $incrementFileName);
                        $update->increment_image = $incrementFileName; 
                    }
                    else if($request->is_increment_delete == 1) {
                        $update->increment_image = NULL;
                    }
                    $update->employee_id = $request->employee_id;
                    $update->increment_policy_number = $request->increment_policy_number;
                    $update->increment_card_number = $request->increment_card_number;
                    $update->increment_policy_start_date = $request->increment_policy_start_date;
                    $update->increment_policy_end_date = $request->increment_policy_end_date;
                    $update->updated_by = $authId;
                    $update->update();
                }
                (new UserActivityController)->createActivity('Employee increment Updated');
                $successMessage = "Employee increment Updated Successfully";                   
                DB::commit();
                return redirect()->route('increment.index')->with('success',$successMessage);
            }
            catch (\Exception $e) {
                DB::rollback();
                dd($e);
            }
        }       
    }
    public function show($id) {
        $data = Increment::where('id',$id)->first();
        $previous = Increment::where('id', '<', $id)->max('id');
        $next = Increment::where('id', '>', $id)->min('id');
        $all = Increment::where('employee_id',$data->employee_id)->latest('increment_policy_end_date')->get();
        return view('hrm.increment.show',compact('data','previous','next','all'));
    }
}
