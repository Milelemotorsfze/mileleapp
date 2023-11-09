<?php

namespace App\Http\Controllers\HRM\Hiring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Masters\MasterDeparment;
use App\Models\Masters\MasterExperienceLevel;
use App\Models\Masters\MasterJobPosition;
use App\Models\Masters\MasterOfficeLocation;

class EmployeeHiringRequestController extends Controller
{
    public function index() {
        return view('hrm.hiring.hiring_request.index');
    }
    public function create() {
        $masterdepartments = MasterDeparment::where('status','active')->select('id','name')->get();
        $masterExperienceLevels = MasterExperienceLevel::where('status','active')->select('id','name')->get();
        $masterJobPositions = MasterJobPosition::where('status','active')->select('id','name')->get();
        $masterOfficeLocations = MasterOfficeLocation::where('status','active')->select('id','name')->get();
        return view('hrm.hiring.hiring_request.create',compact('masterdepartments','masterExperienceLevels','masterJobPositions','masterOfficeLocations'));
    }
}
