<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Masters\MasterSpecificIndustryExperience;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserActivityController;

class MasterSpecificIndustryExperienceController extends Controller
{
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:master_specific_industry_experiences',
        ]);
        if ($validator->fails()) {
            $specificIndustry['error'] = $validator->messages()->first();
        }
        else {
            $specificIndustry = new MasterSpecificIndustryExperience();
            $specificIndustry->name = $request->name;
            $specificIndustry->created_by = Auth::id();
            $specificIndustry->status = 'active';
            $specificIndustry->save();
            (new UserActivityController)->createActivity('Create New Specific Industry Experience');
        }
        return response()->json($specificIndustry);
    }
}
