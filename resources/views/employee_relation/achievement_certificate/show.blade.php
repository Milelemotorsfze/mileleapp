@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Review Achievement Certificate Request</h2>

    <div class="card mb-4">
        <div class="card-header">
            <strong>Submitted Details</strong>
        </div>
        <div class="card-body">
            <!-- Display details entered by the first employee -->
            <div class="form-group">
                <label for="achievement_name">Achievement Name:</label>
                <p>{{ $certificate->achievement_name }}</p>
            </div>

            <div class="form-group">
                <label for="purpose_of_request">Purpose of Request:</label>
                <p>{{ $certificate->purpose_of_request }}</p>
            </div>

            <div class="form-group">
                <label for="status">Current Status:</label>
                <p>{{ ucfirst($certificate->status) }}</p>
            </div>

            <!-- Show comments if the status is not pending -->
            @if ($certificate->status != 'pending')
            <div class="form-group">
                <label for="comments">Reviewer Comments:</label>
                <p>{{ $certificate->comments ?? 'No comments yet.' }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Form for the second employee to submit additional details -->
    <div class="card">
        <div class="card-header">
            <strong>Review and Approval</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('employeeRelation.achievementCertificate.update', $certificate->id) }}" method="POST">
                @csrf

                @method('POST')

                <div class="form-group">
                    <label for="employee_id">Employee ID</label>
                    <input type="text" name="employee_id" class="form-control" value="{{ old('employee_id', $certificate->employee_id) }}" required>
                </div>

                <div class="form-group">
                    <label for="name">Employee Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $certificate->name) }}" required>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="approved" {{ $certificate->status == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $certificate->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="comments">Comments</label>
                    <textarea name="comments" class="form-control" rows="4">{{ old('comments', $certificate->comments) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Submit Review</button>
            </form>
        </div>
    </div>
</div>
@endsection
