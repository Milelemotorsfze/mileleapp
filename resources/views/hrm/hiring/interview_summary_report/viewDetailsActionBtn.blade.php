@canany(['create-interview-summary-report'])
	@php
	    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-interview-summary-report']);
	@endphp
	@if ($hasPermission)
    <li>
        <a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->employeeHiringRequest->id ?? '')}}">
            <i class="fa fa-eye" aria-hidden="true"></i> View Details
        </a>
    </li>
    @endif
@endcanany
@canany(['view-interview-summary-report-details'])
	@php
	    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-details']);
	@endphp
	@if ($hasPermission)
    <li><a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Candidate Details" class="btn btn-sm btn-primary" href="{{route('interview-summary-report.show', $data->id)}}">
        <i class="fa fa-user" aria-hidden="true"></i> Candidate Details
        </a>
    </li>
    @endif
@endcanany
