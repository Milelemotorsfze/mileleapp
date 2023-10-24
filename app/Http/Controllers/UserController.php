<?php
namespace App\Http\Controllers;

    use App\Models\EmpJob;
    use App\Models\Language;
    use App\Models\SalesPersonLaugauges;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Models\User;
    use Spatie\Permission\Models\Role;
    use DB;
    use Hash;
    use App\Models\Profile;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;
    use Illuminate\Support\Arr;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Mail;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Crypt;
    use App\Http\Controllers\UserActivityController;
    class UserController extends Controller
    {
        public function index(Request $request)
        {
            $data = User::orderBy('status','DESC')->whereIn('status',['new','active'])->get();
            $inactive_users = User::where('status','inactive')->get();
            $deleted_users = User::onlyTrashed()->get();
            return view('users.index',compact('data','inactive_users','deleted_users'));
        }
        public function create()
        {
            $roles = Role::all();
            $language = Language::pluck('name','name')->all();
            return view('users.create',compact('roles', 'language'));
        }
        public function store(Request $request)
        {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'roles' => 'required',
                'department' => 'required',
                'designation' => 'required',
                'lauguages' => 'required',
            ]);
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->status = 'active';
            $user->sales_rap = $request->has('sales_rap') ? 'Yes' : 'No';
            $user->selected_role = $request->roles[0];
            $user->save();
            $empProfile = new Profile();
            $empProfile->user_id = $user->id;
            $empProfile->first_name = $request->input('name');
            $empProfile->save();
            $empJob = new EmpJob();
            $empProfileId = $empProfile->id;
            $empJob->emp_profile_id = $empProfileId;
            $empJob->department = $request->input('department');
            $empJob->designation = $request->input('designation');
            $empJob->save();
            if ($user->sales_rap === 'Yes') {
                $languages = $request->input('lauguages');
                foreach ($languages as $language) {
                    $salesPersonLanguage = new SalesPersonLaugauges();
                    $salesPersonLanguage->sales_person = $user->id;
                    $salesPersonLanguage->language = $language;
                    $salesPersonLanguage->save();
                }
            }
            $user->assignRole($request->roles[0]);
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
            $user = User::find($id);
            $roles = Role::pluck('name','name')->all();
            $userRole = $user->roles->pluck('name','name')->all();

            return view('users.edit',compact('user','roles','userRole'));
        }
        public function update(Request $request, $id)
        {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,'.$id,
                'password' => 'same:confirm-password',
                'roles' => 'required'
            ]);

            $input = $request->all();
            if(!empty($input['password'])){
                $input['password'] = Hash::make($input['password']);
            }else{
                $input = Arr::except($input,array('password'));
            }
            $user = User::find($id);
            $user->update($input);
            DB::table('model_has_roles')->where('model_id',$id)->delete();
            $user->assignRole($request->input('roles'));
            (new UserActivityController)->createActivity('User Updated');
            return redirect()->route('users.index')
                            ->with('success','User updated successfully');
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
        }
