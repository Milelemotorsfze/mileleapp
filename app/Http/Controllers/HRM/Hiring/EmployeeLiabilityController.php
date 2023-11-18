<?php

namespace App\Http\Controllers\HRM\Hiring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeLiabilityController extends Controller
{
    public function index() {
        return view('hrm.hiring.employee_liability.index');
    }
    public function create() {
        return view('hrm.hiring.employee_liability.create');
    }
    public function edit() {
        return view('hrm.hiring.employee_liability.edit');
    }
    public function show(string $id) {
        return view('hrm.hiring.employee_liability.show');
    }
}
