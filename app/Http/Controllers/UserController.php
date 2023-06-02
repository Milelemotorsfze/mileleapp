<?php
namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Models\User;
    use Spatie\Permission\Models\Role;
    use DB;
    use Hash;
    use App\Models\Profile;
    use Illuminate\Support\Arr;
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
            $roles = Role::pluck('name','name')->all();
            return view('users.create',compact('roles'));
        }
        public function store(Request $request)
{
    $this->validate($request, [
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|same:confirm-password',
        'roles' => 'required'
    ]);

    $input = $request->all();
    $input['password'] = Hash::make($input['password']);

    $user = User::create($input);

    // Create a profile for the user and associate it with the user_id
    $profile = new Profile();
    $profile->user_id = $user->id;
    $profile->save();

    $user->assignRole($request->input('roles'));

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
            return redirect()->route('users.index')
                            ->with('success','User updated successfully');
        }
        public function destroy($id)
        {
            User::find($id)->delete();
            return redirect()->route('users.index')
                            ->with('success','User deleted successfully');
        }
        public function updateStatus($id)
        {
            $user = User::find($id);
            $user->status = 'inactive';
            $user->update();
            return redirect()->route('users.index')
                            ->with('success','User updated successfully');
        }

        public function makeActive($id)
        {
           $user = User::find($id);
           $user->status = 'active';
           $user->update();
           return redirect()->route('users.index')
                           ->with('success','User updated successfully');
        }

        public function restore($id)
        {
            User::withTrashed()->find($id)->restore();
            return redirect()->route('users.index')
                            ->with('success','User updated successfully');
        }
    }
