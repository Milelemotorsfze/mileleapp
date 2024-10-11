<?php

namespace App\Http\Controllers;

use App\Models\AchievementCertificate;
use Illuminate\Http\Request;

class AchievementCertificateController extends Controller
{
    // Show form for creating a new achievement certificate
    public function create()
    {
        return view('employee_relation.achievement_certificate.create');
    }

    // Store the achievement certificate
    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'purpose_of_request' => 'required|string',
        ]);

        AchievementCertificate::create([
            'bank_name' => $request->bank_name,
            'purpose_of_request' => $request->purpose_of_request,
            'status' => 'pending',
        ]);

        return redirect()->route('employeeRelation.achievementCertificate.index')->with('success', 'Achievement Certificate Request Submitted');
    }

    // index all achievement certificates for admin review
    public function index()
    {
        $certificates = AchievementCertificate::all();
        return view('employee_relation.achievement_certificate.index', compact('certificates'));
    }

    // Show a specific achievement certificate for approval
    public function show($id)
    {
        $certificate = AchievementCertificate::findOrFail($id);
        return view('employee_relation.achievement_certificate.show', compact('certificate'));
    }

    // Approve or reject the achievement certificate
    public function update(Request $request, $id)
    {
        $certificate = AchievementCertificate::findOrFail($id);

        $request->validate([
            'employee_id' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'status' => 'required|in:approved,rejected',
            'comments' => 'nullable|string',
        ]);

        $certificate->update([
            'employee_id' => $request->employee_id,
            'name' => $request->name,
            'status' => $request->status,
            'comments' => $request->comments,
        ]);

        return redirect()->route('employeeRelation.achievementCertificate.index')->with('success', 'Achievement Certificate status updated successfully');
    }
}
