<?php

namespace App\Http\Controllers\HRM\Hiring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeLeaveController extends Controller
{
    public function index() {
        return view('hrm.hiring.employee_leave.index');
    }
    public function create() {
        return view('hrm.hiring.employee_leave.create');
    }
    public function edit() {
        return view('hrm.hiring.employee_leave.edit');
    }
    public function show(string $id) {
        return view('hrm.hiring.employee_leave.show');
    }
}
