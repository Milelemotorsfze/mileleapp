<?php

namespace App\Http\Controllers;

use App\Models\Carmodel;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class CarmodelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Carmodel::orderBy('id','DESC')->get();
        return view('models.index',compact('data'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('models.create',compact('roles'));
    }
    /**
     * Store a newly created resource in storage.
     */
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
    
        $user = Carmodel::create($input);
        $user->assignRole($request->input('roles'));
    
        return redirect()->route('Carmodel.index')
                        ->with('success','Model created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
            $user = Carmodel::find($id);
            return view('models.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = Carmodel::find($id);
    
        return view('models.edit',compact('user'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Carmodel $carmodel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Carmodel $carmodel)
    {
        //
    }
}
