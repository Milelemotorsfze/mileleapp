<?php

namespace App\Http\Controllers\HRM\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HRM\Employee\TicketAllowance;
use Illuminate\Support\Facades\Auth;

class TicketAllowancePOController extends Controller
{
    public function index() {
        $authId = Auth::id();
        if(Auth::user()->hasPermissionForSelectedRole(['view-ticket-listing-of-current-user'])) {
            $datas = TicketAllowance::where('employee_id',$authId)->get();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['view-ticket-listing'])) {
            $datas = TicketAllowance::all();
        }
        return view('hrm.ticketAllowancePO.index',compact('datas'));
    }
    public function create() {
        $employees = User::whereHas('empProfile', function($q) {
            $q = $q->where('type','employee');
        })->with('empProfile.department','empProfile.designation','empProfile.location')->get();
        return view('hrm.ticketAllowancePO.create',compact('employees'));
    }
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'po_year' => 'required',
            'po_number' => 'required',
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
                $createRequest = TicketAllowance::create($input);
                (new UserActivityController)->createActivity('Employee Birthday Gift PO Created');
                $successMessage = "Employee Birthday Gift PO Created Successfully";                   
                DB::commit();
                return redirect()->route('birthday_gift.index')->with('success',$successMessage);
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
        $data = TicketAllowance::where('id',$id)->with('user.empProfile.department','user.empProfile.designation','user.empProfile.location')->first();
        return view('hrm.ticketAllowancePO.edit',compact('employees','data'));
    }
    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'po_year' => 'required',
            'po_number' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $authId = Auth::id();
                $update = TicketAllowance::where('id',$id)->first();
                if($update) {
                    $update->employee_id = $request->employee_id;
                    $update->po_year = $request->po_year;
                    $update->po_number = $request->po_number;
                    $update->updated_by = $authId;
                    $update->update();
                }
                (new UserActivityController)->createActivity('Employee Birthday Gift PO Updated');
                $successMessage = "Employee Birthday Gift PO Updated Successfully";                   
                DB::commit();
                return redirect()->route('birthday_gift.index')->with('success',$successMessage);
            }
            catch (\Exception $e) {
                DB::rollback();
                dd($e);
            }
        }       
    }
    public function show($id) {
        $data = TicketAllowance::where('id',$id)->first();
        $previous = TicketAllowance::where('id', '<', $id)->max('id');
        $next = TicketAllowance::where('id', '>', $id)->min('id');
        $all = TicketAllowance::where('employee_id',$data->employee_id)->latest('')->get();
        return view('hrm.ticketAllowancePO.show',compact('data','previous','next','all'));
    }
}
