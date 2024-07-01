<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Masters\MasterDivisionWithHead;
use App\Models\User;
use Validator;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserActivityController;
use App\Models\HRM\Hiring\EmployeeHiringRequest;
use App\Models\HRM\Hiring\EmployeeHiringRequestHistory;
use App\Models\HRM\Hiring\JobDescription;
use App\Models\HRM\Hiring\InterviewSummaryReport;
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
use App\Models\Masters\MasterDepartment;
use App\Models\HRM\Approvals\TeamLeadOrReportingManagerHandOverTo;

class DivisionController extends Controller
{
    public function index() {
        $data = MasterDivisionWithHead::orderBy('name', 'ASC')->get();
        return view('hrm.masters.division.index',compact('data'));
    }
    public function edit($id) {
        $previous = $next = '';
        $data = MasterDivisionWithHead::where('id',$id)->first();
        $previous = MasterDivisionWithHead::where('id', '<', $id)->max('id');
        $next = MasterDivisionWithHead::where('id', '>', $id)->min('id');
        $divisionHeads = User::orderBy('name', 'ASC')->where('status','active')->whereNotIn('id',[1,16])->whereHas('empProfile')->with('empProfile.designation','empProfile.location')->where('is_management','yes')->get();
        return view('hrm.masters.division.edit',compact('data','previous','next','divisionHeads'));
    } 
    public function update(Request $request, $id) {
        $successMessage = '';
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'division_head_id' => 'required',
            'approval_handover_to' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $authId = Auth::id();
                $newApprovalPerson = User::where('status','active')->whereNotIn('id',[1,16])->where('id',$request->approval_handover_to)->first();
                $data = MasterDivisionWithHead::where('id',$id)->first();
                if($data) {
                    if($data->approval_handover_to != $request->approval_handover_to) {
                        // Hiring Request
                        $hiringDeptHead = EmployeeHiringRequest::where([
                            ['action_by_department_head','pending'],
                            ['department_head_id',$data->approval_handover_to]
                        ])->get();
                        if(count($hiringDeptHead) > 0) {
                            foreach($hiringDeptHead as $hiringDeptHeadData) {
                                $hiringDeptHeadData->department_head_id = $request->approval_handover_to;
                                $hiringDeptHeadData->updated_by = $authId;
                                $hiringDeptHeadData->update();
                                $histryHiring = [];
                                $histryHiring['hiring_request_id'] = $hiringDeptHeadData->id;
                                $histryHiring['icon'] = 'icons8-send-30.png';
                                $histryHiring['message'] = 'Employee hiring request send to Team Lead / Reporting Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                $createHistryHiring = EmployeeHiringRequestHistory::create($histryHiring);
                            }
                        }
                        $hiringDivisionHead = EmployeeHiringRequest::where([
                            ['action_by_division_head','pending'],
                            ['division_head_id',$data->approval_handover_to]
                        ])->get();
                        if(count($hiringDivisionHead) > 0) {
                            foreach($hiringDivisionHead as $hiringDivisionHeadData) {
                                $hiringDivisionHeadData->division_head_id = $request->approval_handover_to;
                                $hiringDivisionHeadData->updated_by = $authId;
                                $hiringDivisionHeadData->update();
                                $histryHiring = [];
                                $histryHiring['hiring_request_id'] = $hiringDivisionHeadData->id;
                                $histryHiring['icon'] = 'icons8-send-30.png';
                                $histryHiring['message'] = 'Employee hiring request send to Division Head ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                $createHistryHiring = EmployeeHiringRequestHistory::create($histryHiring);
                            }
                        }
                        // Job Description
                        $jdDeptHead = JobDescription::where([
                            ['action_by_department_head','pending'],
                            ['department_head_id',$data->approval_handover_to]
                        ])->get();
                        if(count($jdDeptHead) > 0) {
                            foreach($jdDeptHead as $jdDeptHeadData) {
                                $jdDeptHeadData->department_head_id = $request->approval_handover_to;
                                $jdDeptHeadData->updated_by = $authId;
                                $jdDeptHeadData->update();
                                $historyJD = [];
                                $historyJD['hiring_request_id'] = $jdDeptHeadData->hiring_request_id;
                                $historyJD['icon'] = 'icons8-send-30.png';
                                $historyJD['message'] = 'Employee hiring job description send to Team Lead / Reporting Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                $createHistoryJD = EmployeeHiringRequestHistory::create($historyJD);
                            }
                        }
                        // Interview Summary Report
                        $interviewSummaryDivisionHead = InterviewSummaryReport::where([
                            ['action_by_division_head','pending'],
                            ['division_head_id',$data->approval_handover_to]
                        ])->get();
                        if(count($interviewSummaryDivisionHead) > 0) {
                            foreach($interviewSummaryDivisionHead as $interviewSummaryDivisionHeadData) {
                                $interviewSummaryDivisionHeadData->division_head_id = $request->approval_handover_to;
                                $interviewSummaryDivisionHeadData->updated_by = $authId;
                                $interviewSummaryDivisionHeadData->update();
                                $histryHiring = [];
                                $histryHiring['hiring_request_id'] = $interviewSummaryDivisionHeadData->hiring_request_id;
                                $histryHiring['icon'] = 'icons8-send-30.png';
                                $histryHiring['message'] = 'Interview Summary Report send to Division Head ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                $createHistryHiring = EmployeeHiringRequestHistory::create($histryHiring);
                            }
                        }
                        // Passport Submit
                        $passportSubmitDeptHead = PassportRequest::where([
                            ['submit_action_by_department_head','pending'],
                            ['submit_department_head_id',$data->approval_handover_to]
                        ])->get();
                        if(count($passportSubmitDeptHead) > 0) {
                            foreach($passportSubmitDeptHead as $passportSubmitDeptHeadData) {
                                $passportSubmitDeptHeadData->submit_department_head_id = $request->approval_handover_to;
                                $passportSubmitDeptHeadData->updated_by = $authId;
                                $passportSubmitDeptHeadData->update();
                                $histryHiring = [];
                                $histryHiring['passport_request_id'] = $passportSubmitDeptHeadData->id;
                                $histryHiring['icon'] = 'icons8-send-30.png';
                                $histryHiring['message'] = 'Passport Submit Request send to Team Lead / Reporting Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                $createHistryHiring = PassportRequestHistory::create($histryHiring);
                            }
                        }
                        $passportSubmitDivisionHead = PassportRequest::where([
                            ['submit_action_by_division_head','pending'],
                            ['submit_division_head_id',$data->approval_handover_to]
                        ])->get();
                        if(count($passportSubmitDivisionHead) > 0) {
                            foreach($passportSubmitDivisionHead as $passportSubmitDivisionHeadData) {
                                $passportSubmitDivisionHeadData->submit_division_head_id = $request->approval_handover_to;
                                $passportSubmitDivisionHeadData->updated_by = $authId;
                                $passportSubmitDivisionHeadData->update();
                                $histryHiring = [];
                                $histryHiring['passport_request_id'] = $passportSubmitDivisionHeadData->id;
                                $histryHiring['icon'] = 'icons8-send-30.png';
                                $histryHiring['message'] = 'Passport Submit Request send to Division Head ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                $createHistryHiring = PassportRequestHistory::create($histryHiring);
                            }
                        }
                        // Passport Release
                        $passportReleaseDeptHead = PassportRelease::where([
                            ['release_action_by_department_head','pending'],
                            ['release_department_head_id',$data->approval_handover_to]
                        ])->get();
                        if(count($passportReleaseDeptHead) > 0) {
                            foreach($passportReleaseDeptHead as $passportReleaseDeptHeadData) {
                                $passportReleaseDeptHeadData->release_department_head_id = $request->approval_handover_to;
                                $passportReleaseDeptHeadData->updated_by = $authId;
                                $passportReleaseDeptHeadData->update();
                                $histryHiring = [];
                                $histryHiring['passport_release_id'] = $passportReleaseDeptHeadData->id;
                                $histryHiring['icon'] = 'icons8-send-30.png';
                                $histryHiring['message'] = 'Passport Release Request send to Team Lead / Reporting Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                $createHistryHiring = PassportReleaseHistory::create($histryHiring);
                            }
                        }
                        $passportReleaseDivisionHead = PassportRelease::where([
                            ['release_action_by_division_head','pending'],
                            ['release_division_head_id',$data->approval_handover_to]
                        ])->get();
                        if(count($passportReleaseDivisionHead) > 0) {
                            foreach($passportReleaseDivisionHead as $passportReleaseDivisionHeadData) {
                                $passportReleaseDivisionHeadData->release_division_head_id = $request->approval_handover_to;
                                $passportReleaseDivisionHeadData->updated_by = $authId;
                                $passportReleaseDivisionHeadData->update();
                                $histryHiring = [];
                                $histryHiring['passport_release_id'] = $passportReleaseDeptHeadData->id;
                                $histryHiring['icon'] = 'icons8-send-30.png';
                                $histryHiring['message'] = 'Passport Release Request send to Division Head ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                $createHistryHiring = PassportReleaseHistory::create($histryHiring);
                            }
                        }
                        // Liability
                        $liabilityDeptHead = Liability::where([
                            ['action_by_department_head','pending'],
                            ['department_head_id',$data->approval_handover_to]
                        ])->get();
                        if(count($liabilityDeptHead) > 0) {
                            foreach($liabilityDeptHead as $liabilityDeptHeadData) {
                                $liabilityDeptHeadData->department_head_id = $request->approval_handover_to;
                                $liabilityDeptHeadData->updated_by = $authId;
                                $liabilityDeptHeadData->update();
                                $histryHiring = [];
                                $histryHiring['liability_id'] = $liabilityDeptHeadData->id;
                                $histryHiring['icon'] = 'icons8-send-30.png';
                                $histryHiring['message'] = 'Liability Request send to Team Lead / Reporting Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                $createHistryHiring = LiabilityHistory::create($histryHiring);
                            }
                        }
                        $liabilityDivisionHead = Liability::where([
                            ['action_by_division_head','pending'],
                            ['division_head_id',$data->approval_handover_to]
                        ])->get();
                        if(count($liabilityDivisionHead) > 0) {
                            foreach($liabilityDivisionHead as $liabilityDivisionHeadData) {
                                $liabilityDivisionHeadData->division_head_id = $request->approval_handover_to;
                                $liabilityDivisionHeadData->updated_by = $authId;
                                $liabilityDivisionHeadData->update();
                                $histryHiring = [];
                                $histryHiring['liability_id'] = $liabilityDeptHeadData->id;
                                $histryHiring['icon'] = 'icons8-send-30.png';
                                $histryHiring['message'] = 'Liability Request send to Division Head ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                $createHistryHiring = LiabilityHistory::create($histryHiring);
                            }
                        }
                        // Leave
                        $leaveDeptHead = Leave::where([
                            ['action_by_department_head','pending'],
                            ['department_head_id',$data->approval_handover_to]
                        ])->get();
                        if(count($leaveDeptHead) > 0) {
                            foreach($leaveDeptHead as $leaveDeptHeadData) {
                                $leaveDeptHeadData->department_head_id = $request->approval_handover_to;
                                $leaveDeptHeadData->updated_by = $authId;
                                $leaveDeptHeadData->update();
                                $histryHiring = [];
                                $histryHiring['leave_id'] = $leaveDeptHeadData->id;
                                $histryHiring['icon'] = 'icons8-send-30.png';
                                $histryHiring['message'] = 'Leave Request send to Team Lead / Reporting Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                $createHistryHiring = LeaveHistory::create($histryHiring);
                            }
                        }
                        $leaveDivisionHead = Leave::where([
                            ['action_by_division_head','pending'],
                            ['division_head_id',$data->approval_handover_to]
                        ])->get();
                        if(count($leaveDivisionHead) > 0) {
                            foreach($leaveDivisionHead as $leaveDivisionHeadData) {
                                $leaveDivisionHeadData->division_head_id = $request->approval_handover_to;
                                $leaveDivisionHeadData->updated_by = $authId;
                                $leaveDivisionHeadData->update();
                                $histryHiring = [];
                                $histryHiring['leave_id'] = $leaveDeptHeadData->id;
                                $histryHiring['icon'] = 'icons8-send-30.png';
                                $histryHiring['message'] = 'Leave Request send to Division Head ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                $createHistryHiring = LeaveHistory::create($histryHiring);
                            }
                        }
                        // Joining Report
                         $joiningDeptHead = JoiningReport::where([
                            ['action_by_department_head','pending'],
                            ['department_head_id',$data->approval_handover_to]
                        ])->get();
                        if(count($joiningDeptHead) > 0) {
                            foreach($joiningDeptHead as $joiningDeptHeadData) {
                                $joiningDeptHeadData->department_head_id = $request->approval_handover_to;
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
                            ['department_head_id',$data->approval_handover_to]
                        ])->get();
                        if(count($overtimeDeptHead) > 0) {
                            foreach($overtimeDeptHead as $overtimeDeptHeadData) {
                                $overtimeDeptHeadData->department_head_id = $request->approval_handover_to;
                                $overtimeDeptHeadData->updated_by = $authId;
                                $overtimeDeptHeadData->update();
                                $histryHiring = [];
                                $histryHiring['over_times_id'] = $overtimeDeptHeadData->id;
                                $histryHiring['icon'] = 'icons8-send-30.png';
                                $histryHiring['message'] = 'Overtime Application Request send to Team Lead / Reporting Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                $createHistryHiring = OverTimeHistory::create($histryHiring);
                            }
                        }
                        $overtimeDivisionHead = OverTime::where([
                            ['action_by_division_head','pending'],
                            ['division_head_id',$data->approval_handover_to]
                        ])->get();
                        if(count($overtimeDivisionHead) > 0) {
                            foreach($overtimeDivisionHead as $overtimeDivisionHeadData) {
                                $overtimeDivisionHeadData->division_head_id = $request->approval_handover_to;
                                $overtimeDivisionHeadData->updated_by = $authId;
                                $overtimeDivisionHeadData->update();
                                $histryHiring = [];
                                $histryHiring['over_times_id'] = $overtimeDivisionHeadData->id;
                                $histryHiring['icon'] = 'icons8-send-30.png';
                                $histryHiring['message'] = 'Overtime Application Request send to Division Head ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                $createHistryHiring = OverTimeHistory::create($histryHiring);
                            }
                        }
                        // Separation Employee Handover
                        $separationDeptHead = Separation::where([
                            ['action_by_department_head','pending'],
                            ['department_head_id',$data->approval_handover_to]
                        ])->get();
                        if(count($separationDeptHead) > 0) {
                            foreach($separationDeptHead as $separationDeptHeadData) {
                                $separationDeptHeadData->department_head_id = $request->approval_handover_to;
                                $separationDeptHeadData->updated_by = $authId;
                                $separationDeptHeadData->update();
                                $histryHiring = [];
                                $histryHiring['separations_id'] = $separationDeptHeadData->id;
                                $histryHiring['icon'] = 'icons8-send-30.png';
                                $histryHiring['message'] = 'Separation Employee Handover Request send to Team Lead / Reporting Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                $createHistryHiring = SeparationHistory::create($histryHiring);
                            }
                        }
                        // Master Department
                        $masterDepts = MasterDepartment::where('department_head_id',$data->division_head_id)->get();
                        if(count($masterDepts) > 0) {
                            foreach($masterDepts as $masterDept) {
                                $masterDept->approval_by_id = $request->approval_handover_to;
                                $masterDept->updated_by = $authId;
                                $masterDept->update();
                            }
                        }
                        // Teamlead Or Reporting Manager Handover To
                        $lead = TeamLeadOrReportingManagerHandOverTo::where('lead_or_manager_id',$data->division_head_id)->first();
                        if($lead) {
                            $lead->approval_by_id = $request->approval_handover_to;
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
                    if($data->division_head_id != $request->division_head_id) {
                        // $updateEmpLeads = EmployeeProfile::where('team_lead_or_reporting_manager',$data->division_head_id)->whereNot('user_id',$request->division_head_id)->get();
                        // if(count($updateEmpLeads) > 0) {
                        //     foreach($updateEmpLeads as $updateEmpLeadsData) {
                        //         $updateEmpLeadsData->team_lead_or_reporting_manager = $request->division_head_id;
                        //         $updateEmpLeadsData->updated_by = $authId;
                        //         $updateEmpLeadsData->update();
                        //     }
                        // }
                        // $updateMasterDept = MasterDepartment::where()
                        $data->division_head_id = $request->division_head_id;
                    }            
                    $data->approval_handover_to = $request->approval_handover_to;
                    $data->updated_by = $authId;
                    $data->update();
                    (new UserActivityController)->createActivity('Master Division Edited');
                    $successMessage = "Master Division Updated Successfully";
                }
                DB::commit();
                return redirect()->route('division.index')
                                    ->with('success',$successMessage);
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
        $errorMsg ="This page will coming very soon !";
        return view('hrm.notaccess',compact('errorMsg'));
    }
    public function uniqueDivision(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            try {
                $division = MasterDivisionWithHead::where('name',$request->name);
                if(isset($request->currentId) && $request->currentId != '') {
                    $division = $division->whereNot('id',$request->currentId);
                }
                $division = $division->get();
                if(count($division) > 0) {
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
}
