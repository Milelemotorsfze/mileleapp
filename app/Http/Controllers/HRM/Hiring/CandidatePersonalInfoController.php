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

class CandidatePersonalInfoController extends Controller
{
    public function create() {
        return view('hrm.hiring.documents.create');
    }
    public function sendDocsEmail(Request $request) {
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
                        "hrm.hiring.personal_info.email",
                        ["data"=>$data] ,
                        function($msg) use ($data,$template,$subject) {
                            $msg->to($data['email'], $data['name'])
                                ->from($template['from'],$template['from_name'])
                                ->subject($subject);
                        }
                    );
                    // dd('hi');
                DB::commit();
                return redirect()->back()
                                    ->with('success','Documents Request Form Successfully Send To Candidate');
                // return view('hrm.hiring.personal_info.email', compact('data','template','subject'));
            } 
            catch (\Exception $e) {
                DB::rollback();
                info($e);
            }
        }
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
                $emp = EmployeeProfile::where('interview_summary_id',$request->id)->first();
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
                // DB::commit();
                return redirect()->back()
                                    ->with('success','Offer Letter and Personal Information Form Successfully Send To Candidate');
            // } 
            // catch (\Exception $e) {
            //     DB::rollback();
            //     info($e);
            // }
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
        return view('hrm.hiring.documents.create',compact('candidate','masterMaritalStatus','masterReligion','masterLanguages','masterNationality','masterRelations'));
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
        return view('hrm.hiring.personal_info.create',compact('candidate','masterMaritalStatus','masterReligion','masterLanguages','masterNationality','masterRelations'));
    }
    public function storeDocs(Request $request) {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            // 'passport_size_photograph' => 'required',
            // 'resume' => 'required',
            // 'visa' => 'required',
            // 'passport' => 'required',
            // 'national_id' => 'required',
            // 'educational_docs' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $candidate = InterviewSummaryReport::where('id',$request->id)->first();
                if($candidate) {
                    // $candidate->pif_sign = $request->signature;
                    // $candidate->update();
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
                        // $input['residence_telephone_number'] = $request->residence_telephone_number['full'];
                        // $input['contact_number'] = $request->contact_number['full'];
                        $input['type'] = 'candidate';
                        $input['interview_summary_id'] = $request->id;
                        // $input['designation_id'] = $candidate->employeeHiringRequest->questionnaire->designation->id;
                        // $input['department_id'] = $candidate->employeeHiringRequest->questionnaire->department->id;
                        // $input['gender'] = $candidate->gender;
                        // $input['nationality'] = $candidate->nationality;
                        // $input['personal_information_created_at'] = Carbon::now();
                        $createEmp = EmployeeProfile::create($input);                      
                    }
                    else { 
                        // $createEmp->first_name = $request->first_name;
                        // $createEmp->last_name = $request->last_name;
                        // $createEmp->name_of_father = $request->name_of_father;
                        // $createEmp->name_of_mother = $request->name_of_mother;
                        // $createEmp->marital_status = $request->marital_status;
                        // $createEmp->passport_number = $request->passport_number;
                        // $createEmp->passport_expiry_date = $request->passport_expiry_date;
                        // $createEmp->educational_qualification = $request->educational_qualification;
                        // $createEmp->year_of_completion = $request->year_of_completion;
                        // $createEmp->religion = $request->religion;
                        // $createEmp->dob = $request->dob;
                        // $createEmp->address_uae = $request->address_uae;
                        // $createEmp->personal_email_address = $request->personal_email_address;
                        // $createEmp->spouse_name = $request->spouse_name;
                        // $createEmp->spouse_passport_number = $request->spouse_passport_number;
                        // $createEmp->spouse_passport_expiry_date = $request->spouse_passport_expiry_date;
                        // $createEmp->spouse_dob = $request->spouse_dob;
                        // $createEmp->spouse_nationality = $request->spouse_nationality;
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
                        // $createEmp->residence_telephone_number = $request->residence_telephone_number['full'];
                        // $createEmp->contact_number = $request->contact_number['full'];
                        $createEmp->update();
                    }
                    // $oldLangs = EmployeeSpokenLanguage::where('candidate_id',$createEmp->id)->get();
                    // foreach($oldLangs as $oldLang) {
                    //     $oldLang->delete();
                    // }
                    // if(isset($request->language_id)) {
                    //     if(count($request->language_id) > 0) {
                    //         $inputLang['candidate_id'] = $createEmp->id;
                    //         foreach($request->language_id as $language_id) {
                    //             $inputLang['language_id'] = $language_id;
                    //             $createLang = EmployeeSpokenLanguage::create($inputLang);                            
                    //         }
                    //     }
                    // }
                    // $oldChilds = Children::where('candidate_id',$createEmp->id)->get();
                    // foreach($oldChilds as $oldChild) {
                    //     $oldChild->delete();
                    // }
                    // if(isset($request->child)) {
                    //     if(count($request->child) > 0) {
                    //         foreach($request->child as $child) {  
                    //             $inputChild = [];  
                    //             $inputChild['candidate_id'] = $createEmp->id;                      
                    //             $inputChild['child_name'] = $child['child_name'];
                    //             $inputChild['child_passport_number'] = $child['child_passport_number'];
                    //             $inputChild['child_passport_expiry_date'] = $child['child_passport_expiry_date'];
                    //             $inputChild['child_dob'] = $child['child_dob'];
                    //             $inputChild['child_nationality'] = $child['child_nationality'];
                    //             if($inputChild['child_name'] != NULL && $inputChild['child_dob'] != NULL && $inputChild['child_nationality'] != NULL) {
                    //                 $createChild = Children::create($inputChild);                            
                    //             }
                    //         }
                    //     }
                    // }
                    // $oldEcus = UAEEmergencyContact::where('candidate_id',$createEmp->id)->get();
                    // foreach($oldEcus as $oldEcu) {
                    //     $oldEcu->delete();
                    // }
                    // if(isset($request->ecu)) {
                    //     if(count($request->ecu) > 0) {
                    //         foreach($request->ecu as $ecu) {  
                    //             $inputEcu = [];  
                    //             $inputEcu['candidate_id'] = $createEmp->id;                      
                    //             $inputEcu['name'] = $ecu['name'];
                    //             $inputEcu['relation'] = $ecu['relation'];
                    //             $inputEcu['contact_number'] = $ecu['contact_number']['full'];
                    //             $inputEcu['alternative_contact_number'] = $ecu['alternative_contact_number']['full'];
                    //             $inputEcu['email_address'] = $ecu['email_address'];
                    //             $createEcu = UAEEmergencyContact::create($inputEcu);                            
                    //         }
                    //     }
                    // }
                    // $oldEchs = HomeCountryEmergencyContact::where('candidate_id',$createEmp->id)->get();
                    // foreach($oldEchs as $oldEch) {
                    //     $oldEch->delete();
                    // }
                    // if(isset($request->ech)) {
                    //     if(count($request->ech) > 0) {
                    //         foreach($request->ech as $ech) {  
                    //             $inputEch = [];  
                    //             $inputEch['candidate_id'] = $createEmp->id;                      
                    //             $inputEch['name'] = $ech['name'];
                    //             $inputEch['relation'] = $ech['relation'];
                    //             $inputEch['contact_number'] = $ech['contact_number']['full'];
                    //             $inputEch['alternative_contact_number'] = $ech['alternative_contact_number']['full'];
                    //             $inputEch['email_address'] = $ech['email'];
                    //             $inputEch['home_country_address'] = $ech['home_country_address'];
                    //             $createEch = HomeCountryEmergencyContact::create($inputEch);                            
                    //         }
                    //     }
                    // }
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
                }               
           DB::commit();
           $successMessage = 'Candidate Documents Request Form Submitted Successfully.';
           return view('hrm.hiring.documents.successPersonalinfo',compact('candidate','successMessage'));
            } 
            catch (\Exception $e) {
                DB::rollback();
                dd($e);
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
            // 'passport_size_photograph' => 'required',
            // 'resume' => 'required',
            // 'visa' => 'required',
            // 'passport' => 'required',
            // 'national_id' => 'required',
            // 'educational_docs' => 'required',
            // 'professional_diploma_certificates' => 'required',
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
                    // if($request->passport_size_photograph) {                       
                    //     $photoFileName = auth()->id() . '_' . time() . '.'. $request->passport_size_photograph->extension();
                    //     $type = $request->passport_size_photograph->getClientMimeType();
                    //     $size = $request->passport_size_photograph->getSize();
                    //     $request->passport_size_photograph->move(public_path('hrm/employee/photo'), $photoFileName);
                    // }
                    // if($request->resume) {                       
                    //     $resumeFileName = auth()->id() . '_' . time() . '.'. $request->resume->extension();
                    //     $type = $request->resume->getClientMimeType();
                    //     $size = $request->resume->getSize();
                    //     $request->resume->move(public_path('hrm/employee/resume'), $resumeFileName);                                              
                    // }
                    // if($request->visa) {                       
                    //     $visaFileName = auth()->id() . '_' . time() . '.'. $request->visa->extension();
                    //     $type = $request->visa->getClientMimeType();
                    //     $size = $request->visa->getSize();
                    //     $request->visa->move(public_path('hrm/employee/visa'), $visaFileName);                        
                    // }
                    // if($request->emirates_id) {                 
                    //     $emiratesIdFileName = auth()->id() . '_' . time() . '.'. $request->emirates_id->extension();
                    //     $type = $request->emirates_id->getClientMimeType();
                    //     $size = $request->emirates_id->getSize();
                    //     $request->emirates_id->move(public_path('hrm/employee/emirates_id'), $emiratesIdFileName);                        
                    // }
                    if(!$createEmp) {
                        $input = $request->all();  
                        // $input['image_path'] = $photoFileName;
                        // $input['resume'] = $resumeFileName;
                        // $input['visa'] = $visaFileName;
                        // if(isset($emiratesIdFileName)) {
                        //     $input['emirates_id_file'] = $emiratesIdFileName;                  
                        // }
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
                        // if(isset($photoFileName)) {
                        //     $createEmp->image_path = $photoFileName;
                        // }
                        // else if($request->is_photo_delete == 1) {
                        //     $createEmp->image_path =  NULL;
                        // }
                        // if(isset($resumeFileName)) {
                        //     $createEmp->resume = $resumeFileName;
                        // }
                        // else if($request->is_resume_delete == 1) {
                        //     $createEmp->resume = NULL;
                        // }
                        // if(isset($visaFileName)) {
                        //     $createEmp->visa = $visaFileName;
                        // }
                        // else if($request->is_visa_delete == 1) {
                        //     $createEmp->visa =  NULL;
                        // }
                        // if(isset($emiratesIdFileName)) {
                        //     $createEmp->emirates_id_file = $emiratesIdFileName;
                        // }
                        // else if($request->is_emirates_id_delete == 1) {
                        //     $createEmp->emirates_id_file = NULL;
                        // }
                        $createEmp->residence_telephone_number = $request->residence_telephone_number['full'];
                        $createEmp->contact_number = $request->contact_number['full'];
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
                    // if(isset($request->deleted_files)) {
                    //     if(count($request->deleted_files) > 0) {
                    //         foreach($request->deleted_files as $deleted_file) {
                                
                    //             $deleteFile = EmpDoc::where('id',$deleted_file)->first();
                    //             info($deleteFile);
                    //             if($deleteFile) {
                    //                 $deleteFile->delete();
                    //             }
                    //         }
                    //     }
                    // }
                    // if ($request->hasFile('passport')) {
                    //     foreach ($request->file('passport') as $file) {
                    //         $extension = $file->getClientOriginalExtension();
                    //         $fileName = time().'_'.$file->getClientOriginalName();
                    //         $destinationPath = 'hrm/employee/passport';
                    //         $file->move($destinationPath, $fileName);        
                    //         $CandidateDocument = new EmpDoc();
                    //         $CandidateDocument->candidate_id = $createEmp->id;
                    //         $CandidateDocument->document_name = 'passport';
                    //         $CandidateDocument->document_path = $fileName;
                    //         $CandidateDocument->save();
                    //     }
                    // }
                    // if ($request->hasFile('national_id')) {
                    //     foreach ($request->file('national_id') as $file) {
                    //         $extension = $file->getClientOriginalExtension();
                    //         $fileName = time().'_'.$file->getClientOriginalName();
                    //         $destinationPath = 'hrm/employee/national_id';
                    //         $file->move($destinationPath, $fileName);        
                    //         $CandidateDocument = new EmpDoc();
                    //         $CandidateDocument->candidate_id = $createEmp->id;
                    //         $CandidateDocument->document_name = 'national_id';
                    //         $CandidateDocument->document_path = $fileName;
                    //         $CandidateDocument->save();
                    //     }
                    // }
                    // if ($request->hasFile('educational_docs')) {
                    //     foreach ($request->file('educational_docs') as $file) {
                    //         $extension = $file->getClientOriginalExtension();
                    //         $fileName = time().'_'.$file->getClientOriginalName();
                    //         $destinationPath = 'hrm/employee/educational_docs';
                    //         $file->move($destinationPath, $fileName);        
                    //         $CandidateDocument = new EmpDoc();
                    //         $CandidateDocument->candidate_id = $createEmp->id;
                    //         $CandidateDocument->document_name = 'educational_docs';
                    //         $CandidateDocument->document_path = $fileName;
                    //         $CandidateDocument->save();
                    //     }
                    // }
                    // if ($request->hasFile('professional_diploma_certificates')) {
                    //     foreach ($request->file('professional_diploma_certificates') as $file) {
                    //         $extension = $file->getClientOriginalExtension();
                    //         $fileName = time().'_'.$file->getClientOriginalName();
                    //         $destinationPath = 'hrm/employee/professional_diploma_certificates';
                    //         $file->move($destinationPath, $fileName);        
                    //         $CandidateDocument = new EmpDoc();
                    //         $CandidateDocument->candidate_id = $createEmp->id;
                    //         $CandidateDocument->document_name = 'professional_diploma_certificates';
                    //         $CandidateDocument->document_path = $fileName;
                    //         $CandidateDocument->save();
                    //     }
                    // }
                }               
           DB::commit();
           $successMessage = 'Candidate Personal Information Form Submitted Successfully.';
           return view('hrm.hiring.personal_info.successPersonalinfo',compact('candidate','successMessage'));
            } 
            catch (\Exception $e) {
                DB::rollback();
                dd($e);
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
                if($candidate) {
                    $candidate->documents_verified_at = Carbon::now();
                    $candidate->documents_verified_by = $authId;
                    $candidate->update();
                }
                DB::commit();
                return response()->json('success');
            } 
            catch (\Exception $e) {
                DB::rollback();
                dd($e);
            }
        }
    }
    // public function personalInfoVerified(Request $request) {
    //     $validator = Validator::make($request->all(), [
    //         'id' => 'required',
    //     ]);
    //     if ($validator->fails()) {
    //         return redirect()->back()->withInput()->withErrors($validator);
    //     }
    //     else {
    //         DB::beginTransaction();
    //         try {
    //             $authId = Auth::id();
    //             $candidate = EmployeeProfile::where('interview_summary_id',$request->id)->first();
    //             if($candidate) {
    //                 $candidate->personal_information_verified_at = Carbon::now();
    //                 $candidate->personal_information_verified_by = $authId;
    //                 $candidate->update();
    //             }
    //             DB::commit();
    //             return response()->json('success');
    //         } 
    //         catch (\Exception $e) {
    //             DB::rollback();
    //             dd($e);
    //         }
    //     }
    // }
    public function getCandidatePersonalInfo() {
        $pending = EmployeeProfile::where([
            ['type','candidate'],
            ['personal_information_verified_at',NULL],
        ])->get();
        $verified = EmployeeProfile::where([
            ['type','candidate'],
            ['personal_information_verified_at','!=',NULL],
        ])->get();
        return view('hrm.hiring.documents.verifyOrResend',compact('pending','verified'));
    }
}
