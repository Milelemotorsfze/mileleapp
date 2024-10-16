@extends('layouts.table')

@section('content')
<div class="container">
    <h2>Achievement Certificate Requests</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sr No</th>
                <th>Achievement Name</th>
                <th>Purpose of Request</th>
                <th>Status</th>
                <th>Date Applied</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($certificates as $certificate)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $certificate->achievement_name }}</td>
                <td>{{ $certificate->purpose_of_request }}</td>
                <td>{{ ucfirst($certificate->status) }}</td>
                <td>{{ $certificate->created_at }}</td>
                <td>
                    <a href="{{ route('employeeRelation.achievementCertificate.show', $certificate->id) }}" class="btn btn-info">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
