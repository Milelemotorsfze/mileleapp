<?php

namespace App\Http\Controllers\HRM\Hiring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JobDescriptionController extends Controller
{
    public function index() {
        return view('hrm.hiring.job_description.index');
    }
    public function create() {
        return view('hrm.hiring.job_description.create');
    }
    public function edit() {
        return view('hrm.hiring.job_description.edit');
    }
    public function show(string $id) {
        return view('hrm.hiring.job_description.show');
    }
}
