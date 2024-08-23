<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Modules;
use DB;
use App\Http\Controllers\UserActivityController;
class RoleController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:role-list|role-view|role-create|role-edit|role-delete', ['only' => ['index','store']]);
         $this->middleware('permission:role-view', ['only' => ['show']]);
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        $roles = Role::orderBy('id','ASC')->get();
        return view('roles.index',compact('roles'));
    }

    public function create()
    {
        $modules = Modules::with('permissions')->get();
        return view('roles.create',compact('modules'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));
        (new UserActivityController)->createActivity('New Role Created');
        return redirect()->route('roles.index')
                        ->with('success','Role created successfully');
    }

    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();

        return view('roles.show',compact('role','rolePermissions'));
    }

    public function edit($id)
    {
        $role = Role::find($id);
        $modules = Modules::with('permissions')->get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        return view('roles.edit',compact('role','modules','rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $role = Role::find($id);
        // Check for duplicate role name
        $existingRole = Role::where('name', $request->input('name'))
                            ->where('id', '!=', $id)
                            ->first();

        if ($existingRole) {
            return redirect()->back()->withErrors(['name' => 'The role name has already been taken.']);
        }
        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permission'));
        (new UserActivityController)->createActivity('Role Updated');
        return redirect()->route('roles.index')
                        ->with('success','Role updated successfully');
    }

    public function delete($id)
    {
        DB::table("roles")->where('id',$id)->delete();
        (new UserActivityController)->createActivity('Role Deleted');
        return redirect()->route('roles.index')
                        ->with('success','Role deleted successfully');
    }
}
