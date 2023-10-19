<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\EmpDoc;
use App\Models\UserActivities;
use App\Models\EmpJob;
use App\Models\CoreResponsibities;
use App\Models\WorkingHistory;
use App\Models\SalesPersonLaugauges;
use App\Models\skill;
use Monarobase\CountryList\CountryListFacade;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()

    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Open User Profile";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $emp_profile = Profile::where('user_id', Auth::id())->first();
        $user = User::where('id', Auth::id())->first();
        $emp_doc = EmpDoc::where('emp_profile_id', $emp_profile->id)->get();
        $emp_job = EmpJob::where('emp_profile_id', $emp_profile->id)->first();
        $core_responsibities = CoreResponsibities::where('emp_profile_id', $emp_profile->id)->get();
        $working_history = WorkingHistory::where('emp_profile_id', $emp_profile->id)->get();
        $emp_languages = SalesPersonLaugauges::where('sales_person', Auth::id())->get();
        $emp_skills = skill::where('emp_profile_id', $emp_profile->id)->get();
        $countries = CountryListFacade::getList('en');
        return view('users.profile', compact('emp_profile','emp_doc','emp_job', 'core_responsibities', 'working_history', 'emp_languages', 'emp_skills', 'user', 'countries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProfileModel $profileModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProfileModel $profileModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProfileModel $profileModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProfileModel $profileModel)
    {
    }
    public function updateLoginInfo(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Update Profile Login Info";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $updatedInfo = $request->input('updatedInfo');
        $profile = Profile::where('user_id', auth()->user()->id)->first();
        $fullName = $updatedInfo['fullName'];
        $nameParts = explode(' ', $fullName);
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? '';
        $profile->first_name = $firstName;
        $profile->last_name = $lastName;
        $profile->gender = $updatedInfo['gender'];
        $profile->nationality = $updatedInfo['nationality'];
        $profile->religion = $updatedInfo['religion'];
        $profile->passport_number = $updatedInfo['passportNumber'];
        $profile->passport_issue_date = $updatedInfo['passportIssueDate'];
        $profile->passport_expiry_date = $updatedInfo['passportExpiryDate'];
        $profile->contact_number = $updatedInfo['contactNumber'];
        $profile->company_number = $updatedInfo['companyNumber'];
        $profile->save();
    return response()->json(['message' => 'Information updated successfully']);
    }
    public function updateEmailInfo(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Update Email Information";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $user = Auth::user();
        $oldPassword = $request->input('oldPassword');
        $newPassword = $request->input('newPassword');
        if (!Hash::check($oldPassword, $user->password)) {
            return response()->json(['success' => false, 'error' => 'incorrect_old_password']);
        }
        $user->password = Hash::make($newPassword);
        $user->save();
        session()->flush();
        return response()->json(['success' => true]);
    }
    public function updatepictureInfo(Request $request)
{
    $useractivities =  New UserActivities();
        $useractivities->activity = "Update the Profile Picture";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    if ($request->hasFile('picture') && $request->file('picture')->isValid()) {
        $file = $request->file('picture');

        // Specify the desired storage path
        $storagePath = 'profile_pic';

        // Move the uploaded file to the desired storage directory
        $filePath = $file->move(public_path($storagePath), $file->getClientOriginalName());

        $userId = Auth::id();
        $profile = Profile::where('user_id', $userId)->first();

        if ($profile) {
            if ($profile->image_path && file_exists(public_path($profile->image_path))) {
                // Check if the user has an existing profile image
                // Delete the existing image if it exists
                unlink(public_path($profile->image_path));
            }

            $profile->image_path = $storagePath . '/' . $file->getClientOriginalName();
            $profile->save();
        } else {
            Profile::create([
                'user_id' => $userId,
                'picture_path' => $storagePath . '/' . $file->getClientOriginalName(),
            ]);
        }

        return response()->json(['status' => 'success']);
    }

    return response()->json(['status' => 'error']);
}
    public function deleteDocument($id)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Delete the Profile Document";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $document = EmpDoc::findOrFail($id);
    $filePath = public_path($document->document_path);
    if (file_exists($filePath)) {
        unlink($filePath);
    }
    $document->delete();
    return redirect()->back()->with('success', 'Document deleted successfully.');
    }
    public function skillDocument($id)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "update the skills";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
     $document = skill::findOrFail($id);
     $document->delete();
     return redirect()->back()->with('success', 'Skill deleted successfully.');
    }
    public function saveDocument(Request $request)
{
    $useractivities =  New UserActivities();
        $useractivities->activity = "save the profile documents";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $empProfileId = $request->input('emp_profile_id');
    $documentName = $request->input('document_name');
    $documentFile = $request->file('document_file');
    if ($request->hasFile('document_file') && $documentFile->isValid()) {
        $filename = uniqid() . '.' . $documentFile->getClientOriginalExtension();
        $directory = 'documents';
        $documentFile->move(public_path($directory), $filename);
        $document = new EmpDoc;
        $document->document_name = $documentName;
        $document->document_path = $directory . '/' . $filename;
        $document->emp_profile_id = $empProfileId;
        $document->save();
        return response()->json(['message' => 'Document saved successfully'], 200);
    }
    return response()->json(['error' => 'Invalid document file'], 400);
}
public function saveskill(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "save the new skills profile";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $skillName = $request->input('name');
    $skillPercentage = $request->input('percentage');
    $empProfileId = $request->input('emp_profile_id');
    $skill = new Skill();
    $skill->name = $skillName;
    $skill->percentage = $skillPercentage;
    $skill->emp_profile_id = $empProfileId;
    $skill->save();
    return response()->json(['message' => 'Skill saved successfully']);
    }
    public function historydelete($id)
    {
     $document = WorkingHistory::findOrFail($id);
     $document->delete();
     return redirect()->back()->with('success', 'Skill deleted successfully.');
    }
    public function updatehistoryInfo(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "update the employee history";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $CompanyName = $request->input('CompanyName');
    $designation = $request->input('designation');
    $location = $request->input('location');
    $todate = $request->input('todate');
    $fromdate = $request->input('fromdate');
    $empProfileId = $request->input('empProfileId');
    $history = new WorkingHistory();
    $history->company_name = $CompanyName;
    $history->designation = $designation;
    $history->location = $location;
    $history->todate = $todate;
    $history->fromdate = $fromdate;
    $history->emp_profile_id = $empProfileId;
    $history->save();
    return response()->json(['message' => 'Skill saved successfully']);
    }
}
