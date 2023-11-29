<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\UserActivityController;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\Masters\MasterJobPosition;

class MasterJobPositionController extends Controller
{
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:master_job_positions',
        ]);
        if ($validator->fails()) {
            $jobPosition['error'] = $validator->messages()->first();
        }
        else {

            $jobPosition = new MasterJobPosition();
            $jobPosition->name = $request->name;
            $jobPosition->created_by = Auth::id();
            $jobPosition->status = 'new';
            $jobPosition->save();
            (new UserActivityController)->createActivity('Create New Master Job Position');
        }
        return response()->json($jobPosition);
    }
}
