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
use App\Models\HRM\Employee\EmployeeProfile;
use App\Models\HRM\Employee\EmployeeSpokenLanguage;
use App\Models\HRM\Employee\Children;
use App\Models\HRM\Employee\UAEEmergencyContact;
use App\Models\HRM\Employee\HomeCountryEmergencyContact;
use App\Models\EmpDoc;
use DB;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\HRM\Approvals\ApprovalByPositions;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\File;


class CandidatePersonalInfoController extends Controller
{
    public function sendDocsEmail(Request $request) {
        $status = $message = '';
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $update = InterviewSummaryReport::with('candidateDetails')->where('id',$request->id)->first();
                if($update) {
                    $update->email = $request->email;
                }
                $update->update();
                $createEmp = EmployeeProfile::where('interview_summary_id',$request->id)->first();
                if($createEmp) {
                    $createEmp->documents_form_send_at = Carbon::now();
                    $createEmp->documents_form_send_by = Auth::id();
                    $createEmp->update();
                }
                else {
                    $input['documents_form_send_by'] = Auth::id();
                    $input['created_by'] = Auth::id();
                    $input['type'] = 'candidate';
                    $input['interview_summary_id'] = $request->id;
                    $input['documents_form_send_at'] = Carbon::now();
                    $createEmp = EmployeeProfile::create($input);  
                }
                if(($update && $update->email == '') OR 
                    ($update && $update->email != '' && !isset($update->candidateDetails)) OR 
                    ($update && $update->email != '' && isset($update->candidateDetails) && $update->candidateDetails->documents_verified_at == '')) {
                $data['comment'] = '';
                if($request->comment) {
                    $data['comment'] = $request->comment;
                }
                $data['id'] = Crypt::encrypt($update->id);
                $data['send_by'] = Auth::user()->name;
                $data['email'] = $request->email;
                $data['name'] = 'Dear '.$update->candidate_name.' ,';
                $template['from'] = 'no-reply@milele.com';
                $template['from_name'] = 'Milele Matrix';
                $subject = 'Milele - Candidate Documents Request Form';
                Mail::send(
                        "hrm.hiring.documents.email",
                        ["data"=>$data] ,
                        function($msg) use ($data,$template,$subject) {
                            $msg->to($data['email'], $data['name'])
                                ->from($template['from'],$template['from_name'])
                                ->subject($subject);
                        }
                    );
                    $status = 'success';
                    $message = 'Documents Request Form Successfully Send To Candidate';
                }
                else {
                    $status = 'error';
                    $message = "can't send candidate documents upload form ,because this candidate's documents already verified ";
                }
                DB::commit();
                return redirect()->back()
                                    ->with($status,$message);
            } 
            catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg ="Something went wrong! Contact your admin";
                return view('hrm.notaccess',compact('errorMsg'));
            }
        }
    }
    public function createOfferLetter(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'candidate_name' => 'required',
            'passport_number' => 'required',
            'contact_number' => 'required',
            'email' => 'required',
            'probation_duration_in_months' => 'required',
            'basic_salary' => 'required',
            'other_allowances' => 'required',
            'total_salary' => 'required',
            'designation_id' =>'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $offerNo = '';
                $offerCode = '';
                $data = InterviewSummaryReport::where('id',$request->id)->first();
                if($data && $data->offer_letter_verified_at == '' && $data->offer_letter_send_at == '') {
                    if($data && $data->offer_letter_verified_at == '' && $data->offer_letter_send_at == '') {
                        $data->candidate_name = $request->candidate_name;
                        $data->email = $request->email;
                    }
                    $data->update();
                    $latestOfferLetterCode = EmployeeProfile::withTrashed()->orderBy('offer_letter_no', 'desc')->first();
                    $length = 5;
                    $offset = 5;
                    $prefix = "";
                    if($latestOfferLetterCode){
                        $latestUUID =  $latestOfferLetterCode->offer_letter_no; 
                        $newCode =  str_pad($latestUUID + 1, 5, 0, STR_PAD_LEFT);
                        $offerNo =  $prefix.$newCode;
                    }else{
                        $offerNo = $prefix.'00001';
                    }                      
                    $offerCode = 'MM/OL/'.$offerNo.'/'.Carbon::now()->format('Y');
                    $emp = EmployeeProfile::where('interview_summary_id',$request->id)->first();
                    if($emp && $emp->interviewSummary->offer_letter_verified_at == '' && $emp->interviewSummary->offer_letter_send_at == '') {
                        $emp->passport_number = $request->passport_number;
                        $emp->contact_number = $request->contact_number['full'];
                        $emp->probation_duration_in_months = $request->probation_duration_in_months;
                        $emp->basic_salary = $request->basic_salary;
                        $emp->other_allowances = $request->other_allowances;
                        $emp->total_salary = $request->total_salary;
                        $emp->designation_id = $request->designation_id;
                        if($emp->offer_letter_no == NULL && $emp->offer_letter_code == NULL) {
                            $emp->offer_letter_no = $offerNo;
                            $emp->offer_letter_code = $offerCode;
                        }
                        $emp->update();
                    }    
                    $inwords['basic_salary'] = $this->decimalNumberInWords($request->basic_salary);
                    $inwords['other_allowances'] = $this->decimalNumberInWords($request->other_allowances);
                    $inwords['total_salary'] = $this->decimalNumberInWords($request->total_salary);
                    $hr = ApprovalByPositions::where('approved_by_position','HR Manager')->first();
                    $data->isAuth = 1;
                     DB::commit();
                     return view('hrm.hiring.offer_letter.offerLetter',compact('data','inwords','hr'));
                }
                else if($data && ($data->offer_letter_verified_at != '' OR $data->offer_letter_send_at != '')){
                    $errorMsg ="Cannot generate! The offer letter for this candidate has already been generated.";
                    return view('hrm.notaccess',compact('errorMsg'));
                }
            } 
            catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->withInput();
                info($e);
                $errorMsg ="Something went wrong! Contact your admin";
                return view('hrm.notaccess',compact('errorMsg'));
            }
        }
    }
    public function decimalNumberInWords($number) {
        $no = floor($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array('0' => '', '1' => 'One', '2' => 'Two', '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six', '7' => 'Seven', '8' => 'Eight', 
                        '9' => 'Nine', '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve', '13' => 'Thirteen', '14' => 'Fourteen', '15' => 'Fifteen', 
                        '16' => 'Sixteen', '17' => 'Seventeen', '18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty', '30' => 'Thirty', '40' => 'Forty', 
                        '50' => 'Fifty', '60' => 'Sixty', '70' => 'Seventy', '80' => 'Eighty', '90' => 'Ninety');
        $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred
                    :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
            } else $str[] = null;
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ?
            ". " . $words[$point / 10] . " " . 
                $words[$point = $point % 10] : '';
        if($points == "") {
            $inWords =  $result . "Dirham  ";
        }
        else {
            $inWords =  $result . "Dirham  " . $points . " Fils";
        }
        return $inWords;
    }
    public function sendEmail(Request $request) {
        $canSendOfferLetterLink = 'yes';
        $canSendPersonalInfoLink = 'yes';
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $update = InterviewSummaryReport::where('id',$request->id)->first();
                if($update) {
                    $update->email = $request->email;
                }
                $update->update();
                $emp = EmployeeProfile::where('interview_summary_id',$request->id)->first();
                if($emp && $emp->personal_information_verified_at == '' || $update->offer_letter_verified_at == '') {
                    if($emp->personal_information_created_at != NULL && $emp->personal_information_send_at != NULL && 
                    $emp->personal_information_send_at < $emp->personal_information_created_at) {
                        $canSendPersonalInfoLink = 'no';
                    }
                    if($emp->personal_information_created_at != NULL && $emp->offer_signed_at != NULL && 
                    $emp->offer_signed_at < $emp->personal_information_created_at) {
                        $canSendOfferLetterLink = 'no';
                    }
                    if($emp) {
                        $emp->personal_information_send_at = Carbon::now();
                        $emp->update();
                    }               
                    $data['comment'] = '';
                    if($request->comment) {
                        $data['comment'] = $request->comment;
                    }
                    $data['id'] = Crypt::encrypt($update->id);
                    $data['email'] = $request->email;
                    $data['send_by'] = Auth::user()->name;
                    $data['name'] = 'Dear '.$update->candidate_name.' ,';
                    $data['canSendOfferLetterLink'] = $canSendOfferLetterLink;
                    $data['canSendPersonalInfoLink'] = $canSendPersonalInfoLink;
                    $template['from'] = 'no-reply@milele.com';
                    $template['from_name'] = 'Milele Matrix';
                    $subject = 'Milele - Candidate Personal Information Form';
                    Mail::send(
                            "hrm.hiring.personal_info.email",
                            ["data"=>$data] ,
                            function($msg) use ($data,$template,$subject) {
                                $msg->to($data['email'], $data['name'])
                                    ->from($template['from'],$template['from_name'])
                                    ->subject($subject);
                            }
                        );
                        $status ='success';
                $msg ='Offer Letter and Personal Information Form Successfully Send To Candidate';   
                }
                else {
                    $status ='error';
                    $msg ="can't send offer letter and personal information link ,because this candidate's offer letter or personal information already verified ";
                }
               DB::commit();
                return redirect()->back()
                                    ->with($status,$msg);
            } 
            catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg ="Something went wrong! Contact your admin";
                return view('hrm.notaccess',compact('errorMsg'));
            }
        }
    }
    public function sendForm($id) {
        $id = Crypt::decrypt($id);
        $candidate = InterviewSummaryReport::where('id',$id)->with('candidateDetails.candidateChildren','candidateDetails.emergencyContactUAE'
        ,'candidateDetails.emergencyContactHomeCountry','candidateDetails.candidatePassport','candidateDetails.candidateNationalId'
        ,'candidateDetails.candidateEduDocs','candidateDetails.candidateProDipCerti')->first();
        $masterMaritalStatus = MasterMaritalStatus::whereNot('name','Other')->select('id','name')->get();
        $masterReligion = MasterReligion::select('id','name')->get();
        $masterLanguages = Language::select('id','name')->get();
        $masterNationality = Country::select('id','name','nationality')->get();
        $masterRelations = MasterPersonRelation::select('id','name')->get();
        if(($candidate && !isset($candidate->candidateDetails)) OR ($candidate && isset($candidate->candidateDetails) && $candidate->candidateDetails->documents_verified_at == '')) {
            return view('hrm.hiring.documents.create',compact('candidate','masterMaritalStatus','masterReligion','masterLanguages','masterNationality','masterRelations'));
        }
        else {
            $successMessage = 'Sorry ! This Link is expied.';
            return view('hrm.hiring.documents.successPersonalinfo',compact('successMessage'));
        }
    }
    public function sendPersonalForm($id) {
        $id = Crypt::decrypt($id);
        $candidate = InterviewSummaryReport::where('id',$id)->with('candidateDetails.candidateChildren','candidateDetails.emergencyContactUAE'
        ,'candidateDetails.emergencyContactHomeCountry','candidateDetails.candidatePassport','candidateDetails.candidateNationalId'
        ,'candidateDetails.candidateEduDocs','candidateDetails.candidateProDipCerti')->first();
        $masterMaritalStatus = MasterMaritalStatus::whereNot('name','Other')->select('id','name')->get();
        $masterReligion = MasterReligion::select('id','name')->get();
        $masterLanguages = Language::select('id','name')->get();
        $masterNationality = Country::select('id','name','nationality')->get();
        $masterRelations = MasterPersonRelation::select('id','name')->get();
        if($candidate && isset($candidate->candidateDetails) && $candidate->candidateDetails->personal_information_verified_at == '') {
            return view('hrm.hiring.personal_info.create',compact('candidate','masterMaritalStatus','masterReligion','masterLanguages','masterNationality','masterRelations'));
        }
        else {
            $successMessage = 'Sorry ! This Link is expied.';
            return view('hrm.hiring.documents.successPersonalinfo',compact('successMessage'));
        }
    }
    public function storeDocs(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $candidate = InterviewSummaryReport::where('id',$request->id)->first();
                if(($candidate && !isset($candidate->candidateDetails)) OR ($candidate && isset($candidate->candidateDetails) && $candidate->candidateDetails->documents_verified_at == '')) {
                    $createEmp = EmployeeProfile::where('interview_summary_id',$request->id)->first(); 
                    if($request->passport_size_photograph) {                       
                        $photoFileName = auth()->id() . '_' . time() . '.'. $request->passport_size_photograph->extension();
                        $type = $request->passport_size_photograph->getClientMimeType();
                        $size = $request->passport_size_photograph->getSize();
                        $request->passport_size_photograph->move(public_path('hrm/employee/photo'), $photoFileName);
                    }
                    if($request->resume) {                       
                        $resumeFileName = auth()->id() . '_' . time() . '.'. $request->resume->extension();
                        $type = $request->resume->getClientMimeType();
                        $size = $request->resume->getSize();
                        $request->resume->move(public_path('hrm/employee/resume'), $resumeFileName);                                              
                    }
                    if($request->visa) {                       
                        $visaFileName = auth()->id() . '_' . time() . '.'. $request->visa->extension();
                        $type = $request->visa->getClientMimeType();
                        $size = $request->visa->getSize();
                        $request->visa->move(public_path('hrm/employee/visa'), $visaFileName);                        
                    }
                    if($request->emirates_id) {                 
                        $emiratesIdFileName = auth()->id() . '_' . time() . '.'. $request->emirates_id->extension();
                        $type = $request->emirates_id->getClientMimeType();
                        $size = $request->emirates_id->getSize();
                        $request->emirates_id->move(public_path('hrm/employee/emirates_id'), $emiratesIdFileName);                        
                    }
                    if(!$createEmp) {
                        $input = $request->all();  
                        $input['image_path'] = $photoFileName;
                        $input['resume'] = $resumeFileName;
                        $input['visa'] = $visaFileName;
                        if(isset($emiratesIdFileName)) {
                            $input['emirates_id_file'] = $emiratesIdFileName;                  
                        }
                        $input['type'] = 'candidate';
                        $input['interview_summary_id'] = $request->id;
                        $input['documents_form_submit_at'] = Carbon::now();
                        $createEmp = EmployeeProfile::create($input);                      
                    }
                    else { 
                        if(isset($photoFileName)) {
                            $createEmp->image_path = $photoFileName;
                        }
                        else if($request->is_photo_delete == 1) {
                            $createEmp->image_path =  NULL;
                        }
                        if(isset($resumeFileName)) {
                            $createEmp->resume = $resumeFileName;
                        }
                        else if($request->is_resume_delete == 1) {
                            $createEmp->resume = NULL;
                        }
                        if(isset($visaFileName)) {
                            $createEmp->visa = $visaFileName;
                        }
                        else if($request->is_visa_delete == 1) {
                            $createEmp->visa =  NULL;
                        }
                        if(isset($emiratesIdFileName)) {
                            $createEmp->emirates_id_file = $emiratesIdFileName;
                        }
                        else if($request->is_emirates_id_delete == 1) {
                            $createEmp->emirates_id_file = NULL;
                        }
                        $createEmp->documents_form_submit_at = Carbon::now();
                        $createEmp->update();
                    }
                    if(isset($request->deleted_files)) {
                        if(count($request->deleted_files) > 0) {
                            foreach($request->deleted_files as $deleted_file) {
                                
                                $deleteFile = EmpDoc::where('id',$deleted_file)->first();
                                info($deleteFile);
                                if($deleteFile) {
                                    $deleteFile->delete();
                                }
                            }
                        }
                    }
                    if ($request->hasFile('passport')) {
                        foreach ($request->file('passport') as $file) {
                            $extension = $file->getClientOriginalExtension();
                            $fileName = time().'_'.$file->getClientOriginalName();
                            $destinationPath = 'hrm/employee/passport';
                            $file->move($destinationPath, $fileName);        
                            $CandidateDocument = new EmpDoc();
                            $CandidateDocument->candidate_id = $createEmp->id;
                            $CandidateDocument->document_name = 'passport';
                            $CandidateDocument->document_path = $fileName;
                            $CandidateDocument->save();
                        }
                    }
                    if ($request->hasFile('national_id')) {
                        foreach ($request->file('national_id') as $file) {
                            $extension = $file->getClientOriginalExtension();
                            $fileName = time().'_'.$file->getClientOriginalName();
                            $destinationPath = 'hrm/employee/national_id';
                            $file->move($destinationPath, $fileName);        
                            $CandidateDocument = new EmpDoc();
                            $CandidateDocument->candidate_id = $createEmp->id;
                            $CandidateDocument->document_name = 'national_id';
                            $CandidateDocument->document_path = $fileName;
                            $CandidateDocument->save();
                        }
                    }
                    if ($request->hasFile('educational_docs')) {
                        foreach ($request->file('educational_docs') as $file) {
                            $extension = $file->getClientOriginalExtension();
                            $fileName = time().'_'.$file->getClientOriginalName();
                            $destinationPath = 'hrm/employee/educational_docs';
                            $file->move($destinationPath, $fileName);        
                            $CandidateDocument = new EmpDoc();
                            $CandidateDocument->candidate_id = $createEmp->id;
                            $CandidateDocument->document_name = 'educational_docs';
                            $CandidateDocument->document_path = $fileName;
                            $CandidateDocument->save();
                        }
                    }
                    if ($request->hasFile('professional_diploma_certificates')) {
                        foreach ($request->file('professional_diploma_certificates') as $file) {
                            $extension = $file->getClientOriginalExtension();
                            $fileName = time().'_'.$file->getClientOriginalName();
                            $destinationPath = 'hrm/employee/professional_diploma_certificates';
                            $file->move($destinationPath, $fileName);        
                            $CandidateDocument = new EmpDoc();
                            $CandidateDocument->candidate_id = $createEmp->id;
                            $CandidateDocument->document_name = 'professional_diploma_certificates';
                            $CandidateDocument->document_path = $fileName;
                            $CandidateDocument->save();
                        }
                    }
                    $successMessage = 'Candidate Documents Request Form Submitted Successfully.';
                }   
                else  {
                    $successMessage = "can't update this candidate documents ,because this candidate's documents already verified ";;
                }            
           DB::commit();
         
           return view('hrm.hiring.documents.successPersonalinfo',compact('candidate','successMessage'));
            } 
            catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg ="Something went wrong! Contact your admin";
                return view('hrm.notaccess',compact('errorMsg'));
            }
        }
    }
    public function storePersonalinfo(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'first_name' => 'required',
            'last_name' => 'required',
            'name_of_father' => 'required',
            'name_of_mother' => 'required',
            'marital_status' => 'required',
            'passport_number' => 'required',
            'passport_expiry_date' => 'required',
            'educational_qualification' => 'required',
            'year_of_completion' => 'required',
            'religion' => 'required',
            'dob' => 'required',
            'language_id' => 'required',
            'address_uae' => 'required',
            'residence_telephone_number' => 'required',
            'contact_number' => 'required',
            'personal_email_address' => 'required',
            'ecu' => 'required',
            'ech' => 'required',
            'signature' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $candidate = InterviewSummaryReport::where('id',$request->id)->first();
                if($candidate && $request->signature) {
                    $candidate->pif_sign = $request->signature;
                    $candidate->update();
                    $createEmp = EmployeeProfile::where('interview_summary_id',$request->id)->first();  
                    if(!$createEmp OR ($createEmp && $createEmp->personal_information_verified_at == '')) {                             
                        if(!$createEmp) {
                            $input = $request->all(); 
                            $input['residence_telephone_number'] = $request->residence_telephone_number['full'];
                            $input['contact_number'] = $request->contact_number['full'];
                            $input['type'] = 'candidate';
                            $input['interview_summary_id'] = $request->id;
                            $input['designation_id'] = $candidate->employeeHiringRequest->questionnaire->designation->id;
                            $input['department_id'] = $candidate->employeeHiringRequest->questionnaire->department->id;
                            $input['gender'] = $candidate->gender;
                            $input['nationality'] = $candidate->nationality;
                            $input['personal_information_created_at'] = Carbon::now();
                            $createEmp = EmployeeProfile::create($input);                      
                        }
                        else { 
                            $createEmp->first_name = $request->first_name;
                            $createEmp->last_name = $request->last_name;
                            $createEmp->name_of_father = $request->name_of_father;
                            $createEmp->name_of_mother = $request->name_of_mother;
                            $createEmp->marital_status = $request->marital_status;
                            $createEmp->passport_number = $request->passport_number;
                            $createEmp->passport_expiry_date = $request->passport_expiry_date;
                            $createEmp->educational_qualification = $request->educational_qualification;
                            $createEmp->year_of_completion = $request->year_of_completion;
                            $createEmp->religion = $request->religion;
                            $createEmp->dob = $request->dob;
                            $createEmp->address_uae = $request->address_uae;
                            $createEmp->personal_email_address = $request->personal_email_address;
                            $createEmp->spouse_name = $request->spouse_name;
                            $createEmp->spouse_passport_number = $request->spouse_passport_number;
                            $createEmp->spouse_passport_expiry_date = $request->spouse_passport_expiry_date;
                            $createEmp->spouse_dob = $request->spouse_dob;
                            $createEmp->spouse_nationality = $request->spouse_nationality;
                            $createEmp->residence_telephone_number = $request->residence_telephone_number['full'];
                            $createEmp->contact_number = $request->contact_number['full'];
                            $createEmp->personal_information_created_at = Carbon::now();
                            $createEmp->department_id = $candidate->employeeHiringRequest->questionnaire->department->id;
                            $createEmp->designation_id = $candidate->employeeHiringRequest->questionnaire->designation->id;
                            $createEmp->update();
                        }
                        $oldLangs = EmployeeSpokenLanguage::where('candidate_id',$createEmp->id)->get();
                        foreach($oldLangs as $oldLang) {
                            $oldLang->delete();
                        }
                        if(isset($request->language_id)) {
                            if(count($request->language_id) > 0) {
                                $inputLang['candidate_id'] = $createEmp->id;
                                foreach($request->language_id as $language_id) {
                                    $inputLang['language_id'] = $language_id;
                                    $createLang = EmployeeSpokenLanguage::create($inputLang);                            
                                }
                            }
                        }
                        $oldChilds = Children::where('candidate_id',$createEmp->id)->get();
                        foreach($oldChilds as $oldChild) {
                            $oldChild->delete();
                        }
                        if(isset($request->child)) {
                            if(count($request->child) > 0) {
                                foreach($request->child as $child) {  
                                    $inputChild = [];  
                                    $inputChild['candidate_id'] = $createEmp->id;                      
                                    $inputChild['child_name'] = $child['child_name'];
                                    $inputChild['child_passport_number'] = $child['child_passport_number'];
                                    $inputChild['child_passport_expiry_date'] = $child['child_passport_expiry_date'];
                                    $inputChild['child_dob'] = $child['child_dob'];
                                    $inputChild['child_nationality'] = $child['child_nationality'];
                                    if($inputChild['child_name'] != NULL && $inputChild['child_dob'] != NULL && $inputChild['child_nationality'] != NULL) {
                                        $createChild = Children::create($inputChild);                            
                                    }
                                }
                            }
                        }
                        $oldEcus = UAEEmergencyContact::where('candidate_id',$createEmp->id)->get();
                        foreach($oldEcus as $oldEcu) {
                            $oldEcu->delete();
                        }
                        if(isset($request->ecu)) {
                            if(count($request->ecu) > 0) {
                                foreach($request->ecu as $ecu) {  
                                    $inputEcu = [];  
                                    $inputEcu['candidate_id'] = $createEmp->id;                      
                                    $inputEcu['name'] = $ecu['name'];
                                    $inputEcu['relation'] = $ecu['relation'];
                                    $inputEcu['contact_number'] = $ecu['contact_number']['full'];
                                    $inputEcu['alternative_contact_number'] = $ecu['alternative_contact_number']['full'];
                                    $inputEcu['email_address'] = $ecu['email_address'];
                                    $createEcu = UAEEmergencyContact::create($inputEcu);                            
                                }
                            }
                        }
                        $oldEchs = HomeCountryEmergencyContact::where('candidate_id',$createEmp->id)->get();
                        foreach($oldEchs as $oldEch) {
                            $oldEch->delete();
                        }
                        if(isset($request->ech)) {
                            if(count($request->ech) > 0) {
                                foreach($request->ech as $ech) {  
                                    $inputEch = [];  
                                    $inputEch['candidate_id'] = $createEmp->id;                      
                                    $inputEch['name'] = $ech['name'];
                                    $inputEch['relation'] = $ech['relation'];
                                    $inputEch['contact_number'] = $ech['contact_number']['full'];
                                    $inputEch['alternative_contact_number'] = $ech['alternative_contact_number']['full'];
                                    $inputEch['email_address'] = $ech['email'];
                                    $inputEch['home_country_address'] = $ech['home_country_address'];
                                    $createEch = HomeCountryEmergencyContact::create($inputEch);                            
                                }
                            }
                        }
                        $successMessage = 'Candidate Personal Information Form Submitted Successfully.';

                    } 
                    else {
                        $successMessage = "can't update this candidate personal information ,because it is already verified ";
                    }
                }               
           DB::commit();
           return view('hrm.hiring.personal_info.successPersonalinfo',compact('candidate','successMessage'));
            } 
            catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg ="Something went wrong! Contact your admin";
                return view('hrm.notaccess',compact('errorMsg'));
            }
        }
    }
    public function docsVerified(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $authId = Auth::id();
                $candidate = EmployeeProfile::where('interview_summary_id',$request->id)->first();
                if($candidate && $candidate->documents_verified_at == '') {
                    $candidate->documents_verified_at = Carbon::now();
                    $candidate->documents_verified_by = $authId;
                    $candidate->update();
                    DB::commit();
                    return response()->json('success');
                }
                else if($candidate && $candidate->documents_verified_at != '') {
                    DB::commit();
                    return response()->json('error');
                }
            } 
            catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg ="Something went wrong! Contact your admin";
                return view('hrm.notaccess',compact('errorMsg'));
            }
        }
    }
    public function offerLetterSignVerified(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $authId = Auth::id();
                $data = InterviewSummaryReport::where('id',$request->id)->first();
                if($data && $data->offer_letter_verified_at == '') {

                    if($data) {
                        $data->offer_letter_verified_at = Carbon::now();
                        $data->offer_letter_verified_by = $authId;
                        $data->update();
                    }
                    $hr = ApprovalByPositions::where('approved_by_position','HR Manager')->first();
                    $emp = EmployeeProfile::where('interview_summary_id',$request->id)->first();
                    $inwords['basic_salary'] = $this->decimalNumberInWords($emp->basic_salary);
                    $inwords['other_allowances'] = $this->decimalNumberInWords($emp->other_allowances);
                    $inwords['total_salary'] = $this->decimalNumberInWords($emp->total_salary);
                    $data->isAuth = 2;
                    $pdf = PDF::loadView('pdf.sample', compact('data','inwords','hr'));             
                    $data['name'] = 'Dear '.$data->candidate_name.' ,';
                    $template['from'] = 'no-reply@milele.com';
                    $template['from_name'] = 'Milele Matrix';
                    $subject = 'Milele - Candidate Job Offer Letter Document';
                    $filename = 'OL_'.$data->id.date('Y_m_d').'.pdf';
                    $directory = public_path('hrm/employee/offer_letter');
                    \Illuminate\Support\Facades\File::makeDirectory($directory, $mode = 0777, true, true);
                    $pdf->save($directory . '/' . $filename);
                    if($emp) {
                        $emp->offer_letter_fileName = $filename;
                        $emp->update();
                    }
                    $attachPath = $directory . '/' . $filename;
                    Mail::send(
                            "hrm.hiring.offer_letter.sendOfferLetterDocsEmail",
                            ["data"=>$data] ,
                            function($msg) use ($data,$template,$subject,$attachPath) {
                                $msg->to($data['email'], $data['name'])
                                    ->from($template['from'],$template['from_name'])
                                    ->subject($subject)
                                    ->attach($attachPath);
                            }
                        );         
                    DB::commit();
                    return response()->json('success');
                }
                else if($data && $data->offer_letter_verified_at != '') {
                    return response()->json('error');
                }
            } 
            catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg ="Something went wrong! Contact your admin";
                return view('hrm.notaccess',compact('errorMsg'));
            }
        }
    }
    public function personalInfoVerified(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $authId = Auth::id();
                $candidate = EmployeeProfile::where('interview_summary_id',$request->id)->first();
                if($candidate && $candidate->personal_information_verified_at == '') {
                    $candidate->personal_information_verified_at = Carbon::now();
                    $candidate->personal_information_verified_by = $authId;
                    $candidate->update();
                    DB::commit();
                    return response()->json('success');
                }
                else if($candidate && $candidate->personal_information_verified_at != '') {
                    DB::commit();
                    return response()->json('error');
                }
            } 
            catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg ="Something went wrong! Contact your admin";
                return view('hrm.notaccess',compact('errorMsg'));
            }
        }
    }
    public function getCandidatePersonalInfo() {

        $pending = EmployeeProfile::where([
            ['type','candidate'],
            ['personal_information_verified_at',NULL],
            ['personal_information_created_at','!=',NULL],
        ])->whereColumn('personal_information_send_at', '<', 'personal_information_created_at')->get();
        $resend = EmployeeProfile::where([
            ['type','candidate'],
            ['personal_information_verified_at',NULL],
            ['personal_information_created_at','!=',NULL],
        ])->whereColumn('personal_information_send_at', '>', 'personal_information_created_at')->get();
        $verified = EmployeeProfile::where([
            ['type','candidate'],
            ['personal_information_verified_at','!=',NULL],
            ['personal_information_created_at','!=',NULL],
        ])->get();
        return view('hrm.hiring.personal_info.verifyOrResend',compact('pending','resend','verified'));
    }
    public function getCandidateDocsInfo() {
        $pending = EmployeeProfile::where([
            ['type','candidate'],
            ['documents_verified_at',NULL],
            ['documents_form_send_at','!=',NULL],
            ['documents_form_submit_at','!=',NULL],
            // ['documents_form_send_at','<','documents_form_submit_at']
        ])
        ->whereColumn('documents_form_send_at', '<', 'documents_form_submit_at')
        ->get();
        $verified = EmployeeProfile::where([
            ['type','candidate'],
            ['documents_verified_at','!=',NULL],
        ])->get();
        return view('hrm.hiring.documents.verifyOrResend',compact('pending','verified'));
    }
    public function getOfferLetterList() {
        $pending = EmployeeProfile::where([
            ['type','candidate'],
            ['documents_verified_at','!=',NULL],
        ])->whereHas('interviewSummary', function($q){
            $q->where([
                ['offer_letter_send_at','!=',NULL],
                ['offer_letter_verified_at',NULL],
            ])->whereHas('candidateDetails',function($query) {
                $query->where('offer_sign','!=',NULL);
            });
        })->get();
        $verified = EmployeeProfile::where([
            ['type','candidate'],
            ['documents_verified_at','!=',NULL],
        ])->whereHas('interviewSummary', function($q){
            $q->where([
                ['offer_letter_send_at','!=',NULL],
                ['offer_letter_verified_at','!=',NULL],
            ])->whereHas('candidateDetails',function($query) {
                $query->where('offer_sign','!=',NULL);
            });
        })->get();
        return view('hrm.hiring.offer_letter.verifyOrResend',compact('pending','verified'));
    }
    public function sendJobOfferLetter($id) {
        $canSendOfferLetterLink = 'yes';
        $canSendPersonalInfoLink = 'yes';
        if($id != NULL) {
            DB::beginTransaction();
            try {
                $update = InterviewSummaryReport::where('id',$id)->first();
                if($update && $update->offer_letter_send_at == '') {
                    if($update) {
                        $update->offer_letter_send_at = Carbon::now();
                        $update->offer_letter_send_by = Auth::id();
                    }
                    $update->update();
                    $emp = EmployeeProfile::where('interview_summary_id',$id)->first();
                    if($emp->personal_information_created_at != NULL && $emp->personal_information_send_at != NULL && 
                    $emp->personal_information_send_at < $emp->personal_information_created_at) {
                        $canSendPersonalInfoLink = 'no';
                    }
                    if($emp->personal_information_created_at != NULL && $emp->offer_signed_at != NULL && 
                    $emp->offer_signed_at < $emp->personal_information_created_at) {
                        $canSendOfferLetterLink = 'no';
                    }
                    if($emp) {
                        $emp->personal_information_send_at = Carbon::now();
                        $emp->update();
                    }               
                    $data['comment'] = '';
                    $data['id'] = Crypt::encrypt($update->id);
                    $data['email'] = $update->email;
                    $data['send_by'] = Auth::user()->name;
                    $data['name'] = 'Dear '.$update->candidate_name.' ,';
                    $data['basic_salary'] = $emp->basic_salary;
                    $data['basic_salary_inwords'] = $this->decimalNumberInWords($emp->basic_salary);
                    $data['job_position'] = $update->employeeHiringRequest->questionnaire->designation->name;
                    $data['canSendOfferLetterLink'] = $canSendOfferLetterLink;
                    $data['canSendPersonalInfoLink'] = $canSendPersonalInfoLink;
                    $template['from'] = 'no-reply@milele.com';
                    $template['from_name'] = 'Milele Matrix';
                    $subject = 'Milele - Candidate Personal Information Form';
                    Mail::send(
                            "hrm.hiring.personal_info.email",
                            ["data"=>$data] ,
                            function($msg) use ($data,$template,$subject) {
                                $msg->to($data['email'], $data['name'])
                                    ->from($template['from'],$template['from_name'])
                                    ->subject($subject);
                            }
                        );
                    DB::commit();
                    return redirect()->route('interview-summary-report.index')->with('success','Offer Letter and Personal Information Form successfully sent to candidate');     
                }
                else if($update && $update->offer_letter_send_at != '') {
                    DB::commit();
                    return redirect()->route('interview-summary-report.index')->with('error',"Can't send! The offer letter for this candidate has already been sent.");     
                }
            } 
            catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg ="Something went wrong! Contact your admin";
                return view('hrm.notaccess',compact('errorMsg'));
            }
        }
        else {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        
    }
    public function signJobOfferLetter( $id) {
        if($id != NULL) {
            DB::beginTransaction();
            try {
                $id = Crypt::decrypt($id);
                $data = InterviewSummaryReport::where('id',$id)->first();
                $emp = EmployeeProfile::where('interview_summary_id',$id)->first();
                $inwords['basic_salary'] = $this->decimalNumberInWords($emp->basic_salary);
                $inwords['other_allowances'] = $this->decimalNumberInWords($emp->other_allowances);
                $inwords['total_salary'] = $this->decimalNumberInWords($emp->total_salary);
                $hr = ApprovalByPositions::where('approved_by_position','HR Manager')->first();               
                if($emp->offer_sign != NULL && $emp->offer_signed_at != NULL && $emp->offer_letter_hr_id != NULL) {
                    $data->isAuth = 2;
                }
                else if($data->offer_letter_send_at != NULL && $emp->offer_sign == NULL && $emp->offer_signed_at == NULL && $emp->offer_letter_hr_id == NULL) {
                    $data->isAuth = 0;
                }
                $data->canVerifySign = false;
                DB::commit();
                return view('hrm.hiring.offer_letter.offerLetter',compact('data','inwords','hr'));
            } 
            catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg ="Something went wrong! Contact your admin";
                return view('hrm.notaccess',compact('errorMsg'));
            }
        }
        else {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    }
    public function signedOfferLetter(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'signature' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $success = '';
                $error = '';
                $hr = ApprovalByPositions::where('approved_by_position','HR Manager')->first();
                $emp = EmployeeProfile::where('interview_summary_id',$request->id)->first();
                if($emp) {
                    $data = InterviewSummaryReport::where('id',$request->id)->first();
                    $inwords['basic_salary'] = $this->decimalNumberInWords($emp->basic_salary);
                    $inwords['other_allowances'] = $this->decimalNumberInWords($emp->other_allowances);
                    $inwords['total_salary'] = $this->decimalNumberInWords($emp->total_salary);
                    $data->isAuth = 2;
                }
                if($emp->interviewSummary->offer_letter_verified_at == '') {
                    $emp->offer_sign = $request->signature;
                    $emp->offer_signed_at = Carbon::now();
                    $emp->offer_letter_hr_id = $hr->handover_to_id;
                    $emp->save();
                    $success = 'Signature sent successfully';
                }
                else if($emp->interviewSummary->offer_letter_verified_at != '') {
                    $error = "Can't submit signature! The signature has already been verified.";
                }
                DB::commit();
                return view('hrm.hiring.offer_letter.offerLetter',compact('data','inwords','hr','success','error'));
            } 
            catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg ="Something went wrong! Contact your admin";
                return view('hrm.notaccess',compact('errorMsg'));
            }
        }
    }
}
