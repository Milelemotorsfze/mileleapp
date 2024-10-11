<?php

namespace App\Http\Controllers;

use App\Models\LetterRequest;
use Illuminate\Http\Request;

class LetterRequestController extends Controller
{
    // Show form for creating a new letter request
    public function create()
    {
        return view('employee_relation.letter_request.create');
    }

    // Store the letter request
    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'purpose_of_request' => 'required|string',
        ]);

        LetterRequest::create([
            'bank_name' => $request->bank_name,
            'purpose_of_request' => $request->purpose_of_request,
            'status' => 'pending',
        ]);

        return redirect()->route('employeeRelation.letterRequest.index')->with('success', 'Letter Request is Submitted');
    }

    // index all letter requests for admin review
    public function index()
    {
        $certificates = LetterRequest::all();
        return view('employee_relation.letter_request.index', compact('certificates'));
    }

    // Show a specific letter requests for approval
    public function show($id)
    {
        $certificate = LetterRequest::findOrFail($id);
        return view('employee_relation.letter_request.show', compact('certificate'));
    }

    // Approve or reject the letter requests
    public function update(Request $request, $id)
    {
        $letter = LetterRequest::findOrFail($id);

        $request->validate([
            'employee_id' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'status' => 'required|in:approved,rejected',
            'comments' => 'nullable|string',
        ]);

        $letter->update([
            'employee_id' => $request->employee_id,
            'name' => $request->name,
            'status' => $request->status,
            'comments' => $request->comments,
        ]);

        return redirect()->route('employeeRelation.letterRequest.index')->with('success', 'Letter Request status updated successfully');
    }
}
