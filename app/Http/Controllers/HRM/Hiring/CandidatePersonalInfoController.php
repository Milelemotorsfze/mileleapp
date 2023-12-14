<?php

namespace App\Http\Controllers\HRM\Hiring;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\HRM\Hiring\InterviewSummaryReport;
use App\Models\Masters\MasterMaritalStatus;
use App\Models\Masters\MasterReligion;
use App\Models\Masters\MasterPersonRelation;
use App\Models\Language;
use App\Models\Country;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserActivityEmail;
use App\Models\UserActivities;
use Illuminate\Support\Facades\Crypt;

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
                $update->update();
                $data['id'] = Crypt::encrypt($update->id);
                $data['email'] = $request->email;
                $data['name'] = 'Dear '.$update->candidate_name.' ,';
                $template['from'] = 'no-reply@milele.com';
                $template['from_name'] = 'Milele Matrix';
                $subject = 'Milele Matrix Candidate Personal Information Form';
                Mail::send(
                        "hrm.hiring.personal_info.email",
                        ["data"=>$data] ,
                        function($msg) use ($data,$template,$subject) {
                            $msg->to($data['email'], $data['name'])
                                ->from($template['from'],$template['from_name'])
                                ->subject($subject);
                        }
                    );
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
    public function sendForm($id) {
        $id = Crypt::decrypt($id);
        $candidate = InterviewSummaryReport::where('id',$id)->first();
        $masterMaritalStatus = MasterMaritalStatus::whereNot('name','Other')->select('id','name')->get();
        $masterReligion = MasterReligion::select('id','name')->get();
        $masterLanguages = Language::select('id','name')->get();
        $masterNationality = Country::select('id','name','nationality')->get();
        $masterRelations = MasterPersonRelation::select('id','name')->get();
        return view('hrm.hiring.personal_info.create',compact('candidate','masterMaritalStatus','masterReligion','masterLanguages','masterNationality','masterRelations'));
    }
    public function storePersonalinfo(Request $request) {
        dd($request->all());
    }
}
