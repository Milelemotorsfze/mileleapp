<?php

namespace App\Http\Controllers\HRM\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HRM\Employee\PassportRequest;
use App\Models\Masters\PassportRequestPurpose;
use App\Models\HRM\Employee\PassportRelease;
use App\Models\HRM\Employee\PassportReleaseHistory;
use Validator;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\HRM\Employee\EmployeeProfile;
use App\Models\HRM\Approvals\DepartmentHeadApprovals;
use App\Models\Masters\MasterDivisionWithHead;
use App\Models\HRM\Approvals\ApprovalByPositions;
use App\Models\HRM\Employee\PassportRequestHistory;
use App\Http\Controllers\UserActivityController;
use Exception;
use App\Models\User;

class PassportRequestController extends Controller
{
    public function index() {
        $pendings = PassportRequest::where('submit_status','pending')->latest()->get();
        $approved = PassportRequest::where('submit_status','approved')->latest()->get();
        $rejected = PassportRequest::where('submit_status','rejected')->latest()->get();
        return view('hrm.passport.passport_request.index',compact('pendings','approved','rejected'));
    }
    // public function create() {
    //     return view('hrm.passport.passport_request.create');
    // }
    public function edit($id) {
        $data = PassportRequest::where('id',$id)->first();
        $Users = User::whereHas('empProfile')->get();
        $masterEmployees = [];
        $currentUser = User::where('id',$data->employee_id)->first();        
        if($currentUser) {
            array_push($masterEmployees,$currentUser);  
        }
        foreach($Users as $User) {
            if($User->can_submit_or_release_passport == true) {
                array_push($masterEmployees,$User);  
            }
        }
        $submissionPurpose = PassportRequestPurpose::where('type','submit')->get();
        $releasePurpose = PassportRequestPurpose::where('type','release')->get();
        return view('hrm.passport.passport_request.edit',compact('data','masterEmployees','submissionPurpose','releasePurpose'));
    }
    public function show($id) {
        $data = PassportRequest::where('id',$id)->first();
        $previous = PassportRequest::where('id', '<', $id)->max('id');
        $next = PassportRequest::where('id', '>', $id)->min('id');
        return view('hrm.passport.passport_request.show',compact('data','previous','next'));
    }
    public function createOrEdit($id) {
        if($id == 'new') {
            $data = new PassportRequest();
            $previous = $next = '';
        }
        else {
            $data = PassportRequest::find($id);
            $previous = PassportRequest::where('id', '<', $id)->max('id');
            $next = PassportRequest::where('id', '>', $id)->min('id');
        }
        $Users = User::whereHas('empProfile')->get();
        $masterEmployees = [];
        foreach($Users as $User) {
            if($User->can_submit_or_release_passport == true) {
                array_push($masterEmployees,$User);  
            }
        }
        // dd($Users);
        $submissionPurpose = PassportRequestPurpose::where('type','submit')->get();
        $releasePurpose = PassportRequestPurpose::where('type','release')->get();
        return view('hrm.passport.passport_request.create',compact('id','data','previous','next','masterEmployees','submissionPurpose','releasePurpose'));
    }
    public function storeOrUpdate(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            // DB::beginTransaction();
            // try {
                $authId = Auth::id();
                $employee = EmployeeProfile::where('user_id',$request->employee_id)->first();
                $divisionHead = MasterDivisionWithHead::where('id',$employee->department->division_id)->first();
                $HRManager = ApprovalByPositions::where('approved_by_position','HR Manager')->first();
                $input = $request->all();
                if($id == 'new') {
                    $input['created_by'] = $authId;
                    if(isset($request->purposes_of_submit)) {
                        $input['submit_hr_manager_id'] = $HRManager->handover_to_id;                
                        $input['submit_department_head_id'] = $employee->leadManagerHandover->approval_by_id;
                        $input['submit_division_head_id'] = $divisionHead->division_head_id;
                        // $input['passport_status'] = 'with_company';
                        $createRequest = PassportRequest::create($input);
                        $history['passport_request_id'] = $createRequest->id;
                        $history2['passport_request_id'] = $createRequest->id;
                        $submitOrRelease = '';
                    } 
                    else if(isset($request->purposes_of_release)) {
                        $update = PassportRequest::where([
                            ['employee_id',$request->employee_id],
                            // ['passport_status','with_company'],
                            ['submit_action_by_hr_manager','approved'],
                            ['purposes_of_release',NULL],
                            ['submit_status','approved'],
                        ])->first(); 
                        if($update) {
                            $update->release_hr_manager_id = $HRManager->handover_to_id;                
                            $update->release_department_head_id = $employee->leadManagerHandover->approval_by_id;
                            $update->release_division_head_id = $divisionHead->division_head_id;
                            // $update->passport_status = 'with_employee';
                            $update->update();
                            $history['passport_request_id'] = $update->id;
                            $history2['passport_request_id'] = $update->id;
                        }                       
                    }                   
                    $history['icon'] = 'icons8-document-30.png';
                    $history['message'] = 'Employee Leave request created by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                    $createHistory = PassportRequestHistory::create($history);                  
                    $history2['icon'] = 'icons8-send-30.png';
                    $history2['message'] = 'Employee passport '.$request->purposes_of_submit.' request send to Team Lead / Reporting Manager ( '.$employee->leadManagerHandover->handOverTo->name.' - '.$employee->leadManagerHandover->handOverTo->email.' ) for approval';
                    $createHistory2 = PassportRequestHistory::create($history2);
                    (new UserActivityController)->createActivity('Employee Passport Request Created');
                    $successMessage = "Employee Passport Request Created Successfully";
                }
                else {
                    $update = PassportRequest::find($id);
                    if($update) {
                        $update->employee_id = $request->employee_id;
                        $update->updated_by = $authId;
                        if(isset($request->purposes_of_submit)) {
                            $update->submit_hr_manager_id = $HRManager->handover_to_id;                
                            $update->submit_department_head_id = $employee->leadManagerHandover->approval_by_id;
                            $update->submit_division_head_id = $divisionHead->division_head_id;
                        } 
                        else if(isset($request->purposes_of_release)) {
                            $update->release_hr_manager_id = $HRManager->handover_to_id;                
                            $update->release_department_head_id = $employee->leadManagerHandover->approval_by_id;
                            $update->release_division_head_id = $divisionHead->division_head_id;
                        }                      
                        $update->update();
                        $history['passport_request_id'] = $id;
                        $history['icon'] = 'icons8-edit-30.png';
                        $history['message'] = 'Employee Passport request edited by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                        $createHistory = PassportRequestHistory::create($history);
                        (new UserActivityController)->createActivity('Employee Passport Request Edited');
                        $successMessage = "Employee Passport Request Updated Successfully";
                    }
                }
                
                // DB::commit();
                return redirect()->route('passport_request.index')
                                    ->with('success',$successMessage);
            // } 
            // catch (\Exception $e) {
            //     DB::rollback();
            //     dd($e);
            // }
        }
    }
}
