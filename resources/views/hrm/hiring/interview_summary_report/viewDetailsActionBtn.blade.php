@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-interview-summary-report','requestedby-create-interview-summary','organizedby-create-interview-summary']);
@endphp
@if ($hasPermission)
<li>
	<a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->employeeHiringRequest->id ?? '')}}">
	<i class="fa fa-eye" aria-hidden="true"></i> View Details
	</a>
</li>
@endif
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-details','requestedby-view-interview-summary','organizedby-view-interview-summary']);
@endphp
@if ($hasPermission)
<li><a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Candidate Details" class="btn btn-sm btn-primary" href="{{route('interview-summary-report.show', $data->id)}}">
	<i class="fa fa-user" aria-hidden="true"></i> Candidate Details
	</a>
</li>
@endif
