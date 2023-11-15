<?php

namespace App\Http\Controllers\HRM\Hiring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PassportRequestController extends Controller
{
    public function index() {
        return view('hrm.hiring.passport_request.index');
    }
    public function create() {
        return view('hrm.hiring.passport_request.create');
    }
    public function edit() {
        return view('hrm.hiring.passport_request.edit');
    }
    public function show(string $id) {
        return view('hrm.hiring.passport_request.show');
    }
}
