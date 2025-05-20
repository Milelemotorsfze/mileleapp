<?php
namespace App\Http\Controllers;

    use App\Models\EmpJob;
    use App\Models\Language;
    use App\Models\SalesPersonLaugauges;
    use App\Models\Masters\MasterJobPosition;
    use App\Models\Masters\MasterDepartment;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Models\User;
    use Spatie\Permission\Models\Role;
    use DB;
    use Hash;
    use App\Models\SalesPersonStatus;
    use Illuminate\Support\Facades\Auth;
    use App\Models\Profile;
    use Illuminate\Support\Facades\Session;
    use Illuminate\Support\Arr;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Mail;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Crypt;
    use App\Http\Controllers\UserActivityController;
    use App\Models\CompanyDomain;
    use Validator;
    use Exception;
    use App\Models\HRM\Employee\EmployeeProfile;
    class UserController extends Controller
    {
        public function index(Request $request)
        {
            $accessRequests = User::orderBy('id','DESC')->whereIn('status',['new','active'])->whereNot('id','16')
            ->where(function($q){
                $q->whereDoesntHave('roles')->orWhere('password','');
            })->get();
            $data = User::orderBy('status','DESC')->whereIn('status',['new','active'])->where('password','!=','')->whereHas('roles')->get();
            $inactive_users = User::where('status','inactive')->get();
            $deleted_users = User::onlyTrashed()->get();
            return view('users.index',compact('accessRequests','data','inactive_users','deleted_users'));
        }
        public function create()
        {
            $roles = Role::all();
            $language = Language::pluck('name','name')->all();
            $jobposition =  MasterJobPosition::where('status', 'active')->get();
            $departments =  MasterDepartment::where('status', 'active')->get();
            return view('users.create',compact('roles', 'language', 'jobposition', 'departments'));
        }
        public function createLogin($id) {
            $user = User::findOrFail($id); 
            $roles = Role::all();
            $language = Language::pluck('name','name')->all();
            return view('users.create',compact('user','roles', 'language'));
        }
        public function store(Request $request)
        {
            $allowedDomains = CompanyDomain::pluck('email_server')->map(function ($domain) {
                return strtolower(trim(str_replace(' ', '', substr(strrchr($domain, "@") ?: $domain, 1))));
            })->toArray();

            $this->validate($request, [
                'name' => 'required',
                'email' => [
                    'required',
                    'email',
                    'unique:users,email',
                    function ($attribute, $value, $fail) use ($allowedDomains) {
                        $domain = strtolower(trim(substr(strrchr($value, "@"), 1))); 
                        if (!in_array($domain, $allowedDomains)) {
                            $fail('The email domain must match one of the allowed email servers.');
                        }
                    },
                ],                
                'roles' => 'required',
                'department' => 'required',
                'designation' => 'required',
                'lauguages' => 'required',
                'user_image' => 'nullable|image|mimes:jpg,jpeg,png|max:100',
            ]);
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->status = 'active';
            $user->sales_rap = $request->has('sales_rap') ? 'Yes' : 'No';
            $user->is_sales_rep = $request->has('is_sales_rep') ? 'Yes' : 'No';
            $user->can_send_wo_email = $request->has('can_send_wo_email') ? 'yes' : 'no';
            $user->manual_lead_assign = $request->has('manual_lead_assign') ? '1' : '0';
            $user->pfi_access = $request->has('pfi_access') ? '1' : '0';
            $user->selected_role = $request->roles[0];
            $user->save();
            $empProfile = new EmployeeProfile();
            $empProfile->user_id = $user->id;
            $empProfile->first_name = $request->input('name');
            $empProfile->company_number = $request->input('phone');
            $empProfile->department_id = $request->input('department');
            $empProfile->designation_id = $request->input('designation');
            if ($request->hasFile('user_image')) {
                $image = $request->file('user_image');
                $imageName = time().'.'.$image->getClientOriginalExtension();
                $image->move(public_path('images/users'), $imageName);           
                // Save the image path to the user's record
                $empProfile->image_path = 'images/users/'.$imageName;
            }
            $empProfile->save();
            $empJob = new EmpJob();
            $empProfileId = $empProfile->id;
            $empJob->emp_profile_id = $empProfileId;
            $empJob->department = $request->input('department');
            $empJob->designation = $request->input('designation');
            $empJob->save();
            if ($user->sales_rap === 'Yes') {
                $salesmanstatus = New SalesPersonStatus();
                $salesmanstatus->sale_person_id = $user->id;
                $salesmanstatus->status = "Active";
                $salesmanstatus->remarks = "Account Created";
                $salesmanstatus->created_by = Auth::id();
                $salesmanstatus->save();
            }
            $languages = $request->input('lauguages');
                foreach ($languages as $language) {
                    $salesPersonLanguage = new SalesPersonLaugauges();
                    $salesPersonLanguage->sales_person = $user->id;
                    $salesPersonLanguage->language = $language;
                    $salesPersonLanguage->save();
                }
            $user->assignRole($request->input('roles'));
            $data['email'] = $user->email;
            $data['emailEncrypt'] = Crypt::encryptString($user->email);
            $data['name'] = $user->name;
            $template['from'] = 'no-reply@milele.com';
            $template['from_name'] = 'Milele Matrix';
            $subject = 'Milele Matrix Password Creation';
            Mail::send(
                    "auth.createPasswordMail",
                    ["data"=>$data] ,
                    function($msg) use ($data,$template,$subject) {
                        $msg->to($data['email'], $data['name'])
                            ->from($template['from'],$template['from_name'])
                            ->subject($subject);
                    }
                );
            (new UserActivityController)->createActivity('User Created');
            return redirect()->route('users.index')
                ->with('success','User created successfully');
        }
        public function show($id)
        {
            $user = User::find($id);
            return view('users.show',compact('user'));
        }
        public function edit($id)
        {
            $language = Language::pluck('name', 'name')->all();
            $jobposition = MasterJobPosition::where('status', 'active')->get();
            $departments = MasterDepartment::where('status', 'active')->get();
            $user = User::find($id);
            $roles = Role::all();
            $userRole = $user->roles->pluck('id')->toArray();
            $userLanguages = SalesPersonLaugauges::where('sales_person', $user->id)->pluck('language')->toArray();
            $userDepartmentId = $user->empProfile->department_id ?? null;
            $userDesignationId = $user->empProfile->designation_id ?? null;
        
            return view('users.edit', compact('user', 'roles', 'userRole', 'departments', 'jobposition', 'language', 'userLanguages', 'userDepartmentId', 'userDesignationId'));
        }
        public function update(Request $request, $id)
{
    $this->validate($request, [
        'name' => 'required',
        'email' => 'required|email|unique:users,email,' . $id,
        'roles' => 'required',
        'department' => 'required',
        'designation' => 'required',
        'user_image' => 'nullable|image|mimes:jpg,jpeg,png|max:100',
    ]);
    $user = User::find($id);
    $user->name = $request->input('name');
    $user->email = $request->input('email');
    $user->sales_rap = $request->has('sales_rap') ? 'Yes' : 'No';
    $user->is_sales_rep = $request->has('is_sales_rep') ? 'Yes' : 'No';
    $user->can_send_wo_email = $request->has('can_send_wo_email') ? 'yes' : 'no';
    $user->manual_lead_assign = $request->has('manual_lead_assign') ? '1' : '0';
    $user->pfi_access = $request->has('pfi_access') ? '1' : '0';
    $user->selected_role = $request->roles[0];
    $user->save();

    $empProfile = $user->empProfile ?? new EmployeeProfile();
    $empProfile->user_id = $user->id; // Ensure the relationship is set
    $empProfile->first_name = $request->input('name');
    $empProfile->company_number = $request->input('phone');
    $empProfile->department_id = $request->input('department');
    $empProfile->designation_id = $request->input('designation');
    if ($request->hasFile('user_image')) {
        $image = $request->file('user_image');
        $imageName = time().'.'.$image->getClientOriginalExtension();
        $image->move(public_path('images/users'), $imageName);
        $empProfile->image_path = 'images/users/'.$imageName;
    }
    $empProfile->save();
    SalesPersonStatus::updateOrCreate(
        ['sale_person_id' => $user->id],
        ['status' => "Active", 'remarks' => "Account Updated", 'created_by' => Auth::id()]
    );
    SalesPersonLaugauges::where('sales_person', $user->id)->delete();
    $languages = $request->input('languages');
    if($languages){
    foreach ($languages as $language) {
        SalesPersonLaugauges::create(['sales_person' => $user->id, 'language' => $language]);
    }
}
    DB::table('model_has_roles')->where('model_id',$id)->delete();
            $user->assignRole($request->input('roles'));
            (new UserActivityController)->createActivity('User Updated');
    return redirect()->route('users.index')
        ->with('success', 'User updated successfully');
}
        public function delete($id)
        {
            User::find($id)->delete();
            (new UserActivityController)->createActivity('User Deleted');
            return redirect()->route('users.index')
                            ->with('success','User deleted successfully');
        }
        public function updateStatus($id)
        {
            $user = User::find($id);
            $user->status = 'inactive';
            $user->update();
            (new UserActivityController)->createActivity('Make User Inactive');
            return redirect()->route('users.index')
                            ->with('success','User updated successfully');
        }

        public function makeActive($id)
        {
           $user = User::find($id);
           $user->status = 'active';
           $user->update();
           (new UserActivityController)->createActivity('Make User Active');
           return redirect()->route('users.index')
                           ->with('success','User updated successfully');
        }

        public function restore($id)
        {
            User::withTrashed()->find($id)->restore();
            (new UserActivityController)->createActivity('Restore User');
            return redirect()->route('users.index')
                            ->with('success','User updated successfully');
        }
        public function updateRole(Request $request, $roleId)
        {
        $user = Auth::user();
        $user->selected_role = $roleId;
        $user->save();
        Session::put('selectedRole', $roleId);
        return redirect()->back();
        }
        public function showUseractivities($id, $date, Request $request)
        {
        return view('users.activity.dailyactivity', ['id' => $id, 'date' => $date]);
        }
        public function uniqueEmail(Request $request) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else {
                try {
                    $email = User::where('email',$request->email)->get();
                    if(count($email) > 0) {
                        return false;
                    }
                    else {
                        return true;
                    }
                } 
                catch (\Exception $e) {
                   info($e);
               }
            }
        }
        public function createAccessRequest(Request $request) {
             // Define validation rules
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'id' => 'required',
            ];

            // Create a validator instance and use it to validate the request
            $validator = Validator::make($request->all(), $rules);

            // Check if validation fails
            if ($validator->fails()) {
                return redirect('register')
                            ->withErrors($validator)
                            ->withInput();
            }

            // If validation passes, proceed to store the data
            DB::beginTransaction();
            try {
                // Creating a new user instance and saving it in the database
                $user = new User([
                    'name' => $request->name,
                    'email' => $request->email,
                ]);

                $user->save(); // Save the user
                $empProfile = EmployeeProfile::findOrFail($request->id);
                $empProfile->update([
                    'user_id' => $user->id,
                    'updated_by' => Auth::id(),
                ]);
                DB::commit();
                // Optionally, redirect to a route with a success message
                return redirect()->route('employee.index')->with('success', 'Milele Matrix Sign-Up Request Successfully Sent to Admin');
            } catch (\Exception $e) {
                DB::rollback();
                // Handle the exception
                return back()->with('error', 'An error occurred while saving the user: ' . $e->getMessage());
            }
        }
        public function searchUsers(Request $request)
        {
            $query = $request->input('query');
            $users = User::where('name', 'LIKE', "%{$query}%")->get(['id', 'name']);

            return response()->json([
                'users' => $users->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name ?? 'Unknown User',
                    ];
                }),
            ]);
        }
        public function getUserById($id)
        {
            // Find the user by ID
            $user = User::find($id);
    
            // Check if the user exists
            if ($user) {
                // Return user data as a JSON response
                return response()->json([
                    'status' => 'success',
                    'user' => $user
                ]);
            } else {
                // Return error response if user not found
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], 404);
            }
        }
    }
