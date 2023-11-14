<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('hrm.employee_relation.dashboard');
    }

    public function create()
    {
        return view('hrm.employee_relation.createchecklistform');
    }
}
