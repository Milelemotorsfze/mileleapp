@extends('layouts.table')

@section('content')
<div class="container">
    <h2>Letter Requests</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sr No</th>
                <th>Purpose of Request</th>
                <th>Asked By (Company Name)</th>
                <th>Status</th>
                <th>Date Applied</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($letters as $letter)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $letter->purpose_of_letter_request }}</td>
                <td>{{ $letter->asked_by_company_name }}</td>
                <td>{{ ucfirst($letter->status) }}</td>
                <td>{{ $letter->created_at }}</td>
                <td>
                    <a href="{{ route('employeeRelation.letterRequest.show', $letter->id) }}" class="btn btn-info">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection