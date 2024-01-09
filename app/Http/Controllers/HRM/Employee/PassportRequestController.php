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
use Carbon\Carbon;

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
            DB::beginTransaction();
            try {
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
                        $input['submit_division_head_id'] = $divisionHead->approval_handover_to;
                        // $input['passport_status'] = 'with_company';
                        $createRequest = PassportRequest::create($input);
                        $history['passport_request_id'] = $createRequest->id;
                        $history2['passport_request_id'] = $createRequest->id;
                        $submitOrRelease = '';
                        $history['icon'] = 'icons8-document-30.png';
                        $history['message'] = 'Employee passport submit request created by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                        $createHistory = PassportRequestHistory::create($history);                  
                        $history2['icon'] = 'icons8-send-30.png';
                        $history2['message'] = 'Employee passport '.$request->purposes_of_submit.' request send to Employee ( '.$employee->first_name.' '.$employee->last_name.' - '.$employee->personal_email_adddress.' ) for approval';
                        $createHistory2 = PassportRequestHistory::create($history2);
                        (new UserActivityController)->createActivity('Employee Passport Submit Request Created');
                        $successMessage = "Employee Passport Submit Request Created Successfully";
                    } 
                    else if(isset($request->purposes_of_release)) {
                        $passportRequest = PassportRequest::where([
                            ['employee_id',$request->employee_id],
                            ['submit_status','approved'],
                        ])->whereDoesntHave('approvedRelease')->latest('id')->first();
                        if($passportRequest) {
                            $input['release_hr_manager_id'] = $HRManager->handover_to_id;                
                            $input['release_department_head_id'] = $employee->leadManagerHandover->approval_by_id;
                            $input['release_division_head_id'] = $divisionHead->approval_handover_to;
                            $input['passport_request_id'] = $passportRequest->id;
                            $input['release_action_by_employee'] = 'pending';
                            $input['release_submit_status'] = 'pending';
                            // $input['passport_status'] = 'with_company';
                            $createRelease = PassportRelease::create($input);
                            $history['passport_release_id'] = $createRelease->id;
                            $history2['passport_release_id'] = $createRelease->id;
                            $submitOrRelease = '';   
                            $history['icon'] = 'icons8-document-30.png';
                            $history['message'] = 'Employee passport release request created by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                            $createHistory = PassportRequestHistory::create($history);                  
                            $history2['icon'] = 'icons8-send-30.png';
                            $history2['message'] = 'Employee passport '.$request->purposes_of_submit.' request send to Employee ( '.$employee->first_name.' '.$employee->last_name.' - '.$employee->personal_email_adddress.' ) for approval';
                            $createHistory2 = PassportRequestHistory::create($history2);
                            (new UserActivityController)->createActivity('Employee Passport Release Request Created');
                            $successMessage = "Employee Passport Release Request Created Successfully"; 
                        }                                
                    }                                    
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
                            $update->purposes_of_submit = $request->purposes_of_submit;
                            $update->submit_status = 'pending';
                            $update->submit_action_by_employee = 'pending';
                            $update->submit_employee_action_at = NULL;
                            $update->submit_comments_by_employee = NULL;
                            $update->submit_action_by_department_head = NULL;
                            $update->submit_department_head_action_at = NULL;
                            $update->submit_comments_by_department_head = NULL;
                            $update->submit_action_by_division_head = NULL;
                            $update->submit_division_head_action_at = NULL;
                            $update->submit_comments_by_division_head = NULL;
                            $update->submit_action_by_hr_manager = NULL;
                            $update->submit_hr_manager_action_at = NULL;
                            $update->submit_comments_by_hr_manager = NULL;
                            $update->update();
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
                DB::commit();
                if(isset($request->purposes_of_submit)) {
                    return redirect()->route('passport_request.index')
                                    ->with('success',$successMessage);
                }
                else if(isset($request->purposes_of_release)) {
                    return redirect()->route('passport_release.index')
                    ->with('success',$successMessage);
                }              
            } 
            catch (\Exception $e) {
                DB::rollback();
                dd($e);
            }
        }
    }
    public function requestAction(Request $request) {
        $message = '';
        $update = PassportRequest::where('id',$request->id)->first();
        // employee -------> Reporting Manager  ------------>Division Head--------->HR Manager       
        if($request->current_approve_position == 'Employee') {
            $update->submit_comments_by_employee = $request->comment;
            $update->submit_employee_action_at = Carbon::now()->format('Y-m-d H:i:s');
            $update->submit_action_by_employee = $request->status;
            if($request->status == 'approved') {
                $update->submit_action_by_department_head = 'pending';
                $message = 'Employee passport submit request send to Reporting Manager ( '.$update->hrManager->name.' - '.$update->hrManager->email.' ) for approval';
            }
        }
        else if($request->current_approve_position == 'Reporting Manager') {
            $update->submit_comments_by_department_head = $request->comment;
            $update->submit_department_head_action_at = Carbon::now()->format('Y-m-d H:i:s');
            $update->submit_action_by_department_head = $request->status;
            if($request->status == 'approved') {
                $update->submit_action_by_division_head = 'pending';
                $message = 'Employee passport submit request send to Division Head ( '.$update->divisionHead->name.' - '.$update->divisionHead->email.' ) for approval';
            }
        }
        else if($request->current_approve_position == 'Division Head') {
            $update->submit_comments_by_division_head = $request->comment;
            $update->submit_division_head_action_at = Carbon::now()->format('Y-m-d H:i:s');
            $update->submit_action_by_division_head = $request->status;
            if($request->status == 'approved') {
                $update->submit_action_by_hr_manager = 'pending';
                $message = 'Employee passport submit request send to HR Manager ( '.$update->hrManager->name.' - '.$update->hrManager->email.' ) for approval';
            }
        }
        else if($request->current_approve_position == 'HR Manager') {
            $update->submit_comments_by_hr_manager = $request->comment;
            $update->submit_hr_manager_action_at = Carbon::now()->format('Y-m-d H:i:s');
            $update->submit_action_by_hr_manager = $request->status;
            if($request->status == 'approved') {
                $update->submit_status = 'approved';
                $emp = EmployeeProfile::where('id',$update->employee_id)->first();
                $emp->passport_status = 'with_milele';
                $emp->update();
            }
        }
        if($request->status == 'rejected') {
            $update->submit_status = 'rejected';
        }
        $update->update();
        $history['passport_request_id'] = $request->id;
        if($request->status == 'approved') {
            $history['icon'] = 'icons8-thumb-up-30.png';
        }
        else if($request->status == 'rejected') {
            $history['icon'] = 'icons8-thumb-down-30.png';
        }
        $history['message'] = 'Employee passport submit request '.$request->status.' by '.$request->current_approve_position.' ( '.Auth::user()->name.' - '.Auth::user()->email.' )';
        $createHistory = PassportRequestHistory::create($history);  
        if($request->status == 'approved' && $message != '') {
            $history['icon'] = 'icons8-send-30.png';
            $history['message'] = $message;
            $createHistory = PassportRequestHistory::create($history);
        }
        (new UserActivityController)->createActivity($history['message']);
        return response()->json('success');
        // ,'New Employee Hiring Request '.$request->status.' Successfully'
    }
}
