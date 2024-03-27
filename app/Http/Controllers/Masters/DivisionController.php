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
use App\Models\HRM\Employee\PassportRelease;

class DivisionController extends Controller
{
    public function index() {
        $data = MasterDivisionWithHead::get();
        return view('hrm.masters.division.index',compact('data'));
    }
    public function edit($id) {
        $previous = $next = '';
        $data = MasterDivisionWithHead::where('id',$id)->first();
        $previous = MasterDivisionWithHead::where('id', '<', $id)->max('id');
        $next = MasterDivisionWithHead::where('id', '>', $id)->min('id');
        $divisionHeads = User::where('status','active')->whereNotIn('id',[1,16])->whereHas('empProfile')->with('empProfile.department','empProfile.designation','empProfile.location')->whereIn('id',[2,26,31,10,62,57])->get();
        return view('hrm.masters.division.edit',compact('data','previous','next','divisionHeads'));
    }
    public function update(Request $request, $id) {
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
                                $histryHiring = '';
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
                                $histryHiring = '';
                                $histryHiring['hiring_request_id'] = $hiringDeptHeadData->id;
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
                                $historyJD = '';
                                $historyJD['hiring_request_id'] = $request->hiring_request_id;
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
                            }
                        }
                        $joiningDivisionHead = JoiningReport::where([
                            ['action_by_division_head','pending'],
                            ['division_head_id',$data->approval_handover_to]
                        ])->get();
                        if(count($joiningDivisionHead) > 0) {
                            foreach($joiningDivisionHead as $joiningDivisionHeadData) {
                                $joiningDivisionHeadData->division_head_id = $request->approval_handover_to;
                                $joiningDivisionHeadData->updated_by = $authId;
                                $joiningDivisionHeadData->update();
                            }
                        }
                    } 
                    $data->name = $request->name;
                    $data->division_head_id = $request->division_head_id;
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
}
