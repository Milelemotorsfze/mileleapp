<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\HRM\Approvals\ApprovalByPositions;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use DB;
use Illuminate\Support\Facades\Auth;
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
use App\Models\HRM\Hiring\InterviewSummaryReport;

class DesignationApprovalsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ApprovalByPositions::all();
        return view('hrm.masters.designationApprovals.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ApprovalByPositions $approvalByPositions)
    {
        $errorMsg ="This page will coming very soon !";
        return view('hrm.notaccess',compact('errorMsg'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $previous = $next = '';
        $data = ApprovalByPositions::where('id',$id)->first();
        $previous = ApprovalByPositions::where('id', '<', $id)->max('id');
        $next = ApprovalByPositions::where('id', '>', $id)->min('id');
        $users = User::orderBy('name', 'ASC')->where('status','active')->whereNotIn('id',[1,16])->whereHas('empProfile')->with('empProfile.designation','empProfile.location')->get();
        return view('hrm.masters.designationApprovals.edit',compact('data','previous','next','users'));
        // $errorMsg ="This page will coming very soon !";
        // return view('hrm.notaccess',compact('errorMsg'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $successMessage = '';
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'approved_by_position' => 'required',
            'approved_by_id' => 'required',
            'handover_to_id' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $authId = Auth::id();
                $newApprovalPerson = User::where('status','active')->whereNotIn('id',[1,16])->where('id',$request->handover_to_id)->first();
                $data = ApprovalByPositions::where('id',$request->id)->first();
                if($data) {
                    if($request->approved_by_position == 'Finance Manager') {
                        if($data->handover_to_id != $request->handover_to_id) {
                            // Liability
                            $liabilityDeptHead = Liability::where([
                                ['action_by_finance_manager','pending'],
                                ['finance_manager_id',$data->approved_by_id]
                            ])->get();
                            if(count($liabilityDeptHead) > 0) {
                                foreach($liabilityDeptHead as $liabilityDeptHeadData) {
                                    $liabilityDeptHeadData->finance_manager_id = $request->handover_to_id;
                                    $liabilityDeptHeadData->updated_by = $authId;
                                    $liabilityDeptHeadData->update();
                                    $histryHiring = [];
                                    $histryHiring['liability_id'] = $liabilityDeptHeadData->id;
                                    $histryHiring['icon'] = 'icons8-send-30.png';
                                    $histryHiring['message'] = 'Liability Request send to Finance Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                    $createHistryHiring = LiabilityHistory::create($histryHiring);
                                }
                            }
                        }
                    }
                    else if($request->approved_by_position == 'Recruiting Manager') {
                        if($data->handover_to_id != $request->handover_to_id) {
                            // Hiring Request
                            $hiringDeptHead = EmployeeHiringRequest::where([
                                ['action_by_hiring_manager','pending'],
                                ['hiring_manager_id',$data->approved_by_id]
                            ])->get();
                            if(count($hiringDeptHead) > 0) {
                                foreach($hiringDeptHead as $hiringDeptHeadData) {
                                    $hiringDeptHeadData->hiring_manager_id = $request->handover_to_id;
                                    $hiringDeptHeadData->updated_by = $authId;
                                    $hiringDeptHeadData->update();
                                    $histryHiring = [];
                                    $histryHiring['hiring_request_id'] = $hiringDeptHeadData->id;
                                    $histryHiring['icon'] = 'icons8-send-30.png';
                                    $histryHiring['message'] = 'Employee hiring request send to Hiring Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                    $createHistryHiring = EmployeeHiringRequestHistory::create($histryHiring);
                                }
                            }
                        }
                    }
                    else if($request->approved_by_position == 'HR Manager') {
                        if($data->handover_to_id != $request->handover_to_id) {
                            // Hiring Request
                            $hiringDeptHead = EmployeeHiringRequest::where([
                                ['action_by_hr_manager','pending'],
                                ['hr_manager_id',$data->approved_by_id]
                            ])->get();
                            if(count($hiringDeptHead) > 0) {
                                foreach($hiringDeptHead as $hiringDeptHeadData) {
                                    $hiringDeptHeadData->hr_manager_id = $request->handover_to_id;
                                    $hiringDeptHeadData->updated_by = $authId;
                                    $hiringDeptHeadData->update();
                                    $histryHiring = [];
                                    $histryHiring['hiring_request_id'] = $hiringDeptHeadData->id;
                                    $histryHiring['icon'] = 'icons8-send-30.png';
                                    $histryHiring['message'] = 'Employee hiring request send to HR Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                    $createHistryHiring = EmployeeHiringRequestHistory::create($histryHiring);
                                }
                            }
                            // Job Description
                            $jdDeptHead = JobDescription::where([
                                ['action_by_hr_manager','pending'],
                                ['hr_manager_id',$data->approved_by_id]
                            ])->get();
                            if(count($jdDeptHead) > 0) {
                                foreach($jdDeptHead as $jdDeptHeadData) {
                                    $jdDeptHeadData->hr_manager_id = $request->handover_to_id;
                                    $jdDeptHeadData->updated_by = $authId;
                                    $jdDeptHeadData->update();
                                    $historyJD = [];
                                    $historyJD['hiring_request_id'] = $request->hiring_request_id;
                                    $historyJD['icon'] = 'icons8-send-30.png';
                                    $historyJD['message'] = 'Employee hiring job description send to HR Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                    $createHistoryJD = EmployeeHiringRequestHistory::create($historyJD);
                                }
                            }
                            // Interview Summary Report
                            $interviewSummaryDivisionHead = InterviewSummaryReport::where([
                                ['action_by_hr_manager','pending'],
                                ['hr_manager_id',$data->approved_by_id]
                            ])->get();
                            if(count($interviewSummaryDivisionHead) > 0) {
                                foreach($interviewSummaryDivisionHead as $interviewSummaryDivisionHeadData) {
                                    $interviewSummaryDivisionHeadData->hr_manager_id = $request->handover_to_id;
                                    $interviewSummaryDivisionHeadData->updated_by = $authId;
                                    $interviewSummaryDivisionHeadData->update();
                                }
                            }
                            // Passport Submit
                            $passportSubmitDeptHead = PassportRequest::where([
                                ['submit_action_by_hr_manager','pending'],
                                ['submit_hr_manager_id',$data->approved_by_id]
                            ])->get();
                            if(count($passportSubmitDeptHead) > 0) {
                                foreach($passportSubmitDeptHead as $passportSubmitDeptHeadData) {
                                    $passportSubmitDeptHeadData->submit_hr_manager_id = $request->handover_to_id;
                                    $passportSubmitDeptHeadData->updated_by = $authId;
                                    $passportSubmitDeptHeadData->update();
                                    $histryHiring = [];
                                    $histryHiring['passport_request_id'] = $passportSubmitDeptHeadData->id;
                                    $histryHiring['icon'] = 'icons8-send-30.png';
                                    $histryHiring['message'] = 'Passport Submit Request send to HR Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                    $createHistryHiring = PassportRequestHistory::create($histryHiring);
                                }
                            }
                            // Passport Release
                            $passportReleaseDeptHead = PassportRelease::where([
                                ['release_action_by_hr_manager','pending'],
                                ['release_hr_manager_id',$data->approved_by_id]
                            ])->get();
                            if(count($passportReleaseDeptHead) > 0) {
                                foreach($passportReleaseDeptHead as $passportReleaseDeptHeadData) {
                                    $passportReleaseDeptHeadData->release_hr_manager_id = $request->handover_to_id;
                                    $passportReleaseDeptHeadData->updated_by = $authId;
                                    $passportReleaseDeptHeadData->update();
                                    $histryHiring = [];
                                    $histryHiring['passport_release_id'] = $passportReleaseDeptHeadData->id;
                                    $histryHiring['icon'] = 'icons8-send-30.png';
                                    $histryHiring['message'] = 'Passport Release Request send to HR Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                    $createHistryHiring = PassportReleaseHistory::create($histryHiring);
                                }
                            }
                            // Liability
                            $liabilityDeptHead = Liability::where([
                                ['action_by_hr_manager','pending'],
                                ['hr_manager_id',$data->approved_by_id]
                            ])->get();
                            if(count($liabilityDeptHead) > 0) {
                                foreach($liabilityDeptHead as $liabilityDeptHeadData) {
                                    $liabilityDeptHeadData->hr_manager_id = $request->handover_to_id;
                                    $liabilityDeptHeadData->updated_by = $authId;
                                    $liabilityDeptHeadData->update();
                                    $histryHiring = [];
                                    $histryHiring['liability_id'] = $liabilityDeptHeadData->id;
                                    $histryHiring['icon'] = 'icons8-send-30.png';
                                    $histryHiring['message'] = 'Liability Request send to HR Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                    $createHistryHiring = LiabilityHistory::create($histryHiring);
                                }
                            }
                            // Leave
                            $leaveDeptHead = Leave::where([
                                ['action_by_hr_manager','pending'],
                                ['hr_manager_id',$data->approved_by_id]
                            ])->get();
                            if(count($leaveDeptHead) > 0) {
                                foreach($leaveDeptHead as $leaveDeptHeadData) {
                                    $leaveDeptHeadData->hr_manager_id = $request->handover_to_id;
                                    $leaveDeptHeadData->updated_by = $authId;
                                    $leaveDeptHeadData->update();
                                    $histryHiring = [];
                                    $histryHiring['leave_id'] = $leaveDeptHeadData->id;
                                    $histryHiring['icon'] = 'icons8-send-30.png';
                                    $histryHiring['message'] = 'Leave Request send to HR Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                    $createHistryHiring = LeaveHistory::create($histryHiring);
                                }
                            }
                            // Joining Report
                             $joiningDeptHead = JoiningReport::where([
                                ['action_by_hr_manager','pending'],
                                ['hr_manager_id',$data->approved_by_id]
                            ])->get();
                            if(count($joiningDeptHead) > 0) {
                                foreach($joiningDeptHead as $joiningDeptHeadData) {
                                    $joiningDeptHeadData->hr_manager_id = $request->handover_to_id;
                                    $joiningDeptHeadData->updated_by = $authId;
                                    $joiningDeptHeadData->update();
                                    $histryHiring = [];
                                    $histryHiring['joining_report_id'] = $joiningDeptHeadData->id;
                                    $histryHiring['icon'] = 'icons8-send-30.png';
                                    $histryHiring['message'] = 'Joining Report Request send to HR Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                    $createHistryHiring = JoiningReportHistory::create($histryHiring);
                                }
                            }
                            // Overtime Application
                            $overtimeDeptHead = OverTime::where([
                                ['action_by_hr_manager','pending'],
                                ['hr_manager_id',$data->approved_by_id]
                            ])->get();
                            if(count($overtimeDeptHead) > 0) {
                                foreach($overtimeDeptHead as $overtimeDeptHeadData) {
                                    $overtimeDeptHeadData->hr_manager_id = $request->handover_to_id;
                                    $overtimeDeptHeadData->updated_by = $authId;
                                    $overtimeDeptHeadData->update();
                                    $histryHiring = [];
                                    $histryHiring['over_times_id'] = $overtimeDeptHeadData->id;
                                    $histryHiring['icon'] = 'icons8-send-30.png';
                                    $histryHiring['message'] = 'Overtime Application Request send to HR Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                    $createHistryHiring = OverTimeHistory::create($histryHiring);
                                }
                            }
                            // Separation Employee Handover
                            $separationDeptHead = Separation::where([
                                ['action_by_hr_manager','pending'],
                                ['hr_manager_id',$data->approved_by_id]
                            ])->get();
                            if(count($separationDeptHead) > 0) {
                                foreach($separationDeptHead as $separationDeptHeadData) {
                                    $separationDeptHeadData->hr_manager_id = $request->handover_to_id;
                                    $separationDeptHeadData->updated_by = $authId;
                                    $separationDeptHeadData->update();
                                    $histryHiring = [];
                                    $histryHiring['separations_id'] = $separationDeptHeadData->id;
                                    $histryHiring['icon'] = 'icons8-send-30.png';
                                    $histryHiring['message'] = 'Separation Employee Handover Request send to HR Manager ( '.$newApprovalPerson->name.' - '.$newApprovalPerson->email.' ) for approval';
                                    $createHistryHiring = SeparationHistory::create($histryHiring);
                                }
                            }
                        } 
                    }
                    $data->approved_by_position = $request->approved_by_position;
                    $data->approved_by_id = $request->approved_by_id;
                    $data->handover_to_id = $request->handover_to_id;
                    $data->updated_by = $authId;
                    $data->update();
                    (new UserActivityController)->createActivity('Master Designation Approvals Edited');
                    $successMessage = "Master Designation Approvals Updated Successfully";
                    DB::commit();
                    return redirect()->route('designation-approvals.index')->with('success',$successMessage);
                }
            } 
            catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg ="Something went wrong! Contact your admin";
                return view('hrm.notaccess',compact('errorMsg'));
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApprovalByPositions $approvalByPositions)
    {
        //
    }
}
