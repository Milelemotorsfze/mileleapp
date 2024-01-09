<?php

namespace App\Http\Controllers\HRM\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HRM\Employee\PassportRelease;
use App\Models\HRM\Employee\PassportReleaseHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PassportReleaseController extends Controller
{
    public function index() {
        $pendings = PassportRelease::where('release_submit_status','pending')->latest()->get();
        $approved = PassportRelease::where('release_submit_status','approved')->latest()->get();
        $rejected = PassportRelease::where('release_submit_status','rejected')->latest()->get();
        return view('hrm.passport.passport_release.index',compact('pendings','approved','rejected'));
    }
    public function requestAction(Request $request) {
        $message = '';
        $update = PassportRelease::where('id',$request->id)->first();
        // employee -------> Reporting Manager  ------------>Division Head--------->HR Manager       
        if($request->current_approve_position == 'Employee') {
            $update->release_comments_by_employee = $request->comment;
            $update->release_employee_action_at = Carbon::now()->format('Y-m-d H:i:s');
            $update->release_action_by_employee = $request->status;
            if($request->status == 'approved') {
                $update->release_action_by_department_head = 'pending';
                $message = 'Employee passport release request send to Reporting Manager ( '.$update->hrManager->name.' - '.$update->hrManager->email.' ) for approval';
            }
        }
        else if($request->current_approve_position == 'Reporting Manager') {
            $update->release_comments_by_department_head = $request->comment;
            $update->release_department_head_action_at = Carbon::now()->format('Y-m-d H:i:s');
            $update->release_action_by_department_head = $request->status;
            if($request->status == 'approved') {
                $update->release_action_by_division_head = 'pending';
                $message = 'Employee passport release request send to Division Head ( '.$update->divisionHead->name.' - '.$update->divisionHead->email.' ) for approval';
            }
        }
        else if($request->current_approve_position == 'Division Head') {
            $update->release_comments_by_division_head = $request->comment;
            $update->release_division_head_action_at = Carbon::now()->format('Y-m-d H:i:s');
            $update->release_action_by_division_head = $request->status;
            if($request->status == 'approved') {
                $update->release_action_by_hr_manager = 'pending';
                $message = 'Employee passport release request send to HR Manager ( '.$update->hrManager->name.' - '.$update->hrManager->email.' ) for approval';
            }
        }
        else if($request->current_approve_position == 'HR Manager') {
            $update->release_comments_by_hr_manager = $request->comment;
            $update->release_hr_manager_action_at = Carbon::now()->format('Y-m-d H:i:s');
            $update->release_action_by_hr_manager = $request->status;
            if($request->status == 'approved') {
                $update->release_status = 'approved';
                $emp = EmployeeProfile::where('id',$update->employee_id)->first();
                $emp->passport_status = 'with_employee';
                $emp->update();
            }
        }
        if($request->status == 'rejected') {
            $update->release_status = 'rejected';
        }
        $update->update();
        $history['passport_release_id'] = $request->id;
        if($request->status == 'approved') {
            $history['icon'] = 'icons8-thumb-up-30.png';
        }
        else if($request->status == 'rejected') {
            $history['icon'] = 'icons8-thumb-down-30.png';
        }
        $history['message'] = 'Employee passport release request '.$request->status.' by '.$request->current_approve_position.' ( '.Auth::user()->name.' - '.Auth::user()->email.' )';
        $createHistory = PassportReleaseHistory::create($history);  
        if($request->status == 'approved' && $message != '') {
            $history['icon'] = 'icons8-send-30.png';
            $history['message'] = $message;
            $createHistory = PassportReleaseHistory::create($history);
        }
        (new UserActivityController)->createActivity($history['message']);
        return response()->json('success');
        // ,'New Employee Hiring Request '.$request->status.' Successfully'
    }
}
