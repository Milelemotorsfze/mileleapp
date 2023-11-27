<?php

namespace App\Http\Controllers\HRM\Hiring;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HRM\Hiring\InterviewSummaryReport;
use Carbon\Carbon;
use App\Models\HRM\Hiring\EmployeeHiringRequestHistory;
use App\Http\Controllers\UserActivityController;
use DB;
use Exception;
use Illuminate\Support\Facades\Auth;

class InterviewSummaryReportController extends Controller
{
    public function index() {
        $pendings = InterviewSummaryReport::where('status','pending')->latest()->get();
        $approved = InterviewSummaryReport::where('status','approved')->latest()->get();
        $rejected = InterviewSummaryReport::where('status','rejected')->latest()->get();
        return view('hrm.hiring.interview_summary_report.index',compact('pendings','approved','rejected'));
    }
    public function createOrEdit() {
        // $masterJobPositions = MasterJobPosition::where('status','active')->select('id','name')->get();
        // $masterdepartments = MasterDepartment::where('status','active')->select('id','name')->get();
        // $masterOfficeLocations = MasterOfficeLocation::where('status','active')->select('id','name','address')->get();
        // $reportingToUsers = User::whereNotIn('id',['1','16'])->select('id','name')->get();
        return view('hrm.hiring.interview_summary_report.createOrEdit');
        // ,compact('masterJobPositions','masterdepartments','masterOfficeLocations','reportingToUsers')
    }
    public function requestAction(Request $request) {
        DB::beginTransaction();
        try {
            $message = '';
            $update = InterviewSummaryReport::where('id',$request->id)->first();
            if($request->current_approve_position == 'Team Lead / Reporting Manager') {
                $update->comments_by_department_head = $request->comment;
                $update->department_head_action_at = Carbon::now()->format('Y-m-d H:i:s');
                $update->action_by_department_head = $request->status;
                if($request->status == 'approved') {
                    $update->action_by_division_head = 'pending';
                    $message = 'Interview Summary Report send to Division Head ( '.$update->divisionHeadName->name.' - '.$update->divisionHeadName->email.' ) for approval';
                }
            }
            else if($request->current_approve_position == 'Division Head') {
                $update->comments_by_division_head = $request->comment;
                $update->division_head_action_at = Carbon::now()->format('Y-m-d H:i:s');
                $update->action_by_division_head = $request->status;
                if($request->status == 'approved') {
                    $update->status = 'approved';
                }
            }
            if($request->status == 'rejected') {
                $update->status = 'rejected';
            }
            $update->update();
            $history['hiring_request_id'] = $request->id;
            if($request->status == 'approved') {
                $history['icon'] = 'icons8-thumb-up-30.png';
            }
            else if($request->status == 'rejected') {
                $history['icon'] = 'icons8-thumb-down-30.png';
            }
            $history['message'] = 'Interview Summary Report '.$request->status.' by '.$request->current_approve_position.' ( '.Auth::user()->name.' - '.Auth::user()->email.' )';
            $createHistory = EmployeeHiringRequestHistory::create($history);  
            if($request->status == 'approved' && $message != '') {
                $history['icon'] = 'icons8-send-30.png';
                $history['message'] = $message;
                $createHistory = EmployeeHiringRequestHistory::create($history);
            }
            (new UserActivityController)->createActivity($history['message']);
            DB::commit();
            return response()->json('success');
        } 
        catch (\Exception $e) {
            // info($e);
            DB::rollback();
        }
    }
}
