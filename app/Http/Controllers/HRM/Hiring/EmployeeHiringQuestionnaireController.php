<?php

namespace App\Http\Controllers\HRM\Hiring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HRM\Hiring\EmployeeHiringRequest;
use App\Models\Masters\MasterJobPosition;
use App\Models\Masters\MasterOfficeLocation;
use App\Models\Masters\MasterVisaType;
use App\Models\Masters\MasterRecuritmentSource;
use App\Models\Masters\MasterDeparment;
use App\Models\Masters\MasterExperienceLevel;
use App\Models\Masters\MasterSpecificIndustryExperience;
use App\Models\Country;
use App\Models\Language;
use App\Models\User;

class EmployeeHiringQuestionnaireController extends Controller
{
    public function index() {
        return view('hrm.hiring.questionnaire.index');
    }
    public function create($id) {
        $data = EmployeeHiringRequest::where('id',$id)->first();
        $masterDesignations = MasterJobPosition::select('id','name')->get();
        $masterOfficeLocations = MasterOfficeLocation::where('status','active')->select('id','name','address')->get();
        $masterVisaTypes = MasterVisaType::where('status','active')->select('id','name')->get();
        $masterNationality = Country::select('id','name','nationality')->get();
        $masterLanguages = Language::select('id','name')->get();
        $interviewdByUsers = User::whereNot('id',16)->select('id','name')->get();
        $masterRecuritmentSources = MasterRecuritmentSource::select('id','name')->get();
        $masterDepartments = MasterDeparment::select('id','name')->get();
        $masterExperienceLevels = MasterExperienceLevel::select('id','name','number_of_year_of_experience')->get();
        $masterSpecificIndustryExperiences = MasterSpecificIndustryExperience::select('id','name')->get();
        return view('hrm.hiring.questionnaire.create',compact('data','masterDesignations','masterVisaTypes','masterNationality','masterLanguages','interviewdByUsers',
            'masterRecuritmentSources','masterDepartments','masterExperienceLevels','masterSpecificIndustryExperiences'));
    }
    public function edit() {
        return view('hrm.hiring.questionnaire.edit');
    }
}
 