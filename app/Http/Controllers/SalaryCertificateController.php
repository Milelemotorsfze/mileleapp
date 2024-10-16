<?php

namespace App\Http\Controllers;

use App\Models\SalaryCertificate;
use App\Models\Masters\MasterJobPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SalaryCertificateController extends Controller
{


    public function index()
    {
        $certificates = SalaryCertificate::with('creator')->get();
        return view('employee_relation.salary_certificate.index', compact('certificates'));
    }

    public function create()
    {
        $Users = User::where('status', 'active')->whereNotIn('id', [1, 16])->whereNot('is_management', 'yes')->orderBy('name', 'ASC')->whereHas('empProfile')->with('empProfile.designation', 'empProfile.department', 'empProfile.location')->get();
        $masterEmployees = [];
        foreach ($Users as $User) {
            if ($User->can_submit_or_release_passport == true) {
                array_push($masterEmployees, $User);
            }
        }

        $masterJobPositions = MasterJobPosition::all();
        return view('employee_relation.salary_certificate.create', compact('masterJobPositions', 'masterEmployees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'branch_name' => 'required|string|max:255',
            'country_name' => 'required|string|max:255',
            'country_name' => 'required|string|max:255',
            'purpose_of_request' => 'required|string',
            'salary_certficate_request_detail' => 'required|string|max:255',
            'requested_for' => 'required',
        ]);

        SalaryCertificate::create([
            'bank_name' => $request->bank_name,
            'branch_name' => $request->branch_name,
            'country_name' => $request->country_name,
            'purpose_of_request' => $request->purpose_of_request,
            'requested_by' => Auth::id(),
            'requested_for' => $request->requested_for,
            'salary_certficate_request_detail' => $request->salary_certficate_request_detail,
            'status' => 'pending',
        ]);

        return redirect()->route('employeeRelation.salaryCertificate.index')->with('success', 'Salary Certificate Request Submitted');
    }

    public function update(Request $request, $id)
    {
        $certificate = SalaryCertificate::findOrFail($id);

        $request->validate([
            'passport_number' => 'required|string|max:255',
            'issued_by' => 'required|string|max:255',
            'company_branch' => 'required|string|max:255',
            'salary_in_aed' => 'required|numeric',
            'requested_job_title' => 'required|exists:master_job_positions,id',
            'joining_date' => 'required|date',
            'creation_date' => 'required|date',
            'status' => 'required|in:pending,approved,rejected',
            'comments' => 'nullable|string',
        ]);

        $certificate->update([
            'passport_number' => $request->passport_number,
            'issued_by' => $request->issued_by,
            'company_branch' => $request->company_branch,
            'salary_in_aed' => $request->salary_in_aed,
            'requested_job_title' => $request->requested_job_title,
            'joining_date' => $request->joining_date,
            'creation_date' => $request->creation_date,
            'status' => $request->status,
            'comments' => $request->comments,
        ]);

        return redirect()->route('employeeRelation.salaryCertificate.index')->with('success', 'Salary Certificate updated successfully');
    }

    public function show($id)
    {
        $masterJobPositions = MasterJobPosition::orderBy('name', 'ASC')->select('id', 'name')->get();
        $certificate = SalaryCertificate::findOrFail($id);
        return view('employee_relation.salary_certificate.show', compact('certificate', 'masterJobPositions'));
    }

    public function edit($id)
    {
        $masterJobPositions = MasterJobPosition::orderBy('name', 'ASC')->select('id', 'name')->get();
        $certificate = SalaryCertificate::findOrFail($id);
        return view('employee_relation.salary_certificate.show', compact('certificate', 'masterJobPositions'));
    }

    public function downloadCertificate($id)
    {
        $certificate = SalaryCertificate::findOrFail($id);
        return view('employee_relation.salary_certificate.download_view', compact('certificate'));
    }


    public function generateSalaryCertificate(Request $request, $id)
    {
        set_time_limit(300);

        $certificate = SalaryCertificate::with('requestedFor', 'jobTitle')->findOrFail($id);
        $fileName = 'Salary_Certificate_' . $certificate->requestedFor->name . '_' . Carbon::now()->format('YmdHis') . '.pdf';

        if ($request->download == 1) {
            try {
                $pdfFile = PDF::loadView(
                    'employee_relation.salary_certificate.download_template',
                    compact('certificate')
                )->setPaper('A4', 'portrait')->setWarnings(true);;

                return $pdfFile->download($fileName);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        return view('employee_relation.salary_certificate.download_view', compact('certificate'));
    }
}
