<?php

namespace App\Http\Controllers\HRM\Hiring;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CandidatePersonalInfoController extends Controller
{
    public function create() {
        return view('hrm.hiring.personal_info.create');
    }
    public function sendEmail(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            // DB::beginTransaction();
            // try {
                $update = InterviewSummaryReport::where('id',$request->id)->first();
                if($update) {
                    $update->email = $request->email;
                }
                // $update->update();
                // DB::commit();
                return redirect()->route('interview-summary-report.index')
                                    ->with('success','Personal Information Form Successfully Send To Candidate');
            // } 
            // catch (\Exception $e) {
                // DB::rollback();
                // dd($e);
            // }
        }
    }
}
