<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hiring;
use Illuminate\SUpport\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HiringController extends Controller
{
    public function index()
    {
        // return view('hiring.index');
        $JobData = hiring::all();
        return view('hiring.index', ['hiring'=>$JobData]);
    }
    public function create()
    {
        return view('hiring.create');
    }
    public function store(Request $request)
    {
        $hirings = new Hiring();
        $hirings->job_title = $request->input('job_title');
        $hirings->job_details = $request->input('job_details');
        $hirings->job_role = $request->input('job_role');
        $hirings->job_education = $request->input('job_education');
        $hirings->job_experiance = $request->input('job_experiance');
        $hirings->job_skills = $request->input('job_skills');
        $hirings->job_other = $request->input('job_other');
        $hirings->save();
        return redirect('hiring');
    }

    public function show()
    {
    }
    public function edit($id)
    {
        $hiring = Hiring::find($id);
        return view('hiring.edit', compact('hiring'));
    }
    public function update(Request $request, $id){
        $authId = Auth::id();
        $hirings = Hiring::find($id);
        $hirings->job_title = $request->input('job_title');
        $hirings->job_details = $request->input('job_details');
        $hirings->job_role = $request->input('job_role');
        $hirings->job_education = $request->input('job_education');
        $hirings->job_experiance = $request->input('job_experiance');
        $hirings->job_skills = $request->input('job_skills');
        $hirings->job_other = $request->input('job_other');
        $hiring['updated_by'] = $authId;
        $hiring = Hiring::find($id);
        $hirings->update();
        return redirect()
        ->route('hiring.index')
        ->with('success','Record Updated Successfully');
    }
}
