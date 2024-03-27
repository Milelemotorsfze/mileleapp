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
use App\Models\HRM\Employee\EmployeeProfile;
use App\Models\EmpDoc;

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
        $employees = User::where('status','active')->whereNotIn('id',[1,16])->whereHas('empProfile', function($q) {
            $q = $q->where('type','employee');
        })->with('empProfile.department','empProfile.designation','empProfile.location')->get();
        return view('hrm.increment.create',compact('employees'));
    }
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'increament_effective_date' => 'required',
            'increment_amount' => 'required',
            'revised_basic_salary' => 'required',
            'revised_other_allowance' => 'required',
            'revised_total_salary' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $authId = Auth::id();
                $emp = EmployeeProfile::where('user_id',$request->employee_id)->first();                
                $input = $request->all();
                if($emp) {
                    $input['basic_salary'] = $emp->basic_salary; 
                    $input['other_allowances'] = $emp->other_allowances; 
                    $input['total_salary'] = $emp->total_salary; 
                }
                $input['created_by'] = $authId; 
                $createRequest = Increment::create($input);
                if($emp) {
                    $emp->basic_salary = $request->revised_basic_salary;
                    $emp->other_allowances = $request->revised_other_allowance;
                    $emp->total_salary = $request->revised_total_salary;
                    $emp->update();
                    (new UserActivityController)->createActivity('Employee Salary Updated');
                }
                if ($request->hasFile('salaryIncrement')) {
                    foreach ($request->file('salaryIncrement') as $file) {
                        $extension = $file->getClientOriginalExtension();
                        $fileName = time().'_salary_increment_'.$file->getClientOriginalName();
                        $destinationPath = 'hrm/employee/salary_increment';
                        $file->move($destinationPath, $fileName);        
                        $CandidateDocument = new EmpDoc();
                        $CandidateDocument->emp_profile_id = $emp->id;
                        $CandidateDocument->document_name = 'Salary Increment';
                        $CandidateDocument->document_path = $fileName;
                        $CandidateDocument->save();
                    }
                }
                (new UserActivityController)->createActivity('Employee Salary Increment Created');
                $successMessage = "Employee Salary Increment Created Successfully";                   
                DB::commit();
                return redirect()->route('increment.index')->with('success',$successMessage);
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
        $employees = User::where('status','active')->whereNotIn('id',[1,16])->whereHas('empProfile', function($q) {
            $q = $q->where('type','employee');
        })->with('empProfile.department','empProfile.designation','empProfile.location')->get();
        $data = Increment::where('id',$id)->with('user.empProfile.department','user.empProfile.designation','user.empProfile.location')->first();
        return view('hrm.increment.edit',compact('employees','data'));
    }
    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'increament_effective_date' => 'required',
            'increment_amount' => 'required',
            'revised_basic_salary' => 'required',
            'revised_other_allowance' => 'required',
            'revised_total_salary' => 'required',
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
                    $update->increament_effective_date = $request->increament_effective_date;
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
                info($e);
                $errorMsg ="Something went wrong! Contact your admin";
                return view('hrm.notaccess',compact('errorMsg'));
            }
        }       
    }
    public function show($id) {
        $data = Increment::where('id',$id)->first();
        $previous = Increment::where('id', '<', $id)->max('id');
        $next = Increment::where('id', '>', $id)->min('id');
        $all = Increment::where('employee_id',$data->employee_id)->latest('increament_effective_date')->get();
        return view('hrm.increment.show',compact('data','previous','next','all'));
    }
}
