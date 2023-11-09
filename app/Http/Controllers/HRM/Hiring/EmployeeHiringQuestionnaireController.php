<?php

namespace App\Http\Controllers\HRM\Hiring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeHiringQuestionnaireController extends Controller
{
    public function index() {
        return view('hrm.hiring.questionnaire.index');
    }
    public function create() {
        return view('hrm.hiring.questionnaire.create');
    }
}
