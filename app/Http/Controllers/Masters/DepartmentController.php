<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Masters\MasterDepartment;
use App\Models\User;
use App\Models\Masters\MasterDivisionWithHead;
use Validator;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\HRM\Approvals\TeamLeadOrReportingManagerHandOverTo;
use App\Http\Controllers\UserActivityController;
use App\Models\HRM\Hiring\EmployeeHiringRequest;
use App\Models\HRM\Hiring\EmployeeHiringRequestHistory;
use App\Models\HRM\Hiring\JobDescription;
use App\Models\HRM\Employee\PassportRequest;
use App\Models\HRM\Employee\PassportRequestHistory;
use App\Models\HRM\Employee\PassportRelease;
use App\Models\HRM\Employee\PassportReleaseHistory;
use App\Models\HRM\Employee\Liability;
use App\Models\HRM\Employee\LiabilityHistory;
use App\Models\HRM\Employee\Leave;
use App\Models\HRM\Employee\LeaveHistory;
use App\Models\HRM\Employee\JoiningReport;
use App\Models\HRM\Employee\JoiningReportHistory;
use App\Models\HRM\Employee\OverTime;
use App\Models\HRM\Employee\OverTimeHistory;
use App\Models\HRM\Employee\Separation;
use App\Models\HRM\Employee\SeparationHistory;
use App\Models\HRM\Employee\EmployeeProfile;

class DepartmentController extends Controller
{
    public function update(Request $request, $id) {
        $successMessage = '';
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'division_id' => 'required',
            'department_head_id' => 'required',
            'approval_by_id' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $authId = Auth::id();
                $newApprovalPerson = User::where('status','active')->whereNotIn('id',[1,16])->where('id',$request->approval_by_id)->first();
                $data = MasterDepartment::where('id',$id)->first();
                if($data) {
                 
                    if($data->approval_by_id != $request->approval_by_id) {
                        // Hiring Request
                        $hiringDeptHead = EmployeeHiringRequest::where([
                            ['action_by_department_head','pending'],
                            ['department_head_id',$data->approval_by_id]
                        ])->get();
                        if(count($hiringDeptHead) > 0) {
                            foreach($hiringDeptHead as $hiringDeptHeadData) {
                                $hiringDeptHeadData->department_head_id = $request->approval_by_id;
                                $hiringDeptHeadData->updated_by = $authId;
                                $hiringDeptHeadData->update();
                                $histryHiring = [];
                                $histryHiring['hiring_request_id'] = $hiringDeptHeadData->id;
                                $histryHiring['icon'] = 'icons8-send-30.png';
                                $histryHiring['message'] = 'Employee hiring request send to Team Lead / Reporting Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                $createHistryHiring = EmployeeHiringRequestHistory::create($histryHiring);
                            }
                        }
                        // Job Description
                        $jdDeptHead = JobDescription::where([
                            ['action_by_department_head','pending'],
                            ['department_head_id',$data->approval_by_id]
                        ])->get();
                        if(count($jdDeptHead) > 0) {
                            foreach($jdDeptHead as $jdDeptHeadData) {
                                $jdDeptHeadData->department_head_id = $request->approval_by_id;
                                $jdDeptHeadData->updated_by = $authId;
                                $jdDeptHeadData->update();
                                $historyJD = [];
                                $historyJD['hiring_request_id'] = $request->hiring_request_id;
                                $historyJD['icon'] = 'icons8-send-30.png';
                                $historyJD['message'] = 'Employee hiring job description send to Team Lead / Reporting Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                $createHistoryJD = EmployeeHiringRequestHistory::create($historyJD);
                            }
                        }
                        // Interview Summary Report (doesnot have department approval)
                        
                        // Passport Submit
                        $passportSubmitDeptHead = PassportRequest::where([
                            ['submit_action_by_department_head','pending'],
                            ['submit_department_head_id',$data->approval_by_id]
                        ])->get();
                        if(count($passportSubmitDeptHead) > 0) {
                            foreach($passportSubmitDeptHead as $passportSubmitDeptHeadData) {
                                $passportSubmitDeptHeadData->submit_department_head_id = $request->approval_by_id;
                                $passportSubmitDeptHeadData->updated_by = $authId;
                                $passportSubmitDeptHeadData->update();
                                $histryHiring = [];
                                $histryHiring['passport_request_id'] = $passportSubmitDeptHeadData->id;
                                $histryHiring['icon'] = 'icons8-send-30.png';
                                $histryHiring['message'] = 'Passport Submit Request send to Team Lead / Reporting Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                $createHistryHiring = PassportRequestHistory::create($histryHiring);
                            }
                        }
                        // Passport Release
                        $passportReleaseDeptHead = PassportRelease::where([
                            ['release_action_by_department_head','pending'],
                            ['release_department_head_id',$data->approval_by_id]
                        ])->get();
                        if(count($passportReleaseDeptHead) > 0) {
                            foreach($passportReleaseDeptHead as $passportReleaseDeptHeadData) {
                                $passportReleaseDeptHeadData->release_department_head_id = $request->approval_by_id;
                                $passportReleaseDeptHeadData->updated_by = $authId;
                                $passportReleaseDeptHeadData->update();
                                $histryHiring = [];
                                $histryHiring['passport_release_id'] = $passportReleaseDeptHeadData->id;
                                $histryHiring['icon'] = 'icons8-send-30.png';
                                $histryHiring['message'] = 'Passport Release Request send to Team Lead / Reporting Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                $createHistryHiring = PassportReleaseHistory::create($histryHiring);
                            }
                        }
                        // Liability
                        $liabilityDeptHead = Liability::where([
                            ['action_by_department_head','pending'],
                            ['department_head_id',$data->approval_by_id]
                        ])->get();
                        if(count($liabilityDeptHead) > 0) {
                            foreach($liabilityDeptHead as $liabilityDeptHeadData) {
                                $liabilityDeptHeadData->department_head_id = $request->approval_by_id;
                                $liabilityDeptHeadData->updated_by = $authId;
                                $liabilityDeptHeadData->update();
                                $histryHiring = [];
                                $histryHiring['liability_id'] = $liabilityDeptHeadData->id;
                                $histryHiring['icon'] = 'icons8-send-30.png';
                                $histryHiring['message'] = 'Liability Request send to Team Lead / Reporting Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                $createHistryHiring = LiabilityHistory::create($histryHiring);
                            }
                        }
                        // Leave
                        $leaveDeptHead = Leave::where([
                            ['action_by_department_head','pending'],
                            ['department_head_id',$data->approval_by_id]
                        ])->get();
                        if(count($leaveDeptHead) > 0) {
                            foreach($leaveDeptHead as $leaveDeptHeadData) {
                                $leaveDeptHeadData->department_head_id = $request->approval_by_id;
                                $leaveDeptHeadData->updated_by = $authId;
                                $leaveDeptHeadData->update();
                                $histryHiring = [];
                                $histryHiring['leave_id'] = $leaveDeptHeadData->id;
                                $histryHiring['icon'] = 'icons8-send-30.png';
                                $histryHiring['message'] = 'Leave Request send to Team Lead / Reporting Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                $createHistryHiring = LeaveHistory::create($histryHiring);
                            }
                        }
                        // Joining Report
                         $joiningDeptHead = JoiningReport::where([
                            ['action_by_department_head','pending'],
                            ['department_head_id',$data->approval_by_id]
                        ])->get();
                        if(count($joiningDeptHead) > 0) {
                            foreach($joiningDeptHead as $joiningDeptHeadData) {
                                $joiningDeptHeadData->department_head_id = $request->approval_by_id;
                                $joiningDeptHeadData->updated_by = $authId;
                                $joiningDeptHeadData->update();
                                $histryHiring = [];
                                $histryHiring['joining_report_id'] = $joiningDeptHeadData->id;
                                $histryHiring['icon'] = 'icons8-send-30.png';
                                $histryHiring['message'] = 'Joining Report Request send to Team Lead / Reporting Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                $createHistryHiring = JoiningReportHistory::create($histryHiring);
                            }
                        }
                        // Overtime Application
                        $overtimeDeptHead = OverTime::where([
                            ['action_by_department_head','pending'],
                            ['department_head_id',$data->approval_by_id]
                        ])->get();
                        if(count($overtimeDeptHead) > 0) {
                            foreach($overtimeDeptHead as $overtimeDeptHeadData) {
                                $overtimeDeptHeadData->department_head_id = $request->approval_by_id;
                                $overtimeDeptHeadData->updated_by = $authId;
                                $overtimeDeptHeadData->update();
                                $histryHiring = [];
                                $histryHiring['over_times_id'] = $overtimeDeptHeadData->id;
                                $histryHiring['icon'] = 'icons8-send-30.png';
                                $histryHiring['message'] = 'Overtime Application Request send to Team Lead / Reporting Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                $createHistryHiring = OverTimeHistory::create($histryHiring);
                            }
                        }
                        // Separation Employee Handover
                        $separationDeptHead = Separation::where([
                            ['action_by_department_head','pending'],
                            ['department_head_id',$data->approval_by_id]
                        ])->get();
                        if(count($separationDeptHead) > 0) {
                            foreach($separationDeptHead as $separationDeptHeadData) {
                                $separationDeptHeadData->department_head_id = $request->approval_by_id;
                                $separationDeptHeadData->updated_by = $authId;
                                $separationDeptHeadData->update();
                                $histryHiring = [];
                                $histryHiring['separations_id'] = $separationDeptHeadData->id;
                                $histryHiring['icon'] = 'icons8-send-30.png';
                                $histryHiring['message'] = 'Separation Employee Handover Request send to Team Lead / Reporting Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                $createHistryHiring = SeparationHistory::create($histryHiring);
                            }
                        }
                        // Teamlead Or Reporting Manager Handover To
                        $lead = TeamLeadOrReportingManagerHandOverTo::where('lead_or_manager_id',$data->department_head_id)->first();
                        if($lead) {
                            $lead->approval_by_id = $request->approval_by_id;
                            $lead->updated_by = $authId;
                            $lead->update();
                        }
                        else {
                            $handOver['created_by'] = $authId; 
                            $handOver['lead_or_manager_id'] = $request->department_head_id; 
                            $handOver['approval_by_id'] = $request->approval_by_id;
                            $createHandover = TeamLeadOrReportingManagerHandOverTo::create($handOver);
                        }
                    } 
                    $data->name = $request->name;
                    if($data->department_head_id != $request->department_head_id) {
                        $updateEmpLeads = EmployeeProfile::where('team_lead_or_reporting_manager',$data->department_head_id)
                        ->whereNot('user_id',$request->department_head_id)->where('department_id',$id)->get();
                        if(count($updateEmpLeads) > 0) {
                            foreach($updateEmpLeads as $updateEmpLeadsData) {
                                $updateEmpLeadsData->team_lead_or_reporting_manager = $request->department_head_id;
                                $updateEmpLeadsData->updated_by = $authId;
                                $updateEmpLeadsData->update();
                            }
                        } 
                        $updateOldLead = EmployeeProfile::where('user_id',$data->department_head_id)->where('department_id',$id)
                        ->where('team_lead_or_reporting_manager',$data->division->division_head_id)->first();
                        if($updateOldLead) {
                            $updateOldLead->team_lead_or_reporting_manager = $request->department_head_id;
                            $updateOldLead->updated_by = $authId;
                            $updateOldLead->update();
                        }
                        $updateNewLead = EmployeeProfile::where('user_id',$request->department_head_id)->where('department_id',$id)->first();
                        if($updateNewLead) {
                            $updateNewLead->team_lead_or_reporting_manager = $updateNewLead->department->division->division_head_id;
                            $updateNewLead->updated_by = $authId;
                            $updateNewLead->update();
                        }
                        $isOtherLead = EmployeeProfile::where('team_lead_or_reporting_manager',$data->department_head_id)
                        ->whereNot('user_id',$request->department_head_id)->whereNot('department_id',$id)->get();
                        $updateLeadHandover = TeamLeadOrReportingManagerHandOverTo::where('lead_or_manager_id',$data->department_head_id)->first();
                        if($updateLeadHandover && count($isOtherLead) == 0) {
                            $updateLeadHandover->lead_or_manager_id = $request->department_head_id;
                            $updateLeadHandover->updated_by = $authId;
                            $updateLeadHandover->update();                       
                        }
                        $updateLeadHandover2 = TeamLeadOrReportingManagerHandOverTo::where('lead_or_manager_id',$request->department_head_id)->first();
                        if($updateLeadHandover2 == null) {
                            $createHandOvr['lead_or_manager_id'] = $request->department_head_id;
                            $createHandOvr['approval_by_id'] = $employ->team_lead_or_reporting_manager;
                            $createHandOvr['created_by'] = $authId;
                            $leadHandover = TeamLeadOrReportingManagerHandOverTo::create($createHandOvr);              
                        }
                        $data->department_head_id = $request->department_head_id;
                    }
                    $data->approval_by_id = $request->approval_by_id;
                    $data->updated_by = $authId;
                    $data->division_id = $request->division_id;
                    $data->is_demand_planning = $request->is_demand_planning ? true : false;
                    $data->update();
                    (new UserActivityController)->createActivity('Master Department Edited');
                    $successMessage = "Master Department Updated Successfully";
                }
                DB::commit();
                return redirect()->route('department.index')->with('success',$successMessage);
            } 
            catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg ="Something went wrong! Contact your admin";
                return view('hrm.notaccess',compact('errorMsg'));
            }
        }
    }
    public function index() {
        $data = MasterDepartment::orderBy('name', 'ASC')->whereNot('name','Management')->get();
        return view('hrm.masters.department.index',compact('data'));
    }
    public function edit($id) {
        $previous = $next = '';
        $data = MasterDepartment::whereNot('name','Management')->where('id',$id)->first();
        $previous = MasterDepartment::whereNot('name','Management')->where('id', '<', $id)->max('id');
        $next = MasterDepartment::whereNot('name','Management')->where('id', '>', $id)->min('id');
        $deptHeads = User::orderBy('name', 'ASC')->where('status','active')->whereNotIn('id',[1,16])->whereHas('empProfile')->with('empProfile.designation','empProfile.location')->get();
        $divisions = MasterDivisionWithHead::get();
        return view('hrm.masters.department.edit',compact('data','previous','next','deptHeads','divisions'));
        // $errorMsg ="This page will coming very soon !";
        // return view('hrm.notaccess',compact('errorMsg'));
    }
    public function show($id) {
        $errorMsg ="This page will coming very soon !";
        return view('hrm.notaccess',compact('errorMsg'));
    }
    public function create() {
        $deptHeads = User::orderBy('name', 'ASC')->where('status','active')->whereNotIn('id',[1,16])->whereHas('empProfile')->with('empProfile.designation','empProfile.location')->get();
        $divisions = MasterDivisionWithHead::get();
        return view('hrm.masters.department.create',compact('deptHeads','divisions'));
        // $errorMsg ="This page will coming very soon !";
        // return view('hrm.notaccess',compact('errorMsg'));
    }
    public function uniqueDepartment(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            try {
                $department = MasterDepartment::where('name',$request->name);
                if(isset($request->currentId) && $request->currentId != '') {
                    $department = $department->whereNot('id',$request->currentId);
                }
                $department = $department->get();
                if(count($department) > 0) {
                    return false;
                }
                else {
                    return true;
                }
           } 
           catch (\Exception $e) {
               info($e);
           }
        }
    }
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'division_id' => 'required',
            'department_head_id' => 'required',
            'approval_by_id' => 'required',
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
                $input['is_demand_planning'] = $request->is_demand_planning ? true : false;
                $createRequest = MasterDepartment::create($input);
                $lead = TeamLeadOrReportingManagerHandOverTo::where('lead_or_manager_id',$request->department_head_id)->first();
                if($lead) {
                    $lead->approval_by_id = $request->approval_by_id;
                    $lead->updated_by = $authId;
                    $lead->update();
                }
                else {
                    $handOver['created_by'] = $authId; 
                    $handOver['lead_or_manager_id'] = $request->department_head_id; 
                    $handOver['approval_by_id'] = $request->approval_by_id;
                    $createHandover = TeamLeadOrReportingManagerHandOverTo::create($handOver);
                }
                (new UserActivityController)->createActivity('New Master Department Created Created');
                $successMessage = "New Master Department Created Created Successfully";                   
                DB::commit();
                return redirect()->route('department.index')->with('success',$successMessage);
            }
            catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg ="Something went wrong! Contact your admin";
                return view('hrm.notaccess',compact('errorMsg'));
            }
        }     
    }
}
